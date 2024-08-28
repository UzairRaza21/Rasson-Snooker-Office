<?php
session_start();
include "conn.php";

// Ensure user is logged in
if (!isset($_SESSION['club_email'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);

    $cue_clubname = $_SESSION['clubname'];

    // Fetch item price from inventory
    $sql = "SELECT inventory_price FROM `{$cue_clubname}_inventory` WHERE item_name = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $item_name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $item_price);
        mysqli_stmt_fetch($stmt);

        // Check if item price was found
        if ($item_price !== null) {
            echo json_encode([
                'success' => true,
                'item_price' => $item_price
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Item not found in inventory'
            ]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Error preparing query: ' . mysqli_error($conn)
        ]);
    }

    mysqli_close($conn);
}
?>