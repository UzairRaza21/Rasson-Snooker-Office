<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "conn.php";

$cue_clubname = $_SESSION['clubname'];

$table_id = $_POST['id'];
$edited_table_name = $_POST['table_name'];
$edited_snooker_price = $_POST['edit_snooker'];
$edited_century_price = $_POST['edit_century'];

$sql_update_data = "UPDATE `{$cue_clubname}_table` SET 
                    `table_name`='{$edited_table_name}',
                    `snooker_table_price`='{$edited_snooker_price}',
                    `century_table_price`='{$edited_century_price}'
                    WHERE table_id = {$table_id}";

$result_update_data = mysqli_query($conn, $sql_update_data);

if($result_update_data){
    echo 1;
} else {
    echo 0;
}
?>
