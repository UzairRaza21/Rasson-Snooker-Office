<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "conn.php";

$cue_clubname = $_SESSION['clubname'];

// Fetch all customers and their rates
$sql_fetch_customers = "SELECT * FROM `{$cue_clubname}_customer`";
$result_customers = mysqli_query($conn, $sql_fetch_customers);

if ($result_customers) {
    $customers = [];
    while ($row = mysqli_fetch_assoc($result_customers)) {
        $customers[] = $row;
    }

    // Fetch table details
    $sql_fetch_tables = "SELECT * FROM `{$cue_clubname}_table`";
    $result_tables = mysqli_query($conn, $sql_fetch_tables);

    $tables = [];
    if ($result_tables) {
        while ($row = mysqli_fetch_assoc($result_tables)) {
            $tables[] = $row;
        }
    }

    // Prepare output
    $output = [
        'customers' => $customers,
        'tables' => $tables
    ];

    echo json_encode([
        'success' => true,
        'data' => $output
    ]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}

mysqli_close($conn);
?>
