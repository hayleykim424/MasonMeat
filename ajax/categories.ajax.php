<?php
include('../autoloader.php');
$cat = new Categories();
$categories = $cat -> getCategories( true );

//inject all categories into the array
$all_categories = array("category_name" => "All categories" , "category_id" => "0");
array_unshift( $categories, $all_categories );

//return the categories as a json
echo json_encode( $categories );
?>