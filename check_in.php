<?php
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = intval($_POST['customer_id']);
    $cue_clubname = $_SESSION['clubname'];

    // Prepare SQL query to update check-in time
    $sqli_customer_check_in = "UPDATE `{$cue_clubname}_customer` 
                               SET `customer_check_in_time` = NOW(),
                               `customer_visit_date` = NOW()
                               WHERE `customer_id` = ?";
    
    if ($stmt = mysqli_prepare($conn, $sqli_customer_check_in)) {
        mysqli_stmt_bind_param($stmt, 'i', $customer_id);
        $result_customer_check_in = mysqli_stmt_execute($stmt);
        
        // Fetch the new check-in time
        $sqli_check_in_time = "SELECT `customer_check_in_time` FROM `{$cue_clubname}_customer` WHERE `customer_id` = ?";
        if ($stmt_time = mysqli_prepare($conn, $sqli_check_in_time)) {
            mysqli_stmt_bind_param($stmt_time, 'i', $customer_id);
            mysqli_stmt_execute($stmt_time);
            mysqli_stmt_bind_result($stmt_time, $check_in_time);
            mysqli_stmt_fetch($stmt_time);
            mysqli_stmt_close($stmt_time);
        }

        // Format check-in time to 12-hour format with AM/PM
        $formatted_check_in_time = date('h:i A', strtotime($check_in_time));

        echo json_encode(['success' => $result_customer_check_in, 'check_in_time' => $formatted_check_in_time]);
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>
