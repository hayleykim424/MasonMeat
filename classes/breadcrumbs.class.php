<?php
class Breadcrumbs{
  static function create( $title ){
    $breadcrumbs = array();
    $root = array("Home" => "index.php");
    array_push( $breadcrumbs, $root );
    //
    if( self::getCurrentPage() !== 'index.php'){
      $current = self::getPageName( $title );
      $current_page = array( $current => NULL);
      array_push( $breadcrumbs, $current_page );
    }
    
    //output Bootstrap breadcrumb structure
    echo "<ol class=\"breadcrumb bg-light\">";
    
    $counter = 0;
    $length = count($breadcrumbs);
    
    foreach( $breadcrumbs as $breadcumb_item ){
      $name = key($breadcumb_item);
      $link = $breadcumb_item[$name];
      
      echo "<li class=\"breadcrumb-item\">";
      
      if( isset($link) && $length > 1){
        echo "<a href=\"$link\">$name</a>";
      }
      else{
        echo $name;
      }
      echo "</li>";
      
      $counter++;
    }
    echo "</ol>";
  }
  static function getCurrentPage(){
    $uri = basename( $_SERVER["PHP_SELF"] );
    if( $uri == "" ){
      $uri = "index.php";
    }
    return $uri;
  }
  static function getPageName( $title ){
    if( $title ){
      
      return $title;
    }
    else{
      return $_SERVER["QUERY_STRING"];
    }
  }
}
?>