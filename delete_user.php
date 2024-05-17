<?php
  require_once('includes/load.php');
  page_require_level(2);
  $c_user = current_user();

  $useR = find_by_id('users',(int)$_GET['id']);
  
  if(!$useR){
    $session->msg("d","Missing User id.");
    redirect('users.php');
  }

  $delete_id = delete_by_id('users',(int)$_GET['id']);
  $action = 'deleted account';
  $date = make_date();
  $userID = isset($c_user['id']) ? (int)$c_user['id'] : 0;

  if($delete_id){
    $query = "INSERT INTO activity_log (userID, activity, time) VALUES ('{$userID}', '{$action}', '{$date}')";

    if($db->query($query)){
      $session->msg("s","User deleted.");
      redirect('users.php');
    } else {
      $session->msg("d","User deletion failed Or Missing Prm.");
      redirect('users.php');
    }
  }
?>
