<?php
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cue_clubname = $_SESSION['clubname'];

    // Check if session variable exists
    if (isset($cue_clubname)) {
        // Prepare the SQL query to fetch both inventory_name and inventory_price
        $sql_inventory_info = "SELECT inventory_name, inventory_price FROM `{$cue_clubname}_inventory`";
        $result = mysqli_query($conn, $sql_inventory_info);

        $inventory = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $inventory[] = [
                    'name' => $row['inventory_name'],
                    'price' => $row['inventory_price']
                ];
            }
            echo json_encode($inventory);
        } else {
            echo json_encode(["error" => "Error fetching inventory: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["error" => "Session 'clubname' is not set."]);
    }

    mysqli_close($conn);
}
?>
