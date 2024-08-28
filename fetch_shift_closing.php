<?php
session_start();
include "conn.php";

if (!isset($_SESSION['club_email']) || !isset($_SESSION['clubname'])) {
    echo json_encode(["error" => "Session not set."]);
    exit();
}

$cue_clubname = $_SESSION['clubname'];

// SQL query with FORMAT for balances and total collection
$sql = "SELECT shift_id, 
               FORMAT(opening_balance, 2) AS opening_balance, 
               FORMAT(closing_balance, 2) AS closing_balance, 
               FORMAT(total_collection, 2) AS total_collection, 
               open_by, 
               close_by, 
               DATE_FORMAT(open_time, '%Y-%m-%d %r') AS open_time, 
               DATE_FORMAT(close_time, '%Y-%m-%d %r') AS close_time
        FROM `{$cue_clubname}_shift`
        ORDER BY shift_id DESC"; // Fetch all shift details, most recent first

$result = mysqli_query($conn, $sql);

if ($result) {
    $shifts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $shifts[] = $row; // Add each row to the $shifts array
    }
    echo json_encode($shifts); // Return the array as a JSON response
} else {
    echo json_encode(["error" => "Error fetching shift data: " . mysqli_error($conn)]);
}

mysqli_close($conn);
?>
