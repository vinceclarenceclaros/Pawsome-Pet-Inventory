<?php
  $page_title = 'Edit Categorie';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $c_user = current_user();
  $act_log = activity_log();
?>
<?php
  //Display all categories.
  $categorie = find_by_id('categories',(int)$_GET['id']);
  if(!$categorie){
    $session->msg("d","Missing categorie id.");
    redirect('categorie.php');
  }
?>

<?php
if(isset($_POST['edit_cat'])){
  $req_field = array('categorie-name');
  validate_fields($req_field);
  
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));
  $activity = 'update category';
  
  // Check if category name is empty
  if(empty($errors)){
    // Check if the category name already exists in the database
    $existing_category = find_by_column('categories', 'name', $cat_name);
    if ($existing_category && $existing_category['id'] != $categorie['id']) {
      $session->msg("d", "Category name already exists.");
      redirect('edit_categorie.php?id=' . (int)$categorie['id']);
    }
    
    // Check if category name contains special characters
    if (!preg_match('/^[a-zA-Z0-9\s]+$/', $cat_name)) {
      $session->msg("d", "Category name should not contain special characters.");
      redirect('edit_categorie.php?id=' . (int)$categorie['id']);
    }
    
    $sql = "UPDATE categories SET name='{$cat_name}'";
    $sql .= " WHERE id='{$categorie['id']}'";
    $result = $db->query($sql);
    
    if($result && $db->affected_rows() === 1) {
      $date = make_date();
      $userID = isset($c_user['id']) ? (int)$c_user['id'] : 0;
    
      // Insert into activity_log for the update
      $query = "INSERT INTO activity_log (userID, activity, time)
                VALUES ('{$userID}', '{$activity}', '{$date}')";
  
      if ($db->query($query)) {
        $session->msg("s", "Successfully updated Category");
        redirect('categorie.php', false);
      } else {
        $session->msg("d", "Sorry! Failed to Update");
        redirect('categorie.php', false);
      }
    } else {
      $session->msg("d", "Sorry! Failed to Update");
      redirect('categorie.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_categorie.php?id=' . (int)$categorie['id']);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php
         if ($msg != null){
          $keys = array_keys($msg);
          $key = $keys[0];
          if ($key == "danger"){
              $status = false;
          }
          else{
              $status = true;
          }
          echo display_msg($msg,$status);
        }
     ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing <?php echo remove_junk(ucfirst($categorie['name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_categorie.php?id=<?php echo (int)$categorie['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="categorie-name" value="<?php echo remove_junk(ucfirst($categorie['name']));?>">
           </div>
           <button type="submit" name="edit_cat" class="btn btn-primary">Update</button>
       </form>
       </div>
     </div>
   </div>
</div>

<?php include_once('layouts/footer.php'); ?>
