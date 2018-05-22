<?php
  include('autoloader.php');


//Check for POST request
if( $_SERVER['REQUEST_METHOD'] == 'POST'){ //if server receives a post request. (Receive variables from form) POST must be all uppercase

  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  
  $account = new Account();
  $registration = $account -> register($username, $email, $password);
  
  $success = array();
  $errors = array();
  
  if($registration == true) {
    $success["registeration"] = "Account successfully created!";
  }
  else{
    $errors["registeration"] = "There has been an error!";
  }
}
?>


<!doctype html>
<html>
  <?php include ("includes/head.php"); ?>
    
  <body>
    <?php include('includes/navbar.php'); //including navbar on the top of the page ?>
    <div class = "container content">
      <?php
        if(count($success) > 0){
          $msg = implode(" ", $success); //implode: converting array into string
        
      
      
          echo "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
            <strong>Success</strong> $msg
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
              <span aria-hidden=\"true\">&times;</span>
            </button>
          </div>";
        }
        
        if(count($errors) > 0){
          $msg = implode( " ", $errors );
          echo "<div class=\"alert alert-warning alert-dismissible fade show\" role=\"alert\">
          <strong>Holy guacamole!</strong> You should check in on some of those fields below.
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
            <span aria-hidden=\"true\">&times;</span>
          </button>
          </div>";
           
           
        }
        
      ?>
      
      
      
      
      <div class="row">
        <div class = "col-md-4 offset-md-4">
          <div class="alert-success-div"></div>
          
          <h4>Register for an account</h4>
          <form id = "register-form" method = "post" action = "register.php" novalidate>
            <div class = "form-group"> <!--setting up bootstrap form --> 
              <label for = "username">Username</label>
              <!--  class = "form-control" aligns the form-->
              <input id = "username" class = "form-control" type = "text" name = "username" placeholder = "example123" required>
              <div class="invalid-feedback">Please enter a valid username</div>
              <!-- <div class="alert-username"></div> -->
            </div>
            
            <div class ="form-group">
              
              <label for = "email">Email address</label>
              <input id = "email" class = "form-control" type = "email" name = "email" placeholder = "example123@email.com" required>
              <div class="invalid-feedback">Please enter a valid email</div>
             <!-- <div class="alert-email"></div> -->
              
            </div>
            
            <div class = "form-group">
              
              <label for="password">Password</label>
              <input id = "password" class = "form-control" type="password" name="password" placeholder = "minimum 8 characters" required>
              <div class="invalid-feedback">Please enter a valid password</div>
              <!--<div class="alert-password"></div> -->
            </div>
            
            <div class = "text-center">
              
              <button type = "submit" class = "btn btn-primary btn-block" name = "register-btn">Register</button>
              
            </div>
            
            <p class="my-4">Already have an account? <a href="login.php">Log in</a> here</p>
            
          </form>
        </div>
      </div>
    </div>
    
    <script src="js/register.js">
      
    </script>
    
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