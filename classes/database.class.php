<?php
class Database {
  private $username;
  private $password;
  private $host;
  private $db;
  protected $connection;

  protected function __construct(){ //__construct() gets called when DB is created
    $this -> username = getenv('dbuser'); //username etc does not need $ since it is a member of database
    $this -> password = getenv('dbpassword');
    $this -> host = getenv('dbhost');
    $this -> db = getenv('dbname');
    $this -> connect();
  }
  
  private function connect(){ //this function creates connection
    $this -> connection = mysqli_connect(
      $this -> host,
      $this -> username,
      $this -> password,
      $this -> db
    );
  }

}

?>