<?php
$page_title = 'All Product';
require_once('includes/load.php');
page_require_level(2);
$products = join_product_table();

// Filter products based on search query and selected filter
if (isset($_GET['search']) && !empty($_GET['search']) && isset($_GET['filter'])) {
    $search = $_GET['search'];
    $filter = $_GET['filter'];
    $products = filter_products_by_search($products, $search, $filter);
}

function filter_products_by_search($products, $search, $filter) {
    $filtered_products = array();
    foreach ($products as $product) {
        // Check if the search query matches the selected filter attribute
        if (strpos(strtolower($product[$filter]), strtolower($search)) !== false) {
            $filtered_products[] = $product;
        }
    }
    return $filtered_products;
}

// Function to check if user can delete product
function can_delete_product($user_level) {
    return $user_level != 2;
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
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-shopping-cart"></span>
                    <span>Products</span>
                </strong>
                <div class="pull-right">
                    <form class="navbar-form navbar-left" role="search" action="" method="GET">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search" name="search">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="filter">
                                <option value="name">Product Name</option>
                                <option value="categorie">Category</option>
                                <option value="buy_price">Buying Price</option>
                                <option value="sale_price">Selling Price</option>
                                <option value="date">Date Added</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                        <a href="add_product.php" class="glypicon glyphicon-plus btn btn-primary">Add New</a>
                    </form>
                    
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th class="text-center" style="width: 10%"> Photo</th>
                        <th class="text-center"> Product Title </th>
                        <th class="text-center" style="width: 10%;"> Categories </th>
                        <th class="text-center" style="width: 10%;"> In-Stock </th>
                        <th class="text-center" style="width: 10%;"> Buying Price </th>
                        <th class="text-center" style="width: 10%;"> Selling Price </th>
                        <th class="text-center" style="width: 20%;"> Product Added </th>
                        <?php if ($user['user_level'] == 1): ?>
                        <th class="text-center" style="width: 100px;"> Actions </th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $product):?>
                        <tr>
                            <td class="text-center"><?php echo count_id();?></td>
                            <td>
                                <?php if($product['media_id'] === '0'): ?>
                                    <img class="img-avatar img-square" src="uploads/products/no_image.png" alt="">
                                <?php else: ?>
                                    <img class="img-avatar img-square" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                                <?php endif; ?>
                            </td>
                            <td> <?php echo remove_junk($product['name']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                            <td class="text-center">
                                <?php
                                $quantity = remove_junk($product['quantity']);
                                if ($quantity <= 0) {
                                    echo '<span class="btn btn-danger btn-xs">Out of Stock</span>';
                                } elseif ($quantity <= 20) {
                                    echo '<span class="btn btn-warning btn-xs">Low in Stock</span>';
                                } else {
                                    echo $quantity;
                                }
                                ?>
                            </td>
                            <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                            <td> <?php echo read_date($product['date']); ?></td>
                            <?php if ($user['user_level'] == 1): ?>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-warning btn-xs"  title="Edit" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                    <?php if (can_delete_product($user['user_level'])): ?>
                                        <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                    <?php endif; ?>
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
