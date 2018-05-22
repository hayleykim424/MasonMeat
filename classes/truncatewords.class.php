<?php
class TruncateWords{
  public static function extract($str, $limit = 10, $ellipsis = false ){
    //explode str into an array
    $words_array = explode(" ", strip_tags($str) );
    $sentence = '';
    $counter = 0;
    while( $counter < $limit ){
      if($words_array[$counter]!=="." 
        && $words_array[$counter]!==" " 
        && $words_array[$counter]!=="&nbsp;")
      {
        $sentence = $sentence . " " .trim($words_array[$counter]);
      }
      $counter++;
    }
    if( $ellipsis == true ){
      return $sentence . " &hellip;";
    }
    else{
      return $sentence;
    }
    
  }
}
?>