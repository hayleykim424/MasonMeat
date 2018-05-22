<?php
class ProductSearch extends Products{
  public $search_results = array();
  public function __construct(){
    parent::__construct();
    $this -> query = 'SELECT 
    products.id AS id,
    products.name AS name,
    products.price AS price,
    images.image_file_name AS image
    FROM products 
    INNER JOIN products_images 
    ON products.id = products_images.product_id 
    INNER JOIN images
    ON products_images.image_id = images.image_id 
    WHERE products.name LIKE ? 
    OR products.description LIKE ? 
    GROUP BY products.id';
  }
  public function search($keywords){
    //pad keywords with % symbol to match anything containing the keyword(s)
    $search_term = '%' . $keywords . '%';
    $statement = $this -> connection -> prepare( $this -> query );
    $statement -> bind_param( 'ss' , $search_term, $search_term );
    if( $statement -> execute() == false ){
      return false;
    }
    else{
      $result = $statement -> get_result();
      if( $result -> num_rows > 0 ){
        while( $row = $result -> fetch_assoc() ){
          array_push( $this -> search_results , $row);
        }
        return $this -> search_results;
      }
      else{
        return false;
      }
    }
  }
  
}
?>