<?php
  include('autoloader.php');


//handle POST request
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $credentials = $_POST["credentials"]; //taken from the input with the name="credentials" and same for password
  $password = $_POST["password"];
  
  //Create instance of account class
  $account = new Account(); //Account class creates the session
  $login = $account -> authenticate($credentials, $password);
  if( $login == true) {
    //All good.
    $destination = "index.php";
    header("location: $destination");
  }
  else{
    //get errors
    $errors = $account -> errors;
  }
  
}
?>

<!doctype html>
<html>
  <?php include ("includes/head.php"); ?>
    
  <body>
    <?php include('includes/navbar.php'); //including navbar on the top of the page ?>
    <div class = "container content">
      <div class="row justify-content-md-center">
        
        <div class = "col-md-3">
          <img class = "" src="/images/graphics/loginImage.png" alt="Meat image">
          
        </div>
        
        <div class = "col-md-3">
          <?php
          if(count($account -> errors) > 0) {
            $error_string = implode('', $account -> errors);//first parameter of implode() is seperator between the items of the array.
            $alert = "<div class=\"alert alert-warning alert-dismissible fade show\" role=\"alert\">
                        $error_string
                        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                          <span aria-hidden=\"true\">&times;</span>
                        </button>
                      </div>";
            echo $alert;
          }
            
          ?>
          <h4>Login to your account</h4>
          <form id="login-form" method="post" action="login.php" novalidate>
            <div class="form-group">
              <label for="credentials">Email address or User name</label>
              <input id="credentials" class="form-control" type="text" name="credentials" placeholder="Email or Username" required>
              <div class="invalid-feedback">Please type a valid username or email</div>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input id="password" class="form-control" type="password" name="password" placeholder="password" required>
              <div class="invalid-feedback">Please type a valid password</div>
            </div>
            <div class="text-center">
              <button type="submit" name="login" class="btn .btn-default btn-block">Log in</button>
            </div>
            <p class="my-4">Don't have an account? <a class="loginRegisterLink" href="register.php">Register</a> here</p>
            
          </form>
        </div>
      </div>
    </div>
    <script src="/js/login.js"></script>
  </body>
  <?php include('includes/footer.php'); ?>
</html>

<template id="alert-template">
  <div class="alert alert-dismissible fade show" role="alert">
    <span class="alert-message"></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>
