<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
$c_user = current_user();

if(isset($_POST['add_sale'])){
  $req_fields = array('s_id','quantity','price','total', 'date' );
  validate_fields($req_fields);
  if(empty($errors)){
    $p_id      = $db->escape((int)$_POST['s_id']);
    $s_qty     = $db->escape((int)$_POST['quantity']);
    $s_total   = $db->escape($_POST['total']);
    $date      = $db->escape($_POST['date']);
    $activity = 'add product';
    $s_date    = make_date();

    // Check if quantity is greater than zero
    if($s_qty <= 0) {
      $session->msg('d', 'Quantity should be greater than zero.');
      redirect('add_sale.php', false);
    }

    // Check current inventory count
    $product = find_by_id('products', $p_id);
    if($product['quantity'] < $s_qty) {
      $session->msg('d', 'Sale quantity exceeds available inventory.');
      redirect('add_sale.php', false);
    }

    $sql  = "INSERT INTO sales (";
    $sql .= " product_id,qty,price,total,date";
    $sql .= ") VALUES (";
    $sql .= "'{$p_id}','{$s_qty}','{$s_total}','{$s_total}','{$s_date}'";
    $sql .= ")";
    $db->query($sql);

    $userID = isset($c_user['id']) ? (int)$c_user['id'] : 0;

    $query = "INSERT INTO activity_log (userID, activity, time)
              VALUES ('{$userID}', '{$activity}', '{$date}')";

    if($db->query($query)){
      update_product_qty($s_qty,$p_id);
      $session->msg('s',"Sale added. ");
      redirect('add_sale.php', false);
    } else {
      $session->msg('d',' Sorry failed to add!');
      redirect('add_sale.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_sale.php',false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
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
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Find It</button>
            </span>
            <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Search for product name">
        </div>
        <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Sale Edit</span>
      </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
        <table class="table table-bordered">
          <thead>
            <th> Item </th>
            <th> Price </th>
            <th> Qty </th>
            <th> Total </th>
            <th> Date</th>
            <th> Action</th>
          </thead>
          <tbody id="product_info"> </tbody>
        </table>
      </form>
      </div>
    </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>
