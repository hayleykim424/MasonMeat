<?php
session_start();
include('autoloader.php');

if( $_SESSION )


$page_title = "Shopping Cart";
?>

<!doctype html>
<html>
  <?php include ('includes/head.php'); ?>
  <body>
    <?php include('includes/navbar.php'); ?>
    <div class="container" id="shopping-list">
      
    </div>
  
    <script src="/js/shopping-cart-page.js"></script>
  </body>
</html>