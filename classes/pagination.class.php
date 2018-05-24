<?php
class Pagination{
  public static $total_pages;
  public static function create( $total=0, $page_size=8 ){
    if($total > 0){
      //total number of pages = total / items per page (page size)
      $total_pages = ceil( $total / $page_size );
      //set the total pages variable for the class
      //since it's a static class, we use self:: instead of $this ->
      self::$total_pages = $total_pages;
      $page_numbers = array();
      
      //populate page_numbers array, starting from 1
      for( $i=1 ; $i <= $total_pages; $i++ ){
        array_push( $page_numbers , $i );
      }
      
      //get the number of current page
      if( isset( $_GET["page"] ) == false || filter_var($_GET["page"],FILTER_VALIDATE_INT) == false){
        $current_page = 1;
      }
      else{
        $current_page = $_GET["page"];
      }
      
      //get the id of current category
      $selected_category = $_GET["category"];
      if( $selected_category !== 0 ){
        $cat_link = "&category=$selected_category";
      }
      
      //get the url of current page
      $current_url = self::getCurrentPage();
      
      //output the nav element
      echo "<nav class=\"d-flex justify-content-center\" aria-label=\"Product Navigation\">
            <ul class=\"pagination pUl\">";
      //if current page is page 1, disable the previous page link
      if( $current_page - 1 <= 0 ){
        $prev_class = 'disabled';
        $prev_link = '#';
      }
      else{
        $prev_class = '';
        $prev_link = $current_url . '?' . 'page=' . ($current_page - 1) . $cat_link;
      }
      echo "<li class=\"page-item $prev_class fl\">
              <a class=\"page-link\" href=\"$prev_link\" aria-label=\"Previous\">
                <span aria-hidden=\"true\">&laquo;</span>
                <span class=\"sr-only\">Previous</span>
              </a>
            </li>";
      
      //output the paginators
      foreach( $page_numbers as $number ){
        $page_url = self::getCurrentPage() . '?' . 'page=' . $number . $cat_link;
        if( $number == $current_page ){
          $page_class = 'active'; 
        }
        else{
          unset( $page_class );
          $page_class = 'd-none d-md-block d-lg-block';
        }
        echo "<li class=\"page-item $page_class\">
                <a class=\"page-link\" href=\"$page_url\">$number</a>
             </li>";
      }
      //if current page is page is the last page, disable the next page link
      if( $current_page + 1 > $total_pages ){
        $next_class = 'disabled';
        $next_link = '#';
      }
      else{
        $next_class = '';
        $next_link = $current_url . '?' . 'page=' . ($current_page + 1) . $cat_link;
      }
      echo "<li class=\"page-item $next_class\">
              <a class=\"page-link\" href=\"$next_link\" aria-label=\"Next\">
                <span aria-hidden=\"true\">&raquo;</span>
                <span class=\"sr-only\">Next</span>
              </a>
            </li>";
      echo "</ul>
            </nav>";
    }
  }
  public static function getCurrentPage(){
    $current_page = $_SERVER['PHP_SELF'];
    return $current_page;
  }
}
?>