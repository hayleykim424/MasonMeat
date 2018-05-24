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
      
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3311.0071955302237!2d151.23803941521138!3d-33.91521438064356!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12b218d9ba1f63%3A0xa9c1ab9c3b255b89!2s100+Belmore+Rd%2C+Randwick+NSW+2031!5e0!3m2!1sen!2sau!4v1527165486404" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
      <p>Open 7 days 10am - 6pm. Call us on 02 9202 3033 if you have any enquiries!</p>
      
      
      
      
    </div>
      
    <?php include('includes/footer.php'); ?>
    
  </body>
    
</html>