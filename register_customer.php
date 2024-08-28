<?php
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $customer_id = intval($_POST['customer_id']);
    $customer_username = mysqli_real_escape_string($conn, $_POST['customer_username']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_mobile_no']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    
    if (!isset($_SESSION['clubname'])) {
        echo json_encode(['success' => false, 'error' => 'Club name is not set in session.']);
        exit();
    }
    
    $cue_clubname = $_SESSION['clubname'];

    // Prepare SQL query to update customer info
    $sql_customer_info = "UPDATE `{$cue_clubname}_customer` 
                          SET `customer_username` = ?, 
                              `customer_mobile_no` = ?, 
                              `customer_email` = ?
                          WHERE `customer_id` = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql_customer_info)) {
        // Bind parameters: s = string, i = integer
        mysqli_stmt_bind_param($stmt, 'sssi', $customer_username, $customer_phone, $customer_email, $customer_id);
        $result_customer_info = mysqli_stmt_execute($stmt);

        if ($result_customer_info) {
            // Return the response
            echo json_encode([
                'success' => true,
                'customer_username' => $customer_username,
                'customer_phone' => $customer_phone,
                'customer_email' => $customer_email
            ]);
        } else {
            // Log and return error if execution fails
            error_log("Failed to execute SQL statement for updating customer info: " . mysqli_stmt_error($stmt));
            echo json_encode(['success' => false, 'error' => 'Failed to execute SQL statement for updating customer info.']);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        // Log error for failed SQL preparation
        error_log("Failed to prepare the SQL statement for updating customer info: " . mysqli_error($conn));
        echo json_encode(['success' => false, 'error' => 'Failed to prepare the SQL statement for updating customer info.']);
    }

    mysqli_close($conn);
}
?>
