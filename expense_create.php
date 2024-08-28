<?php
session_start();
include "conn.php";

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expense_name = mysqli_real_escape_string($conn, $_POST['expense_name']);
    $expense_amount = mysqli_real_escape_string($conn, $_POST['expense_amount']);

    $cue_clubname = $_SESSION['clubname'];

    // Check if the expense name already exists
    $check_query = "SELECT * FROM `{$cue_clubname}_expense` WHERE `expense_name` = ?";
    $stmt_check = mysqli_prepare($conn, $check_query);

    if ($stmt_check) {
        mysqli_stmt_bind_param($stmt_check, "s", $expense_name);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $response = ['error' => 'Expense with this name already exists.'];
        } else {
            // Prepare the SQL query to insert the current date and time using NOW()
            $sql_expense_info = "INSERT INTO `{$cue_clubname}_expense` (`expense_name`, `expense_amount`, `expense_date`) VALUES (?, ?, NOW())";
            $stmt = mysqli_prepare($conn, $sql_expense_info);

            if ($stmt) {
                // Bind the parameters
                mysqli_stmt_bind_param($stmt, "ss", $expense_name, $expense_amount);

                // Execute the statement
                mysqli_stmt_execute($stmt);

                // Check if the query was successful
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $expense_id = mysqli_insert_id($conn);
                    $response = [
                        'success' => true,
                        'expense_id' => $expense_id,
                        'expense_name' => $expense_name,
                        'expense_amount' => $expense_amount,
                        'expense_date' => date('Y-m-d H:i:s') // Return the current date and time
                    ];
                } else {
                    $response = ['error' => 'Error adding expense item: ' . mysqli_error($conn)];
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            } else {
                $response = ['error' => 'Error preparing query: ' . mysqli_error($conn)];
            }
        }

        // Close the statement
        mysqli_stmt_close($stmt_check);
    } else {
        $response = ['error' => 'Error preparing check query: ' . mysqli_error($conn)];
    }

    // Close the database connection
    mysqli_close($conn);
    echo json_encode($response);
}
?>
