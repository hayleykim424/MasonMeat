<?php
//product detail class returns an array of 1 product
//unless the product has multiple images in which it will return an array with multiple members of all having the same product id but different images
  class ProductDetail extends Products{
    private $query;
    public $product = array();
    public function __construct($product_id){
      parent::__construct();
      $this ->  query = 'SELECT
        products.id AS id,
        products.name AS name,
        products.price AS price,
        products.description AS description,
        images.image_file_name AS image 
        FROM products 
        INNER JOIN products_images 
        ON products.id = products_images.product_id
        INNER JOIN images
        ON products_images.image_id = images.image_id
        WHERE products.id = ?'; //at this stage, we don't know the id of product. safer for sql injection
        $this -> getProduct( $product_id ); // we need this product id to replace the question mark.    
      
    }
    
    private function getProduct($product_id){ //replacing the '?'
      $statement = $this -> connection -> prepare( $this -> query );
      $statement -> bind_param('i', $product_id); //i means integer. it prevents SQL injection.
      $statement -> execute();
      $result = $statement -> get_result();
      if( $result -> num_rows > 0 ){
        while ( $row = $result -> fetch_assoc() ){
          array_push( $this -> product, $row );
        }
      }
      return $this -> product;
    }
    
  }

?>