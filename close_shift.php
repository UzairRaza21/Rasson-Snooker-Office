<?php
session_start();
include "conn.php";

if (!isset($_SESSION['club_email']) || !isset($_SESSION['clubname'])) {
    echo json_encode(["error" => "Session not set."]);
    exit();
}

$cue_clubname = $_SESSION['clubname'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shift_id = $_POST['shift_id'] ?? null; // Assuming you pass the shift ID to close the specific shift

    if (!$shift_id) {
        echo json_encode(["error" => "Shift ID not provided."]);
        exit();
    }

    // Check if the shift is open before closing
    $sql_check_open = "SELECT * FROM `{$cue_clubname}_shift` WHERE `shift_id` = ? AND `close_time` IS NULL";
    $stmt_check = $conn->prepare($sql_check_open);
    $stmt_check->bind_param("i", $shift_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "No open shift found with the given ID."]);
        exit();
    }

    // Close the shift
    $sql_shift_close = "UPDATE `{$cue_clubname}_shift` SET `close_time` = NOW() WHERE `shift_id` = ?";
    $stmt_close = $conn->prepare($sql_shift_close);
    $stmt_close->bind_param("i", $shift_id);

    if ($stmt_close->execute()) {
        echo json_encode(["success" => "Shift closed successfully."]);
    } else {
        echo json_encode(["error" => "Failed to close the shift."]);
    }

    $stmt_check->close();
    $stmt_close->close();
    $conn->close();
}
?>
