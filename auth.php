<?php include_once('includes/load.php'); ?>
<?php
$req_fields = array('username','password' );
validate_fields($req_fields);
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if(empty($errors)){
    $user = authenticate($username, $password);
    if($user){
      if ($user['status'] == 0) {
        $session->msg("d", "Sorry, your account is disabled. Please contact the administrator.");
        redirect('index.php',false);
      } else {
        //create session with id
        $session->login($user['id']);
  
        updateLastLogIn($user['id']);
        $session->msg("s", "Welcome to Pawesome Pet Food Store");
        redirect('home.php',false);
      }
    } else {
      $session->msg("d", "Sorry Username/Password incorrect.");
      redirect('index.php',false);
    }
  }

?>
