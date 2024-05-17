<?php
  $page_title = 'Home Page';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
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
  <div class="col-md-12">
    <div class="panel">
      <div class="jumbotron text-center">
        <h1>Welcome! <hr> Pawesome Pet Supplies Store</h1>
        <p>Unleashing happiness, one wag at a time!</p>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
