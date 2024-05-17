<?php
$page_title = 'Change Password';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(3);
?>
<?php $user = current_user(); ?>
<?php
if (isset($_POST['update'])) {
    $req_fields = array('new-password', 'confirm-password', 'old-password', 'id');
    validate_fields($req_fields);

    if (empty($errors)) {
        $old_password = sha1($_POST['old-password']);
        if ($old_password !== current_user()['password']) {
            $session->msg('d', "Your old password does not match.");
            redirect('change_password.php', false);
        }

        // Define password parameters
        $min_password_length = 8; // Minimum password length

        $new_password = remove_junk($db->escape($_POST['new-password']));
        $confirm_password = remove_junk($db->escape($_POST['confirm-password']));

        // Password strength validation
        if(strlen($new_password) < $min_password_length || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[a-z]/', $new_password) || !preg_match('/[0-9]/', $new_password) || !preg_match('/[!@#$%^&*()\-_=+{};:,<.>ยง~]/', $new_password)) {
            $session->msg('d', 'New password must be at least '.$min_password_length.' characters long and include uppercase letters, lowercase letters, numbers, and special characters.');
            redirect('change_password.php', false);
        }

        if ($new_password !== $confirm_password) {
            $session->msg('d', "New password and confirm password do not match.");
            redirect('change_password.php', false);
        }

        $id = (int)$_POST['id'];
        $sql = "UPDATE users SET password ='{$new_password}' WHERE id='{$db->escape($id)}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            $session->logout();
            $session->msg('s', "Password changed successfully. Login with your new password.");
            redirect('index.php', false);
        } else {
            $session->msg('d', 'Sorry, failed to update.');
            redirect('change_password.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('change_password.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Change your password</h3>
    </div>
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
    <form method="post" action="change_password.php" class="clearfix">
        <div class="form-group">
            <label for="newPassword" class="control-label">New password</label>
            <input type="password" class="form-control" name="new-password" placeholder="New password">
        </div>
        <div class="form-group">
            <label for="confirmPassword" class="control-label">Confirm new password</label>
            <input type="password" class="form-control" name="confirm-password" placeholder="Confirm new password">
        </div>
        <div class="form-group">
            <label for="oldPassword" class="control-label">Old password</label>
            <input type="password" class="form-control" name="old-password" placeholder="Old password">
        </div>
        <div class="form-group clearfix">
            <input type="hidden" name="id" value="<?php echo (int)$user['id']; ?>">
            <button type="submit" name="update" class="btn btn-info">Change</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>
