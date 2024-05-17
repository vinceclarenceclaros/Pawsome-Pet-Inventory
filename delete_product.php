<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $c_user = current_user();
  $act_log = activity_log();
?>
<?php
  $product = find_by_id('products',(int)$_GET['id']);
  if(!$product){
    $session->msg("d","Missing Product id.");
    redirect('product.php');
  }
?>
<?php
  $delete_id = delete_by_id('products',(int)$product['id']);
  $activity = 'delete product';
  $date   = make_date();
  
  if($delete_id){
    $userID = isset($c_user['id']) ? (int)$c_user['id'] : 0;

    $query = "INSERT INTO activity_log (userID, activity, time)
              VALUES ('{$userID}', '{$activity}', '{$date}')";

    if($db->query($query)){
      $session->msg("s","Products deleted.");
      redirect('product.php');
    } else {
      $session->msg("d","Products deletion failed.");
      redirect('product.php');
    }
  } else {
    $session->msg("d","Products deletion failed.");
    redirect('product.php');
  }
?>
