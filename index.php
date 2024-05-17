<?php
require_once('includes/load.php');
if ($session->isUserLoggedIn(true)) {
    redirect('home.php', false);
}
?>
<?php include_once('layouts/header.php'); ?>
<style>
    body {
        background-image: url('libs/images/LOGIN.png');
        background-size: cover;
    }

    .login-button {
        background-color: skyblue;
        display: block;
        margin: 0 auto;
        border-radius: 0%;
        transition: background-color 0.3s;
    }

    .login-button:hover {
        background-color: skyblue;
    }
</style>
<div class="login-page">
    <div class="text-center">
        <h1>Pawesome Pet Food Store</h1>
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
    <form method="post" action="auth.php" class="clearfix">
        <div class="form-group">
            <label for="username" class="control-label">Username</label>
            <input type="name" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-danger login-button">Login</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>
