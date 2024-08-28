<?php
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table_name = mysqli_real_escape_string($conn, $_POST['table_name']);
    $snooker_rate = mysqli_real_escape_string($conn, $_POST['snooker_customer_price']);
    $century_rate = mysqli_real_escape_string($conn, $_POST['century_customer_price']);

    $cue_clubname = $_SESSION['clubname'];

    // Check if the table name already exists
    $check_query = "SELECT COUNT(*) FROM `{$cue_clubname}_table` WHERE `table_name` = ?";
    if ($stmt = mysqli_prepare($conn, $check_query)) {
        mysqli_stmt_bind_param($stmt, 's', $table_name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($count > 0) {
            echo json_encode(['success' => false, 'error' => 'Table name already exists.']);
            mysqli_close($conn);
            exit;
        }
    }

    // Insert new table if name is unique
    $sql_table_info = "INSERT INTO `{$cue_clubname}_table` (`table_name`, `snooker_table_price`, `century_table_price`, `table_creation_date`) VALUES (?, ?, ?, NOW())";
    
    if ($stmt = mysqli_prepare($conn, $sql_table_info)) {
        mysqli_stmt_bind_param($stmt, 'sss', $table_name, $snooker_rate, $century_rate);
        $result_table_info = mysqli_stmt_execute($stmt);
        if ($result_table_info) {
            echo json_encode([
                'success' => true,
                'table_name' => $table_name,
                'snooker_table_rate' => $snooker_rate,
                'century_table_rate' => $century_rate,
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>
