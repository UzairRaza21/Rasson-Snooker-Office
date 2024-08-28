<?php
session_start();
include "conn.php";

// Check if it's a GET request to fetch data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cue_clubname = $_SESSION['clubname'];
    
    // Prepare the SQL query
    $sql_inventory_info = "SELECT * FROM `{$cue_clubname}_inventory`";

    $result = mysqli_query($conn, $sql_inventory_info);

    if ($result) {
        // Fetch all rows as an associative array
        $inventory = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // Return inventory data as JSON
        echo json_encode($inventory);
    } else {
        // Return error message as JSON
        echo json_encode(["error" => "Error fetching inventory: " . mysqli_error($conn)]);
    }
    
    // Close the database connection
    mysqli_close($conn);
}
?>
