<?php
// if (!isset($_SESSION)){
//     echo 'Forbidden!<script>
//     window.location = "../../index.php";
//     </script>';
// }
// <!-- This file controls all database calls. Everything must go through here -->
$db_protected = false;
function db_test(){
    allow_db_connection();
    $dbc = db_connect();
    if ($dbc){
        echo "Success.";
    } else {
        // echo mysqli_error($dbc);
        echo "Failed.";
    }
    db_disconnect($dbc);

}
function db_call($type, $data1, $data2, $data3 = "", $data4 = ""){
    $query = '';
    switch($type){
        case "insert":
            $query = insert($data1, $data2, $data3);
        break;
        case "select":
            $query = select($data1, $data2, $data3);
        break;
        case "update":
            $query = update($data1, $data2, $data3, $data4);
        break;
        case "delete":
            $query = delete($data1, $data2);
        break;
    }

    if ($query != ''){
        allow_db_connection();
        $dbc = db_connect();
        $result = mysqli_query($dbc, $query);
        if (mysqli_error($dbc)){
            $result = mysqli_error($dbc);
        }
        db_disconnect($dbc);
        return $result;
    }
}
function allow_db_connection(){
    $GLOBALS['db_protected'] = true;
}
function db_connect(){
    // open and return database connection
    if(!$GLOBALS['db_protected']){
        return false;
    }
    $dbc = mysqli_connect('localhost', 'mystozjg_bot', 'n^n}G!Vx}(1K', 'mystozjg_mystocks_db');
    if ($dbc -> connect_errno) {
        $error =  $mysqli -> connect_error;
        return false;
      }
      else{
        return $dbc;
      }
}

function db_disconnect($connection){
    // close database connection
    mysqli_close($connection);
    $GLOBALS['db_protected'] = false;
}

function insert($table, $variables, $values){
    // Insert data into database table
    if(count(explode(", ", $variables)) != count(explode(" [~] ", $values))){
        echo "ERR: Variables and values do not match.</br>";
        return false;
    }else if(count(explode(",", $variables)) < 1 || count(explode(",", $values)) < 1){
        echo "ERR: Variables and values cannot be empty.</br>";
        return false;
    }else if(!isset($table) || $table == ''){
        echo "ERR: No table specified for insert.</br>";
        return false;
    }
    $variables = explode(", ", $variables);
    $variables = join("`, `", $variables);
    $variables = "`$variables`";

    $values = explode(" [~] ", $values);
    $values = join("', '", $values);
    $values = "'$values'";

    return "INSERT INTO `$table` ($variables) VALUES ($values)";
}
function select($data, $table, $conditions){
    // select data into database table
    if( isset($conditions) && $conditions != ''){
        $conditions = "WHERE $conditions";
    }else if(!isset($data) || $data == ''){
        echo "ERR: No data specified to be sele.</br>";
        return false;
    }
    else if(!isset($table) || $table == ''){
        echo "ERR: No table specified for insert.</br>";
        return false;
    }
    return "SELECT $data FROM `$table` $conditions";
}

function update($table, $variables, $values, $conditions){
    // update data in database
    if(count(explode(",", $variables)) != count(explode(" [~] ", $values))){
        echo "ERR: Variables and values do not match.</br>";
        return false;
    }elseif(!isset($table) || $table == ''){
        echo "ERR: No table specified for update.</br>";
        return false;
    }else if( !isset($conditions) || $conditions == ''){
        echo "ERR: No condition set for update.</br>";
        return false;
    }
    $updates = "";
    $variables = explode(", ", $variables);
    $values = explode(" [~] ", $values);
    $timestamp = date('Y-m-d H:i:s');
    for ($i=0, $len = count($variables); $i < $len; $i++){
        if($i != 0){
            $updates .= ", ";
        }
        $variable = $variables[$i];
        $value = $values[$i];
        $updates .= "`$variable` = '$value'" ;
    }
    $updates .= ", `last_modified` = '$timestamp'";
    return "UPDATE $table SET $updates WHERE $conditions";
}

function delete($table, $conditions){
    // delete record from the table 
    if(!isset($table) || $table == ''){
        echo "ERR: No table specified for delete.</br>";
        return false;
    }else if(!isset($conditions) || $conditions == ''){
        echo "ERR: No condition set for delete.</br>";
        return false;
    }
    return "DELETE FROM $table WHERE $conditions";
}

?>