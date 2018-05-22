<?php
include('../autoloader.php');

$class = new Account();
if( $_SERVER['REQUEST_METHOD'] == 'POST'){
  //receive the post variables from the form
  $credential = $_POST["credentials"];
  $password = $_POST["password"];
  $account = new Account();
  $login = $account -> authenticate( $credential, $password );
  $response = array();
  
  if( $login == true ){
    $response["success"] = true;
  }
  else{
    $response["success"] = false;
    $response["errors"] = implode(' ',$account -> errors);
  }
  echo json_encode($response);
}
?>