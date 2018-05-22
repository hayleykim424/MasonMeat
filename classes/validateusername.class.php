<?php
class ValidateUsername extends Database{
  public $errors = array();
  public $minimum = 6;
  public $maximum = 16;
  public function __construct($min=6,$max=16){
    parent::__construct();
    $this -> minimum = $min;
    $this -> maximum = $max;
  }
  public function validate($username){
    $errors = array();
    
    //check if username contains at least the desired number of characters
    if( strlen( $username ) < $this -> minimum ){
      array_push( $this -> errors, 'must be at least 6 characters');
    }
    if( strlen($username) > $this -> maximum ){
      array_push( $this -> errors, 'must be less than 16 characters');
    }
    //check if first character is a number
    // if( ctype_digit( substr($username, 0, 1 )) ){
    //   array_push( $this -> errors, 'first character cannot be a number');
    // }
    
    //check if username is all numbers
    if( ctype_digit($username) == true ){
      array_push( $this -> errors, 'must contain letters');
    }
    
    //check if username contains a space in the middle
    //explode the username into an array;
    $chars = str_split($username);
    foreach( $chars as $char ){
      if( ctype_space($char) ){
        array_push( $this -> errors, 'cannot contain spaces');
        break;
      }
    }
    
    //check if username contains html tags by stripping tags and comparing to the length of the original
    if( strlen( strip_tags($username) ) !== strlen($username) ){
      array_push( $this -> errors, 'cannot contain html tags' );
    }
    //check if username contains symbols
    if( ctype_alnum( $username ) == false ){
      array_push( $this -> errors, 'cannot contain symbols');
    }
    //check if username contains spaces at the beginning or end
    $trimmed = trim($username);
    if( strlen($username) !== strlen($trimmed) ){
      array_push( $this -> errors, 'cannot contain spaces before or after');
    }
    
    //check if username exists
    if( $this -> checkUserExists($username) ){
      array_push( $this -> errors, 'username already used');
    }
    
    if( count($this -> errors) > 0 ){
      return false;
    }
    else{
      return true;
    }
  }
  public function checkUserExists($username){
    $query = "SELECT username FROM accounts WHERE username = ? OR LOWER(username) = ?";
    $statement = $this -> connection -> prepare( $query );
    $statement -> bind_param('ss',$username, strtolower($username) );
    if( $statement -> execute() ){
      $result = $statement -> get_result();
      if( $result -> num_rows > 0 ){
        while( $row = $result -> fetch_assoc() ){
          if(strtolower($row["username"]) == strtolower($username)){
            return true;
            break;
          }
        }
      }
      else{
        return false;
      }
    }
  }
}
?>