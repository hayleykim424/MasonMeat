<!-- https://mason-meat-project-hayleyhkim.c9users.io/phpmyadmin/ -->

<?php

  include("autoloader.php");
  
  //create an instance of database class
  //$database = new Database();
  //$connection = $database -> connection;
  
  //create instance of products class
  $products_obj = new Products();
  
  $products = $products_obj -> getProducts(true);
  $total_items = $products_obj -> products_total;
  $total_in_page = $products_obj -> current_page_total;
  
  //create an instance of categories class
  $categories_obj = new Categories();
  $categories = $categories_obj -> getCategories(true);
  //inject the all categories link
  $all_categories = array("category_name" => "All categories" , "category_id" => "0");
  array_unshift( $categories, $all_categories );
  //print_r($categories);
  
  //--- [get] variables for the page
  //CURRENT PAGE NUMBER
  $page_var = $_GET["page"];
  //if page number is valid, use it
  if( filter_var( $_GET["page"], FILTER_VALIDATE_INT) ){
    $current_page_number = $_GET["page"];
  }
  //set page number to 1 if invalid
  else{
    $current_page_number = 1;
  }
  //CURRENT CATEGORY ID
  $category_var = base64_decode($_GET["category"]);
  
  if( filter_var($category_var, FILTER_VALIDATE_INT ) ){
    $current_category = $category_var;
  }
  else{
    $current_category = 0;
  }
  
  $page_title = "Products page";
  
?>
<!doctype html>
<html>
  <?php include ('includes/head.php'); ?>
  
  
  <body>
    <?php include('includes/navbar.php'); ?>
    
    <div class="container">

      
      <div class="row">
        <!-- Sidebar-->
        <div class="productCatMenu">
  
          <nav class="nav nav-pills flex-column mt-4 d-none d-md-block">
            <?php
            if( count($categories) > 0 && $categories ){
              foreach( $categories as $category ){
                $cat_name = $category["category_name"];
                $cat_id = $category["category_id"];
                $cat_link = basename($_SERVER["PHP_SELF"]) . 
                "?category=" .
                base64_encode($cat_id);
                
                if( $cat_id == $current_category ){
                  $active_class = "active";
                }
                else{
                  unset( $active_class );
                }
                
                echo "<a class=\"nav-link $active_class\" href=\"$cat_link\">$cat_name</a>";
              }
            }
            ?>
          </nav>
          <!--dropdown element for mobile-->
          <div class="dropdown category-nav d-md-none my-2">
            <a class="btn btn-primary btn-block dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Categories
            </a>
            <div class="dropdown-menu w-100">
              <?php
              if( count($categories) > 0 && $categories ){
                foreach( $categories as $category ){
                  $cat_name = $category["category_name"];
                  $cat_id = $category["category_id"];
                  $cat_link = basename($_SERVER["PHP_SELF"]) . "?category=$cat_id";
                  
                  if( $cat_id == $current_category ){
                    $active_class = "active";
                  }
                  else{
                    unset( $active_class );
                  }
                  
                  echo "<a class=\"dropdown-item $active_class\" href=\"$cat_link\">$cat_name</a>";
                }
              }
              ?>
            </div>
          </div>
        </div>
        
        <!--Products column-->
        <div class="col-md-10">
          
          <?php include('includes/breadcrumb.php'); //include breadcrumb; ?>
          
          <div class="row justify-content-between product-nav my-md-4 my-sm-2">
            
            
            <div class="col-4 col-sm-4 col-md-2 text-right product-nav-text">
              <span class="d-none d-sm-inline">Total of</span> <?php echo $total_items; ?>  <span class="d-sm-inline">products</span>
            </div>
          </div>
          
          <?php
          
          if( count($products) > 0 && $products){
            $col_counter = 0;
            $product_counter = 0;
            foreach( $products as $product ){
              $col_counter++;
              $product_counter++;
              if( $col_counter == 1 ){
                echo "<div class=\"row\">";
              }
              //print out columns
              $id = $product["id"];
              $name = $product["name"];
              $price = $product["price"];
              $description = TruncateWords::extract($product["description"],10,true);
              $image = $product["image"];
              
              echo "<div class=\"col-sm-3 product-column\">";
              echo "<a href=\"detail.php?product_id=$id\">";
              echo "<h4 class=\"product-name\">$name</h4>";
              echo "<img class=\"product-thumbnail img-fluid\" src=\"images/products/$image\">";
              echo "<h5 class=\"price product-price\">$price</h5>";
              echo "<p class=\"product-description\">$description</p>";
              echo "</a>";
              echo "</div>";
            
            
            if($col_counter == 4 || $product_counter == count($products) ) { //keeps looping until the counter hits 4
              echo "</div>";
              echo "<hr>";
              $col_counter = 0; //then set col_counter back to 0
            }
          }
        }
      
        include('includes/pagination.php');
      
        ?>
      </div> 
    </div>
  </div>
  
  <?php include('includes/footer.php'); ?>  
     
  </body>
</html>