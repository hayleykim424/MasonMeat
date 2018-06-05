<?php
class ShoppingCart extends Database{
  public $auth_state;
  public $errors = array();
  public $cart_items;
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
    }
  }
  public function addItem($product_id, $quantity){
    //if user does not already have a cart, create one
    if( !$this -> cart_id && $this -> account_id ){
      $this -> cart_id = $this -> createCart( $this -> account_id );
    }
    else{
      $this -> errors["auth"] = "user is not logged in";
      return false;
    }
    //item can only be added when user is logged in and cart_id has value (user has cart)
    if($this -> auth_state == true && $this -> cart_id ){
      //add the item to cart in the database
      $query = "INSERT INTO shopping_cart_items ( product_id,quantity,added ) 
      VALUES ( ?, ?, NOW() )";
      $statement = $this -> connection -> prepare( $query );
      $statement -> bind_param( 'ii' , $product_id, $quantity );
      if( $statement -> execute() ) {
        return true;
      }
      else{
        //store any errors in errors array
        $this -> errors["database"] = $this -> connection -> error;
        return false;
      }
      
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
    }
  }
  public function updateItem( $product_id, $quantity ){
    if($this -> auth_state == true && $this -> cart_id){
      //update item
      $query = "UPDATE shopping_cart_items SET quantity= ? WHERE product_id = ?";
      $statement = $this -> connection -> prepare( $query );
      $statement -> bind_param( "ii", $this  );
      if( $statement -> execute() ){
        return true;
      }
      else{
        $this -> errors["database"] = $this -> connection -> error;
        return false;
      }
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
      WHERE shopping_cart_items.cart_id = ? ";
      $statement = $this -> connection -> prepare( $query );
      $statement -> bind_param( "i" , $this -> cart_id );
      if( $statement -> execute() ){
        $cart_items = array();
        //get the result products
        $result = $statement -> get_result;
        while( $row = $result -> fetch_assoc() ){
          //add row to cart_items array
          array_push( $cart_items , $row );
        }
        //store cart items in $this -> cart_items
        $this -> cart_items = $cart_items;
        return true;
      }
      else{
        $this -> errors['database'] = $this -> connection -> error;
        return false;
      }
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
    return $cart_id;
  }
  protected function createCart( $account_id ){
    $query = "INSERT INTO shopping_cart (account_id,update,created,active) VALUES (?,NOW(),NOW(),1)";
    $statement = $this -> connection -> prepare( $query );
    $statement -> bind_param( 'i', $account_id );
    $statement -> execute();
    //insert_id is a returned after insert operation containing the id of the newly created row
    $cart_id = $this -> connection -> insert_id;
    return $cart_id;
  }
}
?>