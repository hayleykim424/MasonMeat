<?php
include('../autoloader.php');


$products_obj = new Products();
$products = $products_obj -> getProducts(true);

//trim all description
if( $products ){
  foreach( $products as &$product ){
    $product["description"] = TruncateWords::extract($product["description"],10,true);
  }
}

echo json_encode($products);
?>