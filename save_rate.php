<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "conn.php";

$cue_clubname = $_SESSION['clubname'];
$table_id = $_POST['table_id'];

// Handle the initial insertion of table data
if (isset($_POST['table_id']) && !isset($_POST['table_rate'])) {
    $table_id = intval($_POST['table_id']);
    $customer_name = "Guest Player"; // Replace with actual customer name
    $customer_phone = "Customer Phone"; // Replace with actual customer phone
    $customer_email = "Customer Email"; // Replace with actual customer email

    $sql_customer_info = "INSERT INTO `{$cue_clubname}_customer` (customer_username, customer_mobile_no, customer_price, customer_email) 
                          VALUES (?, ?, 0, ?)"; // Initial price set to 0

    if ($stmt = mysqli_prepare($conn, $sql_customer_info)) {
        mysqli_stmt_bind_param($stmt, 'sss', $customer_name, $customer_phone, $customer_email);
        $result_customer_info = mysqli_stmt_execute($stmt);

        if ($result_customer_info) {
            $customer_id = mysqli_insert_id($conn);

            // Fetch table details for rate selection
            $sql_fetch_tables = "SELECT * FROM `{$cue_clubname}_table` WHERE table_id = {$table_id}";
            $result = mysqli_query($conn, $sql_fetch_tables);

            if (mysqli_num_rows($result) > 0) {
                $output = '<form class="save_customer_rate" data-table_id="' . htmlspecialchars($table_id) . '">';
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $output .= '
                        <label for="table_rate">Select Rate:</label>
                        <select name="table_rate" class="table_rate" data-table_id="' . htmlspecialchars($table_id) . '" data-customer_id="' . htmlspecialchars($customer_id) . '">
                            <option value="">Select Game Type</option>
                            <option value="' . htmlspecialchars($row['snooker_table_price']) . '">Snooker - Rate ' . htmlspecialchars($row['snooker_table_price']) . '/min</option>
                            <option value="' . htmlspecialchars($row['century_table_price']) . '">Century - Rate ' . htmlspecialchars($row['century_table_price']) . '/min</option>
                        </select>
                        <input type="hidden" name="table_id" value="' . htmlspecialchars($table_id) . '">
                        <input type="hidden" name="cue_clubname" value="' . htmlspecialchars($cue_clubname) . '">
                    ';
                }
  
                $output .= '</form>';
                echo json_encode([
                    'success' => true,
                    'customer_id' => $customer_id,
                    'form' => $output
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No tables found.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }

        mysqli_stmt_close($stmt);
    }
}

// Handle rate update
if (isset($_POST['table_id']) && isset($_POST['table_rate'])) {
    $table_id = intval($_POST['table_id']);
    $customer_rate = floatval($_POST['table_rate']);
    $customer_table_id = intval($_POST['table_id']);
    $customer_id = intval($_POST['customer_id']); // Assuming customer ID is passed

    $sql_update_rate = "UPDATE `{$cue_clubname}_customer` 
                        SET customer_price = ?, customer_table_id = ? 
                        WHERE customer_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql_update_rate)) {
        mysqli_stmt_bind_param($stmt, 'dii', $customer_rate, $customer_table_id, $customer_id);
        $result_update_rate = mysqli_stmt_execute($stmt);

        if ($result_update_rate) {
            // Fetch the updated customer rate along with check-in and check-out times
            $sql_fetch_times = "SELECT customer_check_in_time, customer_check_out_time 
                                FROM `{$cue_clubname}_customer` 
                                WHERE customer_id = ?";
            if ($stmt_time = mysqli_prepare($conn, $sql_fetch_times)) {
                mysqli_stmt_bind_param($stmt_time, 'i', $customer_id);
                mysqli_stmt_execute($stmt_time);
                mysqli_stmt_bind_result($stmt_time, $customer_check_in, $customer_check_out);
                mysqli_stmt_fetch($stmt_time);
                mysqli_stmt_close($stmt_time);

                $customer_check_in = $customer_check_in ?? 'N/A';
                $customer_check_out = $customer_check_out ?? 'N/A';

                echo json_encode([
                    'success' => true,
                    'customer_rate' => $customer_rate,
                    'customer_id' => $customer_id,
                    'customer_check_in_time' => $customer_check_in,
                    'customer_check_out_time' => $customer_check_out,
                    'message' => 'Rate updated successfully!'
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error fetching check-in/check-out times.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>
