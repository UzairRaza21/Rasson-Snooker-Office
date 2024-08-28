<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "conn.php";

// Get the club name from the session
$cue_clubname = $_SESSION['clubname'];

$table_id = $_POST['table_id'];

$sql_delete = "DELETE FROM `{$cue_clubname}_table` WHERE table_id = {$table_id}";
$result_delete = mysqli_query($conn, $sql_delete) or die ("SQL QUERY FAILED");

if ($result_delete){
    echo 1;
}else{
    echo 0;
}
?>