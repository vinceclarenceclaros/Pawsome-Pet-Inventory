<?php
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="backup.sql"');

include('get_all_data.php');

$sql = "SHOW TABLES";
$result = find_by_sql($sql);

foreach ($result as $key => $value) {
   foreach ($value as $row => $item) {
        if($row == 0){
            get_recovery($item);
        }
   }
}

function get_recovery($tableName) {

    $sql = "DESCRIBE $tableName";
    $result = find_by_sql($sql);

    $sql = generateCreateTableSQL($result,$tableName);
    echo $sql;

    echo "\n";

    $sql = "SELECT * FROM $tableName";
    $result = find_by_sql($sql);

    $sql=generateInsertData($result,$tableName);
    echo $sql;
    }



?>