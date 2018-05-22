<?php
include('../autoloader.php');

$class = new Account();

if( $_SERVER['REQUEST_METHOD'] == 'POST'){
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  //send data to account -> register
  $registration = $class -> register($username, $email, $password );
  $response = array();
  $errors = array();
  
  if( $registration == true ){
    $response["success"] = true;
  }
  else{
    $response["success"] = false;
    $errors = $class -> errors;
    $response["errors"] = $errors;
  }
  echo json_encode($response);
}
?>