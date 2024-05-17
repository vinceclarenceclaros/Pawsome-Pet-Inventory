<?php
$page_title = 'All Sales';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

// Get sorting parameters from request
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Fetch sales with sorting
$sales = find_all_sale($sort_by, $order);

include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-6">
    <?php 
    if ($msg != null){
      $keys = array_keys($msg);
      $key = $keys[0];
      $status = ($key != "danger");
      echo display_msg($msg, $status);
    }
    ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Sales</span>
        </strong>
        <div class="pull-right">
        <form method="GET" action="sales.php" class="form-inline text-right">
          <div class="form-group">
            <label for="sort_by">Sort by:</label>
            <select name="sort_by" class="form-control">
              <option value="name" <?php if ($sort_by == 'name') echo 'selected'; ?>>Name</option>
              <option value="qty" <?php if ($sort_by == 'qty') echo 'selected'; ?>>Quantity</option>
              <option value="total" <?php if ($sort_by == 'total') echo 'selected'; ?>>Total</option>
              <option value="date" <?php if ($sort_by == 'date') echo 'selected'; ?>>Date</option>
            </select>
          </div>
          <div class="form-group">
            <label for="order">Order:</label>
            <select name="order" class="form-control">
              <option value="ASC" <?php if ($order == 'ASC') echo 'selected'; ?>>Ascending</option>
              <option value="DESC" <?php if ($order == 'DESC') echo 'selected'; ?>>Descending</option>
            </select>
          </div>
          <button type="submit" class="btn btn-info">Sort</button>
          <a href="add_sale.php" class="glypicon btn btn-primary">Add New</a>
        </form>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Product name </th>
              <th class="text-center" style="width: 15%;"> Quantity</th>
              <th class="text-center" style="width: 15%;"> Total </th>
              <th class="text-center" style="width: 15%;"> Date </th>
              <?php if ($user['user_level'] == 1 || $user['user_level'] == 2): ?>
              <th class="text-center" style="width: 100px;"> Actions </th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sales as $sale): ?>
            <tr>
              <td class="text-center"><?php echo count_id(); ?></td>
              <td><?php echo remove_junk($sale['name']); ?></td>
              <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
              <td class="text-center"><?php echo remove_junk($sale['total']); ?></td>
              <td class="text-center"><?php echo $sale['date']; ?></td>
              <?php if ($user['user_level'] == 1): ?>
              <td class="text-center">
                <div class="btn-group">
                  <a href="edit_sale.php?id=<?php echo (int)$sale['id']; ?>" class="btn btn-warning btn-xs" title="Edit" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  <a href="delete_sale.php?id=<?php echo (int)$sale['id']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
                </div>
              </td>
              <?php elseif ($user['user_level'] == 2): ?>
              <td class="text-center">
                <div class="btn-group">
                  <a href="edit_sale.php?id=<?php echo (int)$sale['id']; ?>" class="btn btn-warning btn-xs" title="Edit" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                </div>
              </td>
              <?php endif; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

<?php
// Function to find all sales with dynamic sorting
function find_all_sales($sort_by = 'date', $order = 'DESC') {
  global $db;
  $valid_columns = ['name', 'qty', 'total', 'date'];
  $valid_orders = ['ASC', 'DESC'];

  // Validate sorting parameters
  $sort_by = in_array($sort_by, $valid_columns) ? $sort_by : 'date';
  $order = in_array($order, $valid_orders) ? $order : 'DESC';

  $sql = "SELECT * FROM sales ORDER BY {$sort_by} {$order}";
  return find_by_sql($sql);
}

?>
