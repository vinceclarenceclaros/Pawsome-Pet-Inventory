<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $c_user = current_user();
  $act_log = activity_log();
?>
<?php
  $d_sale = find_by_id('sales',(int)$_GET['id']);
  if(!$d_sale){
    $session->msg("d","Missing sale id.");
    redirect('sales.php');
  }
?>
<?php
  $delete_id = delete_by_id('sales',(int)$d_sale['id']);
  $activity = 'delete sale';
  $date   = make_date();

  if($delete_id){
    $userID = isset($c_user['id']) ? (int)$c_user['id'] : 0;

    $query = "INSERT INTO activity_log (userID, activity, time)
              VALUES ('{$userID}', '{$activity}', '{$date}')";

    if($db->query($query)){
      $session->msg("s","sale deleted.");
      redirect('sales.php');
    } else {
      $session->msg("d","sale deletion failed.");
      redirect('sales.php');
    }
  } else {
      $session->msg("d","sale deletion failed.");
      redirect('sales.php');
  }
?>
