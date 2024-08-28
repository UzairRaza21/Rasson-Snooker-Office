<?php
session_start();
include "conn.php";
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = intval($_POST['customer_id']);
    $cue_clubname = $_SESSION['clubname'];

    // SQL query to update check-out time
    $sqli_customer_check_out = "UPDATE `{$cue_clubname}_customer` 
                                SET `customer_check_out_time` = NOW() 
                                WHERE `customer_id` = ?";
    
    if ($stmt = mysqli_prepare($conn, $sqli_customer_check_out)) {
        mysqli_stmt_bind_param($stmt, 'i', $customer_id);
        $result_customer_check_out = mysqli_stmt_execute($stmt);
        
        // Check if the update was successful
        if ($result_customer_check_out) {
            // SQL query to fetch check-in time, check-out time, and customer price
            $sqli_check_times = "SELECT `customer_check_in_time`, `customer_check_out_time`, `customer_price` 
                                 FROM `{$cue_clubname}_customer` 
                                 WHERE `customer_id` = ?";
            
            if ($stmt_time = mysqli_prepare($conn, $sqli_check_times)) {
                mysqli_stmt_bind_param($stmt_time, 'i', $customer_id);
                mysqli_stmt_execute($stmt_time);
                mysqli_stmt_bind_result($stmt_time, $check_in_time, $check_out_time, $customer_price);
                mysqli_stmt_fetch($stmt_time);
                mysqli_stmt_close($stmt_time);

                // Calculate elapsed time in seconds
                $check_in_time_unix = strtotime($check_in_time);
                $check_out_time_unix = strtotime($check_out_time);
                $elapsed_time_seconds = $check_out_time_unix - $check_in_time_unix;

                // Calculate elapsed time in minutes for price calculation
                $elapsed_minutes = round($elapsed_time_seconds / 60, 2); // rounded to 2 decimal places

                // Calculate total price
                $total_price = round($elapsed_minutes * $customer_price, 2); // rounded to 2 decimal places

                // Convert elapsed time to hour:mins:sec format
                $hours = floor($elapsed_time_seconds / 3600);
                $minutes = floor(($elapsed_time_seconds % 3600) / 60);
                $seconds = $elapsed_time_seconds % 60;
                $formatted_elapsed_time = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

                // Format check-in and check-out times to 12-hour format with AM/PM
                $formatted_check_in_time = date('h:i A', $check_in_time_unix);
                $formatted_check_out_time = date('h:i A', $check_out_time_unix);

                // SQL query to update stopwatch_elapsed_time and total_price
                $sql_total_price_time = "UPDATE `{$cue_clubname}_customer` 
                                         SET `stopwatch_elapsed_time` = '{$formatted_elapsed_time}', 
                                             `total_price` = {$total_price}
                                         WHERE `customer_id` = ?";
                
                if ($stmt_update = mysqli_prepare($conn, $sql_total_price_time)) {
                    mysqli_stmt_bind_param($stmt_update, 'i', $customer_id);
                    $result_total_price_time = mysqli_stmt_execute($stmt_update);
                    mysqli_stmt_close($stmt_update);

                    // Check if the update was successful
                    if ($result_total_price_time) {
                        echo json_encode([
                            'success' => true,
                            'check_out_time' => $formatted_check_out_time,
                            'elapsed_time' => $formatted_elapsed_time, // formatted time
                            'total_price' => $total_price
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Failed to update total price and elapsed time.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to prepare statement for updating elapsed time and total price.']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to fetch check-in/check-out times or customer price.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update check-out time.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare statement for check-out time update.']);
    }

    mysqli_close($conn);
}
?>
