<?php
class Navigation{
  private $nav_items = array();
  public $current_page;
  private $json;
  public function __construct($json = false){
    $this -> json = $json;
    //get the current page so it can be marked as active in the navigation bar
    $this -> current_page = $this -> getCurrentpage();
    
    if( $this -> checkUserAuthState() == true ){
      $this -> nav_items = array(
      "PRODUCTS" => "products.php",
      "ABOUT" => "about.php",
      "CONTACT" => "contact.php",
      "LOG OUT" => "logout.php"
      );
    }
    else{
      $this -> nav_items = array(
      "PRODUCTS" => "products.php",
      "ABOUT" => "about.php",
      "CONTACT" => "contact.php",
      "LOGIN" => "login.php"
      );
    }
  }
  protected function getCurrentpage(){
    //get the name of the current page
    $uri = basename( $_SERVER["REQUEST_URI"] );
    if( $uri == "" ){
      $uri = "index.php";
    }
    return $uri;
  }
  private function checkUserAuthState(){
    //check if user is logged in or not via a session variable
    if( isset($_SESSION["email"]) || isset($_SESSION["username"])){
      return true;
    }
    else{
      return false;
    }
  }
  public function getNavigationItems(){
    if($this -> json == true ){
      return json_encode( $this -> nav_items );
    }
    else{
      return $this -> nav_items;
    }
  }
}
?>