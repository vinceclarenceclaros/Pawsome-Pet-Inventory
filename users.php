<?php
$page_title = 'All user';
require_once('includes/load.php');
page_require_level(1);
$all_users = find_all_user();
$login_trail = login_trail();
$act_log = activity_log();
?>
<?php include_once('layouts/header.php'); ?>
<script src="/libs/js/modal_edit.js"></script>
<script src="/libs/js/modal_delete.js"></script>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-user"></span>
                    <span>Accounts</span>
                </strong>
                <a href="add_user.php" class="btn btn-info pull-right">Add New Employee</a>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th class="text-center">Name </th>
                                <th class="text-center" style="width: 70px;">User ID</th>
                                <th class="text-center">Username</th>
                                <th class="text-center" style="width: 15%;">Role</th>
                                <th class="text-center" style="width: 10%;">Status</th>
                                <th class="text-center" style="width: 25%;">Last Login</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_users as $a_user) : ?>
                                <tr>
                                    <td class="text-center"><?php echo count_id(); ?></td>
                                    <td><?php echo remove_junk(ucwords($a_user['name'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_user['id'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_user['username'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_user['group_name'])) ?></td>
                                    <td class="text-center">
                                        <?php if ($a_user['status'] === '1') : ?>
                                            <span class="label label-success"><?php echo "Active"; ?></span>
                                        <?php else : ?>
                                            <span class="label label-danger"><?php echo "Deactive"; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo read_date($a_user['last_login']) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_user.php?id=<?php echo (int)$a_user['id']; ?>" class="btn btn-warning btn-xs glyphicon glyphicon-edit" title="Edit" data-toggle="tooltip"></a>
                                            <a href="delete_user.php?id=<?php echo (int)$a_user['id']; ?>" class="btn btn-danger btn-xs glyphicon glyphicon-trash" title="Delete" data-toggle="tooltip"></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Trails Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-transfer"></span>
                    <span>Login Trail</span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 70px;">User ID</th>
                                <th class="text-center">Username</th>
                                <th class="text-center" style="width: 25%;">Time</th>
                                <th class="text-center" style="width: 25%;">Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($login_trail as $trail) : ?>
                                <tr>
                                    <td class="text-center"><?php echo remove_junk(ucwords($trail['id'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($trail['name'])) ?></td>
                                    <td><?php echo read_date($trail['login_time']) ?></td>
                                    <td class="text-center"><?php echo remove_junk(ucwords($trail['activity'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Activity Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-list-alt"></span>
                    <span>User Activity</span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 70px;">User ID</th>
                                <th class="text-center">Username</th>
                                <th class="text-center" style="width: 25%;">Time</th>
                                <th class="text-center" style="width: 25%;">Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($act_log as $log) : ?>
                                <tr>
                                    <td class="text-center"><?php echo remove_junk(ucwords($log['userID'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($log['name'])) ?></td>
                                    <td><?php echo read_date($log['time']) ?></td>
                                    <td class="text-center"><?php echo remove_junk(ucwords($log['activity'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
