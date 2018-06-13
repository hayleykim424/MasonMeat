<?php
  include("autoloader.php");
  if ( isset($_GET["product_id"]) ){
    
    $product_id = $_GET["product_id"];
    
    $product_detail = new ProductDetail( $product_id );
    $product = $product_detail -> product; //getProduct($product_id);
    
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
          
          <div class="alert-success-div"></div>
          
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
            
            
           <!--form for shopping cart and wishlist-->
          <form id="shopping-form" class="my-2 form-inline">
            <div class="form-row w-100">
              <div class="col-8 col-md-3 input-group">
                <div class="input-group product-quantity my-2 my-md-0">
                  <div class="input-group-prepend">
                    <button class="btn btn-outline-primary" data-function="subtract" type="button">&minus;</button>
                  </div>
                  <input type="text" name="quantity" value="1" min="1" class="form-control border-primary text-center flex-fill">
                  <div class="input-group-append">
                    <button class="btn btn-outline-primary" data-function="add" type="button">&plus;</button>
                  </div>
                  <!--product id in the form-->
                  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                </div>
              </div>
              
              <div class="form-row w-100">
              
              <div class="col-12 col-md-7">
                <button class="btn btn-md btn-default" type="submit" name="submit" value="shoppingcart">
                  <img class="icon d-inline-block cart" src="images/graphics/cart.png">
                  Add to cart
                </button>
                <button class="btn btn-md btn-default" type="submit" name="submit" value="wishlist">
                  <img class="icon d-inline-block favourite" src="images/graphics/like.png">
                  Wishlist
                </button>
              </div>
              
              </div>
              
            </div>
          </form>
          
          
          
          <!--<div class="form-row w-100">  -->
          <!--  <div class="col-6 col-sm-4 col-md-4">-->
          <!--      <div class="quantityDiv1 input-group">-->
          <!--        <div class="input-group-prepend">-->
          <!--          <button class="btn btn-outline-primary" data-function="subtract" type="button">&minus;</button>-->
          <!--        </div>-->
          <!--        <input type="text" name="quantity" value="1" min="1" class="quantityInput form-control border-primary text-center flex-fill">-->
          <!--        <div class="input-group-append">-->
          <!--          <button class="btn btn-outline-primary" data-function="add" type="button">&plus;</button>-->
          <!--        </div>-->
                  
                
          <!--      </div>-->
          <!--  </div>-->
          <!--</div>-->
          
          <!--<div class="form-row w-100">-->
              
          <!--  <div class="col col-sm-6" style="max-width:120px;">-->
          <!--    <button class="btn btn-md btn-default" type="submit" name="submit" value="shoppingcart">-->
          <!--      <img class ="cart" src="/images/graphics/cart.png">-->
          <!--      Add to cart-->
          <!--    </button>-->
          <!--  </div>  -->
            
          <!--  <div class="col col-sm-6" style="max-width:150px;">  -->
          <!--    <button class="btn btn-md btn-default" type="submit" name="submit" value="wishlist">-->
          <!--      <img class ="favourite" src="/images/graphics/like.png">-->
          <!--      Add to wishlist-->
          <!--    </button>-->
          <!--  </div>-->
          <!--</div>  -->
            
            
          <!--  <div class="col col-sm-8">-->
          <!--    </div>-->
            
            
            
          </div>
        </div>

      </div>
    </div>
    <script src="js/product-detail.js"></script>
    <script src="js/shopping-cart.js"></script>
    <?php include('includes/footer.php'); ?>
  </body>
</html>

<template id="alert-template">
  <div class="alert alert-dismissible fade show" role="alert">
    <span class="alert-message"></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>