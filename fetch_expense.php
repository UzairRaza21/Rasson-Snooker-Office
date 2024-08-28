<?php
session_start();
include "conn.php";

// Check if it's a GET request to fetch data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ensure the session variable is set
    if (!isset($_SESSION['clubname'])) {
        echo json_encode(["error" => "Session expired or club name not set."]);
        exit;
    }

    $cue_clubname = $_SESSION['clubname'];

    // Prepare the SQL query
    $sql_expense_info = "SELECT * FROM `{$cue_clubname}_expense`";

    $result = mysqli_query($conn, $sql_expense_info);

    if ($result) {
        // Fetch all rows as an associative array
        $expenses = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // Return expense data as JSON
        echo json_encode($expenses);
    } else {
        // Return error message as JSON
        echo json_encode(["error" => "Error fetching expenses: " . mysqli_error($conn)]);
    }
    
    // Close the database connection
    mysqli_close($conn);
}
?>
