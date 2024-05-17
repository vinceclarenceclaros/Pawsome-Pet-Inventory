<?php
$page_title = 'Add Product';
require_once('includes/load.php');
page_require_level(2);

$c_user = current_user();
$all_categories = find_all('categories');
$all_photo = find_all('media');

if (isset($_POST['add_product'])) {
    $required_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price');
    validate_fields($required_fields);

    if (empty($errors)) {
        try {
            $p_name = remove_junk($db->escape($_POST['product-title']));
            $p_cat = remove_junk($db->escape($_POST['product-categorie']));
            $p_qty = remove_junk($db->escape($_POST['product-quantity']));
            $p_buy = remove_junk($db->escape($_POST['buying-price']));
            $p_sale = remove_junk($db->escape($_POST['saleing-price']));
            $activity = 'add product';

            $media_id = isset($_POST['product-photo']) && !empty($_POST['product-photo']) ? remove_junk($db->escape($_POST['product-photo'])) : '0';

            $date = make_date();

            // Check if product already exists
            $existing_product = find_by_sql("SELECT * FROM products WHERE name='{$p_name}'");

            if ($existing_product) {
                $session->msg('d', "Product already exists!");
                redirect('add_product.php', false);
            } else {
                $query = "INSERT INTO products (name, quantity, buy_price, sale_price, category_id, media_id, date)
                          VALUES ('{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}')
                          ON DUPLICATE KEY UPDATE name='{$p_name}'";
                $db->query($query);

                $userID = isset($c_user['id']) ? (int) $c_user['id'] : 0;

                $query = "INSERT INTO activity_log (userID, activity, time)
                          VALUES ('{$userID}', '{$activity}', '{$date}')";

                if ($db->query($query)) {
                  $session->msg('s', "Product added ", true); // Success message with true for success
                    redirect('add_product.php', false);
                } else {
                    $session->msg('d', ' Sorry failed to add!');
                    redirect('product.php', false);
                }
            }
        } catch (Exception $e) {
            $session->msg('d', $e->getMessage());
            redirect('add_product.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_product.php', false);
    }
}
?>



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
</div>

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-plus"></span>
          <span>Add New Product</span>
        </strong>
      </div>
    <div class="panel-body">
      <div class="col-md-12">
        <form method="post" action="add_product.php" class="clearfix">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon">
              <i class="glyphicon glyphicon-font"></i>
              </span>
              <input type="text" class="form-control" name="product-title" placeholder="Product Title">
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                  </span>
                  <select class="form-control" name="product-categorie">
                    <option value="">Select Product Category</option>
                      <?php  foreach ($all_categories as $cat): ?>
                    <option value="<?php echo (int)$cat['id'] ?>">
                      <?php echo $cat['name'] ?></option>
                      <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-picture"></i>
                  </span>
                    <select class="form-control" name="product-photo">
                      <option value="">Select Product Photo</option>
                        <?php  foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-rub"></i>
                    </span>
                    <input type="number" class="form-control" name="buying-price" placeholder="Buying Price">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-rub"></i>
                      </span>
                      <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price">
                      <span class="input-group-addon">.00</span>
                    </div>
                  </div>
                </div>
              </div>
              <button type="submit" name="add_product" class="btn btn-info">Add product</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
