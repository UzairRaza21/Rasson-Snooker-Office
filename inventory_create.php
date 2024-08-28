<?php
session_start();
include "conn.php";

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventory_name = mysqli_real_escape_string($conn, $_POST['inventory_name']);
    $inventory_price = mysqli_real_escape_string($conn, $_POST['inventory_price']);
    $inventory_quantity = mysqli_real_escape_string($conn, $_POST['inventory_qty']);

    $cue_clubname = $_SESSION['clubname'];

    // Prepare the SQL query
    $sql_inventory_info = "INSERT INTO `{$cue_clubname}_inventory` (`inventory_name`, `inventory_price`, `inventory_qty`) VALUES (?, ?, ?)";
    
    // Initialize a statement and prepare the SQL query
    $stmt = mysqli_prepare($conn, $sql_inventory_info);
    
    if ($stmt) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "sss", $inventory_name, $inventory_price, $inventory_quantity);
        
        // Execute the statement
        mysqli_stmt_execute($stmt);
        
        // Check if the query was successful
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $inventory_id = mysqli_insert_id($conn);
            $response = [
                'success' => true,
                'inventory_id' => $inventory_id,
                'inventory_name' => $inventory_name,
                'inventory_price' => $inventory_price,
                'inventory_qty' => $inventory_quantity
            ];
        } else {
            $response = ['error' => 'Error adding inventory item: ' . mysqli_error($conn)];
        }
        
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $response = ['error' => 'Error preparing query: ' . mysqli_error($conn)];
    }
}

mysqli_close($conn);
echo json_encode($response);
?>
