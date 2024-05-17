<?php
$page_title = 'Edit User';
require_once('includes/load.php');
page_require_level(1);
$c_user = current_user();

$e_user = find_by_id('users',(int)$_GET['id']);
$groups  = find_all('user_groups');

if(!$e_user){
  $session->msg("d","Missing user id.");
  redirect('users.php');
}

if(isset($_POST['update'])) {
  $req_fields = array('name','username','level','status');
  validate_fields($req_fields);

  if(empty($errors)){
    $id = (int)$e_user['id'];
    $name = remove_junk($db->escape($_POST['name']));
    $username = remove_junk($db->escape($_POST['username']));
    $level = (int)$db->escape($_POST['level']);
    $status = remove_junk($db->escape($_POST['status']));
    $activity = 'update account';
    $date = make_date();

    $sql = "UPDATE users SET name ='{$name}', username ='{$username}', user_level='{$level}', status='{$status}' WHERE id='{$db->escape($id)}'";

    if ($result = $db->query($sql)) {
      $query = "INSERT INTO activity_log (userID, activity, time) VALUES ('{$c_user['id']}', '{$activity}', '{$date}')";

      if ($db->query($query)) {
        $session->msg('s',"Account Updated");
        redirect('edit_user.php?id='.(int)$e_user['id'], false);
      } else {
        $session->msg('d','Sorry failed to update!');
        redirect('edit_user.php?id='.(int)$e_user['id'], false);
      }
    } else {
      $session->msg('d','Sorry failed to update!');
      redirect('edit_user.php?id='.(int)$e_user['id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_user.php?id='.(int)$e_user['id'],false);
  }
}

if(isset($_POST['update-pass'])) {
  $req_fields = array('password','confirm-password'); // Add confirm-password to required fields
  validate_fields($req_fields);

  if(empty($errors)){
    $id = (int)$e_user['id'];
    $password = remove_junk($db->escape($_POST['password']));
    $confirm_password = remove_junk($db->escape($_POST['confirm-password']));

    if ($password !== $confirm_password) {
      $session->msg('d',"Password and Confirm Password do not match.");
      redirect('edit_user.php?id='.(int)$e_user['id']);
    }

    // Define password parameters
    $min_password_length = 8; // Minimum password length

    // Password strength validation
    if(strlen($password) < $min_password_length || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[!@#$%^&*()\-_=+{};:,<.>ยง~]/', $password)) {
      $session->msg('d', 'New password must be at least '.$min_password_length.' characters long and include uppercase letters, lowercase letters, numbers, and special characters.');
      redirect('edit_user.php?id='.(int)$e_user['id'], false);
    }

    $h_pass = sha1($password);

    $sql = "UPDATE users SET password='{$h_pass}' WHERE id='{$db->escape($id)}'";

    if ($result = $db->query($sql)) {
      $session->msg('s',"User password has been updated");
      redirect('edit_user.php?id='.(int)$e_user['id'], false);
    } else {
      $session->msg('d','Sorry failed to update user password!');
      redirect('edit_user.php?id='.(int)$e_user['id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_user.php?id='.(int)$e_user['id'],false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12"><?php 
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
  ?></div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-th"></span> Update <?php echo remove_junk(ucwords($e_user['name'])); ?> Account</strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" class="clearfix">
          <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input type="name" class="form-control" name="name" value="<?php echo remove_junk(ucwords($e_user['name'])); ?>">
          </div>
          <div class="form-group">
            <label for="username" class="control-label">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo remove_junk(ucwords($e_user['username'])); ?>">
          </div>
          <div class="form-group">
            <label for="level">User Role</label>
            <select class="form-control" name="level">
              <?php foreach ($groups as $group ):?>
              <option <?php if($group['group_level'] === $e_user['user_level']) echo 'selected="selected"';?> value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status">
              <option <?php if($e_user['status'] === '1') echo 'selected="selected"';?>value="1">Active</option>
              <option <?php if($e_user['status'] === '0') echo 'selected="selected"';?> value="0">Deactive</option>
            </select>
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="update" class="btn btn-info">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-th"></span> Change <?php echo remove_junk(ucwords($e_user['name'])); ?> password</strong>
      </div>
      <div class="panel-body">
        <form action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" method="post" class="clearfix">
          <div class="form-group">
            <label for="password" class="control-label">New Password</label>
            <input type="password" class="form-control" name="password" placeholder="Type new password">
          </div>
          <div class="form-group">
            <label for="confirm-password" class="control-label">Confirm New Password</label>
            <input type="password" class="form-control" name="confirm-password" placeholder="Retype new password">
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="update-pass" class="btn btn-danger pull-right">Change</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
