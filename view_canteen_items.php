<?php
session_start();
include "conn.php";

// Ensure user is logged in
if (!isset($_SESSION['club_email'])) {
    header("location: login.php");
    exit();
}

$cue_clubname = $_SESSION['clubname'];
$club_email = $_SESSION['club_email'];

// Fetch the canteen items for the logged-in user
$sql = "SELECT canteen_item_name, canteen_item_qty, canteen_item_price, canteen_total_price FROM `{$cue_clubname}_canteen` WHERE club_email = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $club_email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $item_name, $item_qty, $item_price, $total_price);

    $items = [];
    $grand_total = 0;

    while (mysqli_stmt_fetch($stmt)) {
        $items[] = [
            'item_name' => $item_name,
            'item_qty' => $item_qty,
            'item_price' => $item_price,
            'total_price' => $total_price
        ];
        $grand_total += $total_price;
    }

    mysqli_stmt_close($stmt);

    // Output the items and the grand total as JSON
    echo json_encode([
        'items' => $items,
        'grand_total' => $grand_total
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Error fetching canteen items: ' . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>
