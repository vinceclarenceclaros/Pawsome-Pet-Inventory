<?php 
require_once('get_notifs.php');
$user = current_user();
// Asia/Manila
date_default_timezone_set("Asia/Manila");
$low_quantity_products=low_quantity_products();
?>
<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "Inventory Management System";?>
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
  <?php  if ($session->isUserLoggedIn(true)): ?>
    <header id="header">
      <div class="logo pull-left"> Pawesome Pet Food Store</div>
      <div class="header-content">
      <div class="header-date pull-left">
        <strong><?php echo date("F j, Y, g:i a");?></strong>
      </div>
      <div class="pull-right clearfix">
        <ul class="info-menu list-inline list-unstyled">
        <li class="notification-icon">
            <a href="#" data-toggle="dropdown" id="notification-list" class="toggle" aria-expanded="false">
              <i class="glyphicon glyphicon-bell"></i> 
              
              <?php
                if (!empty($low_quantity_products)) {
                    echo "<span class='notification-dot'></span>";
                }
              ?>
            </a>
            <ul class="dropdown-menu notification-list">
              <?php foreach ($low_quantity_products as $product):?>
                
                <li>
                    <a href='product.php' style='word-wrap: break-word;'>
                    
                    <?php
                  $quantity = remove_junk($product['quantity']);
                  if ($quantity <= 0) {
                    echo "<i class='glyphicon glyphicon-exclamation-sign danger'></i> ".remove_junk($product['name'])." <br> is out of stock";
                  } elseif ($quantity <= 20) {
                    echo "<i class='glyphicon glyphicon-exclamation-sign warning'></i> ".remove_junk($product['name'])." <br> is low on stock"; 
                  }
                  ?>
                  </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </li>
          <li class="profile">
            <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
              <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
              <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                  <a href="profile.php?id=<?php echo (int)$user['id'];?>">
                      <i class="glyphicon glyphicon-user"></i>
                      Profile
                  </a>
              </li>
             <li>
                 <a href="edit_account.php" title="edit account">
                     <i class="glyphicon glyphicon-cog"></i>
                     Settings
                 </a>
             </li>
             <li class="last">
                 <a href="logout.php">
                     <i class="glyphicon glyphicon-off"></i>
                     Logout
                 </a>
             </li>
           </ul>
          </li>
        </ul>
      </div>
     </div>
    </header>
    <div class="sidebar">
      <?php if($user['user_level'] === '1'): ?>
        <!-- admin menu -->
      <?php include_once('admin_menu.php');?>

      <?php elseif($user['user_level'] === '2'): ?>
        <!-- Special user -->
      <?php include_once('special_menu.php');?>

      <?php elseif($user['user_level'] === '3'): ?>
        <!-- User menu -->
      <?php include_once('user_menu.php');?>

      <?php endif;?>

   </div>
<?php endif;?>

<div class="page">
  <div class="container-fluid">
