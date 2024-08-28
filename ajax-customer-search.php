<?php
session_start();
include "conn.php";

// Check if user is logged in
if (!isset($_SESSION['club_email'])) {
    header("Location: login.php");
    exit();
}

$cue_clubname = $_SESSION['clubname'];
$search_value = $_POST["search"];

// Sanitize input and prepare the SQL query
$search_value = '%' . $search_value . '%';
$sql_search = "SELECT * FROM `{$cue_clubname}_customer` WHERE `customer_username` LIKE ? OR `customer_mobile_no` LIKE ?";
$stmt = $conn->prepare($sql_search);
$stmt->bind_param("ss", $search_value, $search_value);
$stmt->execute();
$result = $stmt->get_result();

$output = "";
if ($result->num_rows > 0) {
    $output = "<table>
    <thead>
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

    $i = 1; // Counter for row number
    while ($row = $result->fetch_assoc()) {
        $grand_total = $row['customer_canteen_bill'] + $row['total_price'];
        $output .= "
        <tr>
        <td>" . htmlspecialchars($i) . "</td>
        <td>" . htmlspecialchars($row['customer_table_id']) . "</td>
        <td>" . htmlspecialchars($row['customer_username']) . "</td>
        <td>" . htmlspecialchars($row['customer_check_in_time']) . "</td>
        <td>" . htmlspecialchars($row['customer_check_out_time']) . "</td>
        <td>" . htmlspecialchars($row['stopwatch_elapsed_time']) . "</td>
        <td>" . htmlspecialchars($row['customer_price']) . "</td>
        <td>" . htmlspecialchars($row['total_price']) . "</td>
        <td>" . htmlspecialchars($row['customer_canteen_bill']) . "</td>
        <td>" . htmlspecialchars($grand_total) . "</td>
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

$stmt->close();
$conn->close();
?>
