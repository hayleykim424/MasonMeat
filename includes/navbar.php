<?php
session_start();
$nav_obj = new Navigation();
$navigation = $nav_obj -> getNavigationItems();
?>

<?php 
print_r($_SESSION["username"]);

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
                  <a class=\"nav-link\" href=\"/$link\">$name</a>
                </li>";
        }
      }
      ?>
      
      <?php
    if( $_SESSION["username"] ){
      $user = $_SESSION["username"];
      echo "<span class=\"navbar-text\">Hello $user</span>";
    }
    ?>
      
    </ul>
    
    
    
  </div>
</nav>
</div>