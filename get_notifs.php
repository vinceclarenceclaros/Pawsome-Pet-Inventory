<?php
require_once(LIB_PATH_INC.'/database.php');

function get_query($sql){
    global $db;
    $result = $db->query($sql);
    $result_set = $db->while_loop($result);
    return $result_set;  
  }

function low_quantity_products(){
    global $db;
    $sql  =" SELECT p.id,p.name,p.quantity,p.buy_price,p.sale_price,p.media_id,p.date,c.name";
    $sql  .=" AS categorie,m.file_name AS image";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.category_id";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" WHERE p.quantity <= 20"; // Adding the condition for quantity
    $sql  .=" ORDER BY p.id ASC";
    $test_response = get_query($sql);
    return($test_response);
  }
?>