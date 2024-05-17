<?php
$page_title = 'All Categories';
require_once('includes/load.php');
$c_user = current_user();
$all_categories = find_all('categories');

$errors = array();

// Process form submission to add a new category
if (isset($_POST['add_cat'])) {
    // Validation
    $req_field = array('categorie-name');
    validate_fields($req_field);

    // Check for special characters in category name
    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['categorie-name'])) {
        $errors[] = "Special characters are not allowed in category name.";
    }

    $cat_name = remove_junk($db->escape($_POST['categorie-name']));
    $activity = 'add category';
    $date   = make_date();

    // Check if the category name already exists
    $existing_category = find_by_column('categories', 'name', $cat_name);
    if($existing_category) {
        $errors[] = "Category '{$cat_name}' already exists.";
    }

    if(empty($errors)){
        $sql  = "INSERT INTO categories (name)";
        $sql .= " VALUES ('{$cat_name}')";
        $db->query($sql);

        $userID = isset($c_user['id']) ? (int)$c_user['id'] : 0;

        $query = "INSERT INTO activity_log (userID, activity, time)
                  VALUES ('{$userID}', '{$activity}', '{$date}')";
        if($db->query($query)){
            $session->msg("s", "Successfully Added New Category");
            redirect('categorie.php',false);
        } else {
            $session->msg("d", "Sorry Failed to insert.");
            redirect('categorie.php',false);
        }
    } else {
        $session->msg("d", $errors); // Error messages will be displayed as red
        redirect('categorie.php',false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
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
        ?> <!-- Display alert message -->
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-indent-left"></span>
                    <span>All Categories</span>
                </strong>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Categories</th>
                            <?php if ($c_user['user_level'] == 1): ?>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_categories as $cat):?>
                            <tr>
                                <td class="text-center"><?php echo count_id();?></td>
                                <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                                <?php if ($c_user['user_level'] == 1): ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>
                                            <a href="delete_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                                                <span class="glyphicon glyphicon-trash"></span>
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
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-plus"></span>
                    <span>Add New Category</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="categorie.php">
                    <div class="form-group">
                        <input type="text" class="form-control" name="categorie-name" placeholder="Category Name">
                    </div>
                    <button type="submit" name="add_cat" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
