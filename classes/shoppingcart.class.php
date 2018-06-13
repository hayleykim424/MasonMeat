<?php
class ShoppingCart extends Database{
  public $auth_state;
  public $errors = array();
  public $cart_items;
  public $cart_count;
  private $cart_id;
  private $account_id;
  public function __construct(){
    //connect to database
    parent::__construct();
    //if session has not already started, start it
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    //check if user is not logged in
    if($_SESSION['account_id'] == false ){
      
      $this -> auth_state = false;
      $this -> errors["auth"] = "user is not logged in";
    }
    else{
      $this -> auth_state = true;
      //set account_id
      $this -> account_id = $_SESSION['account_id'];
      //get user cart if it exists and store its id in $this -> cart_id
      $this -> cart_id = $this -> findUserCart( $this -> account_id );
      $this -> cart_count = $this -> getCartCount();
    }
  }
  
  public function addItem($product_id, $quantity){
    //if user is not logged in, return false
    if( $this -> auth_state == false ){
      $this -> errors["auth"] = "user is not logged in";
      return false;
    }
    
    //if user does not already have a cart, create one
    if( !$this -> cart_id && $this -> account_id ){
      $this -> cart_id = $this -> createCart( $this -> account_id );
    }
    
    //item can only be added when user is logged in and cart_id has value (user has cart)
    if($this -> auth_state == true && $this -> cart_id ){
      //add the item to cart in the database
      $query = "INSERT INTO shopping_cart_items ( cart_id, product_id,quantity,added ) 
      VALUES ( ? , ?, ?, NOW() )";
      $statement = $this -> connection -> prepare( $query );
      $statement -> bind_param( 'iii' , $this-> cart_id, $product_id, $quantity );
      if( $statement -> execute() ) {
        return true;
      }
      else{
        //check if error is duplicate error, ie product already in cart
        if( $this -> connection -> errno == '1062'){
          //get the item
          $item = $this -> getItem( $product_id );
          //get the existing quantity
          $old_quantity = $item['quantity'];
          //add the new quantity
          $new_quantity = $old_quantity + $quantity;
          //update the item
          $item_update = $this -> updateItem( $product_id, $new_quantity );
          return $item_update ? true : false;
        }
        //store any errors in errors array
        $this -> errors["database"] = $this -> connection -> error;
        return false;
      }
      $this -> cart_count = $this -> getCartCount();
    }
    
  }
  
  public function removeItem($product_id){
    if($this -> auth_state == true && $this -> cart_id){
      //remove item
      $query = 'DELETE FROM shopping_cart_items WHERE cart_id = ? AND product_id = ?';
      $statement = $this -> connection -> prepare( $query ); 
      $statement -> bind_param( "ii" , $this -> cart_id , $product_id );
      if( $statement -> execute() ){
        return true;
      }
      else{
        $this -> errors["database"] = $this -> connection -> error;
        return false;
      }
      $this -> cart_count = $this -> getCartCount();
    }
  }
  
  public function updateItem( $product_id, $quantity ){
    if($this -> auth_state == true && $this -> cart_id){
      //update item
      $query = "UPDATE shopping_cart_items SET quantity= ? WHERE product_id = ?";
      $statement = $this -> connection -> prepare( $query );
      $statement -> bind_param( "ii", $quantity , $product_id  );
      if( $statement -> execute() ){
        return true;
      }
      else{
        $this -> errors["database"] = $this -> connection -> error;
        return false;
      }
      $this -> cart_count = $this -> getCartCount();
    }
  }
  public function getCartCount(){
    $query = 'SELECT COUNT(product_id) AS count FROM shopping_cart_items WHERE cart_id = ?';
    $statement = $this -> connection -> prepare( $query );
    $statement -> bind_param( 'i' , $this -> cart_id );
    if( $statement -> execute() ){
      $result = $statement -> get_result();
      $row = $result -> fetch_assoc();
      $count = $row['count'];
      $_SESSION['cart_count'] = $count;
      return $count;  
    }
    else{
      return false;
    }
    
  }
  public function getItem($product_id){
    $query = 'SELECT item_id, product_id, quantity FROM shopping_cart_items WHERE cart_id = ? AND product_id = ?';
    $statement = $this -> connection -> prepare( $query );
    $statement -> bind_param('ii', $this -> cart_id , $product_id );
    if ($statement -> execute() ){
      $result = $statement -> get_result();
      if( $result -> num_rows > 0 ){
        $product = $result -> fetch_assoc();
        return $product;
      }
      else{
        return false;
      } 
    }
    else{
      return false;
    }
  }

  public function listCart(){
    if($this -> auth_state == true && $this -> cart_id){
      //this query joins several tables to get product information and image
      $query = "SELECT 
      shopping_cart_items.item_id AS item_id, 
      shopping_cart_items.product_id AS product_id, 
      shopping_cart_items.quantity AS quantity,
      products.name AS name,
      products.price AS price,
      images.image_file_name AS image
      FROM shopping_cart_items 
      INNER JOIN products 
      ON shopping_cart_items.product_id = products.id 
      INNER JOIN products_images 
      ON products.id = products_images.product_id 
      INNER JOIN images
      ON products_images.image_id = images.image_id 
      WHERE shopping_cart_items.cart_id = ? GROUP BY product_id";
      $statement = $this -> connection -> prepare( $query );
      $statement -> bind_param( "i" , $this -> cart_id );
      if( $statement -> execute() ){
        $cart_items = array();
        //get the result
        $result = $statement -> get_result();
        if( $result -> num_rows > 0){
          //store total price of cart items
          $total_price = 0;
          while( $row = $result -> fetch_assoc() ){
            //add price x quantity to total price
            $total_price += ( $row['price'] * $row['quantity'] );
            //add row to cart_items array
            array_push( $cart_items , $row );
          }
          //add total price to cart_items array
          $this -> cart_items['total'] = $total_price;
          //store cart items in $this -> cart_items
          $this -> cart_items['items'] = $cart_items;
          return true;
        }
        else{
          $this -> errors['database'] = $this -> connection -> error;
          return false;
        }
        
      }
      else{
        $this -> errors['database'] = $this -> connection -> error;
        return false;
      }
      $this -> cart_count = $this -> getCartCount();
    }
    //if user is not logged in
    else{
      $this -> errors['auth'] = "user not logged in";
      return false;
    }
  }
  
  protected function findUserCart( $account_id ){
    //check if user already has an active cart
    $query = "SELECT cart_id FROM shopping_cart WHERE account_id= ? AND active=1";
    $statement = $this -> connection -> prepare( $query );
    $statement -> bind_param( 'i', $account_id );
    $statement -> execute();
    $result = $statement -> get_result();
    
    //cart does exist
    $row = $result -> fetch_assoc();
    $cart_id = $row['cart_id'];
    $statement -> close();
    $this -> cart_count = $this -> getCartCount();
    return $cart_id;
  }
  
  protected function createCart( $account_id ){
    $query = "INSERT INTO shopping_cart (account_id,updated,created,active) VALUES (?,NOW(),NOW(),1)";
    $statement = $this -> connection -> prepare( $query );
    $statement -> bind_param( 'i', $account_id );
    $statement -> execute();
    //insert_id is a returned after insert operation containing the id of the newly created row
    $cart_id = $this -> connection -> insert_id;
    return $cart_id;
  }
}
?>