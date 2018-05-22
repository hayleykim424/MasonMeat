<?php
include('autoloader.php');

$search_words = $_GET['keywords'];
if( isset($search_words) == true ){
  $search = new ProductSearch();
  $results = $search -> search( $search_words );
}
?>

<!doctype html>
<html>
  <?php include ('includes/head.php'); ?>
  <body>
    <?php include('includes/navbar.php'); ?>
    <div class="container content">
      <?php
      if( $results !== false ){
        $results_count = count( $results );
        if( $results_count > 0 ){
          echo "
          <div class=\"alert alert-success\">
          <p>You searched for <strong>$search_words</strong>".
          " resulting in <strong>" . $results_count ."</strong> " .
          ($results_count == 1 ? "product" : "products")  . "</p>
          </div>";
          
          //loop through the result
          foreach( $results as $product ){
            $id = $product["id"];
            $name = $product["name"];
            $price = $product["price"];
            $image = $product["image"];
            echo "<div class=\"row seach-row\">
              <div class=\"col-sm-2\">
                <img class=\"img-fluid\" src=\"/images/products/$image\">
              </div>
              <div class=\"col\">
                <h4>$name</h4>
                <h6 class=\"price\">$price</h6>
                <div class=\"text-right\">
                  <a href=\"detail.php?product_id=$id\" class=\"btn btn-outline-info\">View Details</a>
                </div>
              </div>
            </div>
            <hr>";
          }
        }
      }
      else{
        echo "<div class=\"alert alert-warning\">
        <p>Your search for <strong>$search_words</strong> returned <strong>0</strong> result</p>
        </div>";
      }
      
      ?>
    </div>
  </body>
</html>