<?php
session_start();
$nav_obj = new Navigation();
$navigation = $nav_obj -> getNavigationItems();
?>

<?php 
//print_r($_SESSION["username"]);

?> 

<div class="navbarDiv container">
<nav class="navbar navbar-expand-md navbar-light">
  <a class="navbarLogo order-1 float-left" href="index.php">
    <img class ="logo" src="/images/graphics/logonew.jpg">
  </a>
  <button class="navbar-toggler order-4" type="button" data-toggle="collapse" data-target="#nav-collapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  

<!-- SEARCH BAR -->
<form class="navbar-form order-2 w-50 d-flex" method="get" action="search.php" role="search">
  <div class="inner-addon right-addon w-100">
      <i class="glyphicon glyphicon-search"></i>
      <input name="keywords" type="search" class="searchInput form-control flex-fill flex-sm-fill w-100" placeholder="Search"/>
    </div>
</form>


  
  
  <div class="menuDiv collapse navbar-collapse order-10" id="nav-collapse">
    <ul class="navUl navbar-nav justify-content-end d-flex">
      <?php
      if( count($navigation) > 0 ){
        
        foreach( $navigation as $name => $link ){
          //if the link matches the current page, set active as 'active'
          if( $link == $nav_obj -> current_page ){
            $active = "active";
          }
          else{
            unset($active);
          }
          
          echo "<li class=\"nav-item $active text-right\">
                  <a class=\"nav-link navMenu\" href=\"/$link\">$name</a>
                </li>";
        }
      }
      ?>
      
      
      
      
    
    <div class="cart-group d-flex align-self-center order-8 order-md-9">
    <?php
    $cart = new ShoppingCart();
    $cart_count = $cart -> getCartCount();
    ?>
    <a href="shoppingcart.php" class="nav-icon cart mx-1">
      <img class="icon cart" src="images/graphics/cart.png">
      <span id="cart-count" class="badge badge-primary"><?php echo $cart_count; ?></span>
    </a>
    <!--<span class="nav-icon wish mx-1">-->
    <!--  <img class="icon" src="images/graphics/icons/wish-bag.png">-->
    <!--  <span id="wish-count" class="badge badge-primary">1</span>-->
    <!--</span>-->
  </div>
      
      <?php
    if( $_SESSION["username"] ){
      $user = $_SESSION["username"];
      echo "<span class=\"navbar-text welcomeUser\">Hi $user</span>";
    }
    ?>
      
    </ul>
    
    
    
  </div>
</nav>
</div>