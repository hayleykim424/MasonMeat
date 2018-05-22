<!-- https://mason-meat-project-hayleyhkim.c9users.io/phpmyadmin/ -->


<?php


  include("autoloader.php");
  
  //start session
  //session_start();
  
  $page_title = "Home page";
  
?>

<!doctype html>
<html>
  <?php include ('includes/head.php'); ?>
  <body>
    <?php //print_r($_SESSION); echo "line 56 in index.php"; ?> 
    
    <?php include('includes/navbar.php'); ?>
    <?php include('includes/homeslider.php'); ?>
    <?php include('includes/homegallery.php'); ?>
    
    <div class="container">
      
      <h1>Mason's Best Meat</h1>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
      
      <p>when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
      
      
      
      
    </div>
      
    <?php include('includes/footer.php'); ?>
    
  </body>
    
</html>