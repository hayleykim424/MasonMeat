<?php
class Categories extends Database{
  public function __construct(){
    //initialise database class
    parent::__construct();
  }
  public function getCategories($active_only = false){
    $categories = array();
    $query = "SELECT category_id,category_name,active FROM categories";
    if( $active_only === true ){
      $query = $query . " WHERE active=1";
    }
    //no need to use statement here as query is fixed.
    $result = $this -> connection -> query( $query );
    if( $result -> num_rows > 0 ){
      while( $row = $result -> fetch_assoc() ){
        array_push( $categories, $row );
      }
      return $categories;
    }
    else{
      return null;
    }
  }
  
  
  public function getCategoryChildren( $parent_id, $active_only = false ){
    $categories = array();
    $query = "SELECT category_id,category_name FROM categories";
    if( $active_only === true ){
      $query = $query . " WHERE active=1 AND parent_id= ?";
    }
    //statement
    $statement = $this -> connection -> prepare( $query );
    $statement -> bind_param( 'i', $parent_id);
    $statement -> execute();
    $result = $statement -> get_result();

    if( $result -> num_rows > 0 ){
      while( $row = $result -> fetch_assoc() ){
        array_push( $categories, $row );
      }
      return $categories;
    }
    else{
      return null;
    }
  }
  
  
  
  public function create($name,$parent=0,$active=true){
    $query = "INSERT INTO categories 
    (category_name,parent_id,created,active) 
    VALUES
    (?,?,NOW(),?)";
    $active_int = (int)$active;
    $statement = $this -> connection -> prepare( $query );
    $statement -> bind_param('sii',$name,$parent,$active_int);
    if( $statement -> execute() == false ){
      //failed
      return false;
    }
    else{
      return true;
    }
  }
  public function update($id,$name,$parent,$active){
    $query = "UPDATE categories 
    SET category_name=?,parent_id=?,active=? 
    WHERE category_id=?";
    $active_int = (int)$active;
    $statement = $this -> connection -> prepare($query);
    $statement -> bind_param( 'siii', $name, $parent, $active_int, $id );
    if( $statement -> execute() == false ){
      return false;
    }
    else{
      return true;
    }
  }
  
  public function remove($id){
    //because of foreign keys, before removing from 'categories' table, we need to delete references to the category from 'products_categories'
    //delete from products categories
    $errors = array();
    $query = "DELETE FROM products_categories 
    WHERE category_id=?";
    $statement = $this -> connection -> prepare($query);
    $statement -> bind_param( 'i', $id );
    if( $statement -> execute() == false ){
      $errors["products_categories"] = 1;
    }
    //delete from categories
    $query = "DELETE FROM categories 
    WHERE category_id=?";
    $statement = $this -> connection -> prepare($query);
    $statement -> bind_param( 'i',  $id );
    if( $statement -> execute() == false ){
      $errors["categories"] = 1;
    }
    if( count($errors) > 0){
      return false;
    }
    else{
      return true;
    }
  }
}
?>