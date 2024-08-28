<?php
session_start();
include "conn.php";

if (!isset($_SESSION['club_email']) || !isset($_SESSION['clubname'])) {
    echo json_encode(["status" => "error", "message" => "Session not set."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cue_clubname = $_SESSION['clubname'];

    // Check if the previous shift is still open
    $sql_check_last_shift = "SELECT close_time 
                             FROM `{$cue_clubname}_shift` 
                             ORDER BY shift_id DESC 
                             LIMIT 1";

    $result_check_last_shift = mysqli_query($conn, $sql_check_last_shift);
    if ($result_check_last_shift) {
        $row = mysqli_fetch_assoc($result_check_last_shift);
        if ($row && is_null($row['close_time'])) {
            echo json_encode(["status" => "error", "message" => "Previous shift is still open. Please close it before starting a new shift."]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Error checking last shift: " . mysqli_error($conn)]);
        exit();
    }

    // Fetching the total income for the current day
    $sql_closing_income = "SELECT SUM(total_price) AS total_income
                           FROM `{$cue_clubname}_customer` 
                           WHERE DATE(`customer_visit_date`) = CURDATE()";

    $result_income = mysqli_query($conn, $sql_closing_income);
    $income = 0;
    if ($result_income) {
        $row = mysqli_fetch_assoc($result_income);
        $income = (float)($row['total_income'] ?? 0);
    } else {
        echo json_encode(["status" => "error", "message" => "Error fetching income: " . mysqli_error($conn)]);
        exit();
    }

    // Fetching the previous closing balance
    $sql_last_shift = "SELECT closing_balance 
                       FROM `{$cue_clubname}_shift` 
                       ORDER BY shift_id DESC 
                       LIMIT 1";

    $result_last_shift = mysqli_query($conn, $sql_last_shift);
    $opening_balance = 0;
    if ($result_last_shift) {
        $row = mysqli_fetch_assoc($result_last_shift);
        $opening_balance = (float)($row['closing_balance'] ?? 0);
    } else {
        echo json_encode(["status" => "error", "message" => "Error fetching last shift data: " . mysqli_error($conn)]);
        exit();
    }

    $closing_balance = $opening_balance + $income;
    $total_collection = $income;
    $open_by = $_SESSION['club_email'];
    $close_by = $_SESSION['club_email']; // Assuming the same person closes the shift

    // Inserting the shift data
    $sql_shift = "INSERT INTO `{$cue_clubname}_shift` 
                 (`opening_balance`, `closing_balance`, `total_collection`, `open_by`, `close_by`, `open_time`) 
                 VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($conn, $sql_shift);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ddsss", $opening_balance, $closing_balance, $total_collection, $open_by, $close_by);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Shift data inserted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to insert shift data."]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare the statement: " . mysqli_error($conn)]);
    }
}

mysqli_close($conn);
?>
