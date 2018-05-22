<?php
session_start();
//get the page the user comes from
$origin = $_SERVER['HTTP_REFERER'];
//unset session variables
unset($_SESSION["username"]);
unset($_SESSION["email"]);
unset($_SESSION["account_id"]);

//redirect the user to $origin
header("location: $origin");

?>