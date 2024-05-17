<?php
require_once('includes/load.php');
require_once(LIB_PATH_INC.'/database.php');

function generateCreateTableSQL($data,$tableName) {
    $sql = "CREATE TABLE IF NOT EXISTS ".$tableName." (";

    foreach ($data as $field) {
        $fieldName = $field['Field'];
        $fieldType = $field['Type'];
        $fieldNull = $field['Null'];
        $fieldKey = $field['Key'];
        $fieldDefault = $field['Default'];
        $extra = $field['Extra'];

        $sql .= "$fieldName $fieldType";

        if ($fieldNull === 'NO') {
            $sql .= " NOT NULL";
        }

        if ($fieldDefault !== null) {
            if ($fieldDefault == 'CURRENT_TIMESTAMP'){
                $sql .= " DEFAULT $fieldDefault()";
            }
            else{
                $sql .= " DEFAULT '$fieldDefault'";
            }
            
        }

        if ($extra !== '') {
            if ($extra == 'DEFAULT_GENERATED'){
                $sql .= ", PRIMARY KEY (id)";
            }
            else{
                $sql .= " $extra";
            }
            
        }

        $sql .= ",";
    }

    // Remove trailing comma
    $sql = rtrim($sql, ',');

    $sql .= ");";

    return $sql;
}

function generateInsertData($result, $tableName) {
    $full_sql = "";
    if (count($result) > 0) {
        foreach ($result as $column => $row) {
            $column_names = array_filter(array_keys($row), 'is_string');
            $sql_header = implode(",",$column_names);
            $sql_values = "";
            foreach ($column_names as $key => $column_name) {
                $sql_values .= "\"".$row[$column_name]."\",";
            }
            $sql_values = rtrim($sql_values, ',');
            $full_sql .= "INSERT IGNORE INTO $tableName ($sql_header) VALUES ($sql_values);\n";
        }
    }

    return $full_sql;
}


?>