<?php
  //modified product class to accept pagination
  class Products extends Database {
  //result array for products
  public $products = array();
  //total of products without pages
  public $products_total = 8;
  //total of products for this page
  public $current_page_total;
  //products per page
  private $page_size = 8;
  //page number
  private $current_page = 1;
  //offset
  private $offset = 0;
  //total number of pages
  private $total_pages;
  //currently selected category
  private $selected_category = 0;
  
  public function __construct() {
    
    parent:: __construct(); //creates DB connection
    
    //check [page] get variable and make sure it is an integer
    $page_var = $_GET["page"];
    if( isset( $page_var ) && filter_var( $page_var , FILTER_VALIDATE_INT )  ){
      $this -> current_page = $_GET["page"];
    }
    
    //check [page_size] and make sure it is an integer otherwise do not set
    //the page_size variable -- use the default value
    if( isset( $_GET["page_size"] ) && filter_var($_GET[""]) ){
      $this -> page_size = $_GET["page_size"];
    }
    //check [category]
    $category_var = base64_decode( $_GET["category"] );
    if( isset( $category_var ) 
    && filter_var( $category_var, FILTER_VALIDATE_INT ) 
    && $category_var > 0 ){
      $this -> selected_category = $category_var;
    }
    
    $this -> offset = ($this -> current_page - 1 ) * $this -> page_size;
  }
  public function getProducts($pagination = false){

    $query = 'SELECT 
    products.id AS id,
    products.name AS name,
    products.price AS price,
    products.description AS description,
    images.image_file_name AS image
    FROM products 
    INNER JOIN products_images 
    ON products.id = products_images.product_id 
    INNER JOIN images
    ON products_images.image_id = images.image_id'; 
    
  
    if( $this -> selected_category !== 0 ){
      $query = $query . 
      " INNER JOIN products_categories 
      ON products_categories.product_id = products.id  
      WHERE products_categories.category_id=? AND products.active = 1";
    }
    else{
      $query = $query . " WHERE products.active = 1";
    }
    //add grouping after category has been applied
    $query = $query . " GROUP BY products.id";
    
    //get the total number of products before pagination is applied
    $this -> products_total = $this -> getProductsTotal( $query );
    
    //if pagination is true append limit and offset to query
    if($pagination == true){
      //$this -> products_total = $this -> getProductsTotal( $query );
      $query = $query . ' ' . 'LIMIT ? OFFSET ?';
    }
    //send the query to the database using the database class connection variable
    $statement = $this -> connection -> prepare($query);
    
    //if there is category selected, pass the parameters
    if( $this -> selected_category !== 0 ){
      $statement -> bind_param("iii", $this -> selected_category, $this -> page_size, $this -> offset );
    }
    //if there is no category selected, pass only page size and offset
    else if($pagination == true){
      $statement -> bind_param('ii', $this -> page_size, $this -> offset );
    }
    
    //execute query
    $statement -> execute();
    //get query results
    $result = $statement -> get_result();
    if( $result -> num_rows > 0 ){
      while( $row = $result -> fetch_assoc() ){
        //add each row to products array
        array_push( $this -> products, $row );
      }
      
      $this -> current_page_total = $result -> num_rows;
      
      if( $json == false ){
        return $this -> products;
      }
      else{
        return json_encode( $this -> products );
      }
    }
    else{
      $this -> current_page_total = 0;
      return false;
    }
    $statement -> close();
  }
  private function getProductsTotal( $query ){
    //execute the query and return the total
    $statement = $this -> connection -> prepare( $query );
    
    //if there is category, pass the category id
    if( $this -> selected_category !== 0 ){
      $statement -> bind_param( "i" , $this -> selected_category );
    }
    
    $statement -> execute();
    $result = $statement -> get_result();
    $rows = $result -> num_rows;
    return $rows;
  }

    
  }


?>