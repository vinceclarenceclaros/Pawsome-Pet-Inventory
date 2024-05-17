<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $c_user = current_user();
  $act_log = activity_log();
?>
<?php
  $categorie = find_by_id('categories',(int)$_GET['id']);
  if(!$categorie){
    $session->msg("d","Missing Categorie id.");
    redirect('categorie.php');
  }
?>
<?php
  $delete_id = delete_by_id('categories',(int)$categorie['id']);
  $activity = 'delete category';
  $date   = make_date();

  if($delete_id){
    $userID = isset($c_user['id']) ? (int)$c_user['id'] : 0;

    $query = "INSERT INTO activity_log (userID, activity, time)
              VALUES ('{$userID}', '{$activity}', '{$date}')";

    if($db->query($query)){
      $session->msg("s","Categorie deleted.");
      redirect('categorie.php');
    } else {
      $session->msg("d","Categorie deletion failed.");
      redirect('categorie.php');
    }
      
  } else {
      $session->msg("d","Categorie deletion failed.");
      redirect('categorie.php');
  }
?>
