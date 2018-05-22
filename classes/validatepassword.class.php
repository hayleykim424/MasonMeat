<?php
class ValidatePassword{
  public $errors = array();
  public $minimum;
  public function __construct($min = 8){
    $this -> minimum = $min;
  }
  public function validate($password){
    //check if it meets the minimum length
    if( strlen($password) < $this -> minimum ){
      array_push( $this -> errors, 'must be 8 characters or more');
    }
    //check if it is all letters
    if( ctype_alpha($password) ){
      array_push( $this -> errors, 'must contain numbers');
    }
    //check if it is all numbers
    if( ctype_digit($password) ){
      array_push( $this -> errors, 'must contain letters');
    }
    //convert password to an array
    $letters = str_split($password);
  }
}
?>