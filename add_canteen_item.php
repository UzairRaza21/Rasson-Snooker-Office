<?php
session_start();
include "conn.php";

// Ensure user is logged in
if (!isset($_SESSION['club_email'])) {
    header("location: login.php");
    exit();
}

// Fetch customer ID from session instead of POST
$customer_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : 0;
$cue_clubname = $_SESSION['clubname'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and prepare POST data
    $canteen_item_name = mysqli_real_escape_string($conn, $_POST['canteen_inventory_name']);
    $canteen_item_qty = mysqli_real_escape_string($conn, $_POST['canteen_item_qty']);

    // Fetch the inventory price based on the selected item name
    $sql_price = "SELECT inventory_price FROM `{$cue_clubname}_inventory` WHERE inventory_name = ?";
    $stmt_price = mysqli_prepare($conn, $sql_price);

    if ($stmt_price) {
        mysqli_stmt_bind_param($stmt_price, "s", $canteen_item_name);
        mysqli_stmt_execute($stmt_price);
        mysqli_stmt_bind_result($stmt_price, $canteen_item_price);
        mysqli_stmt_fetch($stmt_price);
        mysqli_stmt_close($stmt_price);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Error fetching inventory price: ' . mysqli_error($conn)
        ]);
        exit();
    }

    $canteen_item_total = $canteen_item_qty * $canteen_item_price;

    // Insert the canteen item into the database
    $sql = "INSERT INTO `{$cue_clubname}_canteen` (`canteen_item_name`, `canteen_item_qty`, `canteen_item_price`, `canteen_item_total`, `canteen_item_sale_date`, `customer_id`) VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sidii", $canteen_item_name, $canteen_item_qty, $canteen_item_price, $canteen_item_total, $customer_id);
        mysqli_stmt_execute($stmt);

        // Check if the query was successful
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // Return success response with the item name, quantity, and price
            echo json_encode([
                'success' => true,
                'item_name' => $canteen_item_name,
                'item_qty' => $canteen_item_qty,
                'item_price' => $canteen_item_price
            ]);
        } else {
            // Return error response
            echo json_encode([
                'success' => false,
                'error' => 'Error adding canteen item: ' . mysqli_error($conn)
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
