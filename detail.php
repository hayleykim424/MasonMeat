<?php
  include("autoloader.php");
  if ( isset($_GET["product_id"]) ){
    
    $product_id = $_GET["product_id"];
    
    $product_detail = new ProductDetail( $product_id );
    $product = $product_detail -> product;
    
    $product_name = $product[0]["name"];
    $product_price = $product[0]["price"];
    $product_description = $product[0]["description"];
  }
  else{
  echo "You will be redirected to the home page after 5 seconds";
  header( "location:index.php" );
}
$page_title = $product_name;
//print_r($product);
?>


<!doctype html>
<html>
  <?php include ('includes/head.php'); ?>
  <body>
    <?php include('includes/navbar.php'); ?>
    <div class="container content">
      
      <?php
      include('includes/breadcrumb.php');
      ?>
      
      <div class="row">
        <div class = "col-sm-6">
             <?php
            if ( count($product) > 0 ){
              foreach ($product as $product_image) {
                $image = $product_image["image"];
                echo "<img class=\"img-fluid\" src=\"/images/products/$image\">";
              }
            }
          ?>
            
        </div>
        
        <div class="col-sm-6">
          <h2>
            <?php echo $product_name; ?>
          </h2>
          <p class="price">
            <?php echo $product_price; ?>
          </p>
          <p class="description">
            <?php echo $product_description; ?>
          </p>
          
          <div>
            
            <button>Buy</button>
            
            
          </div>
        </div>

      </div>
    </div>
  </body>
</html>