<?php
//handle ajax request to add item to the shopping cart
if( $_SERVER["REQUEST_METHOD"] == "POST"){
  //include the autoloader
  include( '../autoloader.php' );
  
  //add an array to store responses
  $response = array();
  //add an array to store errors
  $errors = array();
  
  //create instance of shopping cart class
  $cart = new ShoppingCart();
  //get action
  $action = $_POST["action"];
  //if the request was to add an item to the cart
  if( $action == "add" ){
    //add item to cart
    $product_id = $_POST["productId"];
    $quantity = $_POST["quantity"];
    if( $cart -> addItem( $product_id , $quantity) ){
      //success
      $response["success"] = true;
    }
    else{
      //failed
      $response["success"] = false;
      $errors = $cart -> errors;
      //if error is caused by the user not being logged in
      //response should contain redirect page, product id and quantity
      if( isset($cart -> errors['auth']) ){
        $errors['auth'] = 'not authorized';
        $response["redirect"] = "login.php";
        //add item and quantity to response
        $response["product_id"] = $product_id;
        $response["quantity"] = $quantity;
      }
    }
    $response["cart_count"] = $cart -> getCartCount();
  }
  //if the command is to list the shopping cart
  if( $action == "list" ){
    $cart_list = $cart -> listCart();
    if( $cart_list ){
      $response["success"] = true; 
      $response["items"] = $cart -> cart_items['items'];
      $response['total'] = $cart -> cart_items['total'];
    }
    else{
      $response["success"] = false;
      $response["redirect"] = "login.php";
      $response["origin"] = "shoppingcart.php";
    }
    $errors = $cart -> errors;
  }
  //if the command is to update an item in the shopping cart
  if( $action == 'update' ){
    $product_id = $_POST["productId"];
    $quantity = $_POST["quantity"];
    $update = $cart -> updateItem($product_id,$quantity);
    if( $update == true ){
      $response['success'] = true;
    }
    else{
      $response['success'] = false;
    }
    $errors = $cart -> errors;
  }
  
  if( count($errors) > 0 ){
    $response["errors"] = $errors;
  }
  echo json_encode($response);
}
?>