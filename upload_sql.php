<?php
require_once('includes/load.php');
require_once(LIB_PATH_INC.'/database.php');

$uploadedFile = $_FILES['sqlFile'];

$msg = execMulti($uploadedFile);
echo $msg

?>