<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "conn.php";

// Get the club name from the session
$cue_clubname = $_SESSION['clubname'];

// Get the table_id from the POST request
$table_id = $_POST['table_id'];

$table_id = mysqli_real_escape_string($conn, $table_id); // Sanitize input

// Query to fetch customer data based on the table_id
$sql_fetch_customer_table = "SELECT * FROM {$cue_clubname}_customer WHERE `customer_table_id` = '$table_id'";
$result = mysqli_query($conn, $sql_fetch_customer_table);
$output = "";

if (mysqli_num_rows($result) > 0) {
    $output = "<table>
    <thead >
    <tr>
    <td>#</td>
    <td>Table Name</td>
    <td>Customer Name</td>
    <td>Check In</td>
    <td>Check Out</td>
    <td>Mins</td>
    <td>Rate</td>
    <td>Game Total</td>
    <td>Canteen Total</td>
    <td>Grand Total</td>
    <td>Balance</td>
    </tr>
    </thead>
    <tbody class='table-row-flex'>
    ";

    
    // Loop through the result set and display each row
    $i = 1; // Counter for row number
    while ($row = mysqli_fetch_assoc($result)) {
        $grand_total = $row['customer_canteen_bill'] + $row['total_price'];
        $output .= "
        <tr>
        <td>{$i}</td>
        <td>{$row['customer_table_id']}</td>
        <td>{$row['customer_username']}</td>
        <td>{$row['customer_check_in_time']}</td>
        <td>{$row['customer_check_out_time']}</td>
        <td>{$row['stopwatch_elapsed_time']}</td>
        <td>{$row['customer_price']}</td>
        <td>{$row['total_price']}</td>
        <td>{$row['customer_canteen_bill']}</td>
        <td>$grand_total</td>
        <td>N/A</td>
        </tr>
        ";
        $i++;
    }

    $output .= "</tbody></table>";
} else {
    $output = "<p>No data found</p>";
}

echo $output;

mysqli_close($conn);
?>

