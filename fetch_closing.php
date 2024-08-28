<?php
session_start();
include "conn.php";
 
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cue_clubname = $_SESSION['clubname'];

    // Prepare the SQL queries to fetch the closing details
    $sql_closing_income = "SELECT SUM(total_price) as snooker_billard_sales 
                           FROM `{$cue_clubname}_customer` 
                           WHERE DATE(`customer_visit_date`) = CURDATE()";

    $sql_closing_canteen = "SELECT SUM(canteen_item_total) as canteen_sales 
                            FROM `{$cue_clubname}_canteen` 
                            WHERE DATE(`canteen_item_sale_date`) = CURDATE()";

    $sql_closing_expense = "SELECT SUM(expense_amount) as total_expense 
                            FROM `{$cue_clubname}_expense` 
                            WHERE DATE(`expense_date`) = CURDATE()";

    $result_income = mysqli_query($conn, $sql_closing_income);
    $result_canteen = mysqli_query($conn, $sql_closing_canteen);
    $result_expense = mysqli_query($conn, $sql_closing_expense);

    if ($result_income && $result_canteen && $result_expense) {
        $income = mysqli_fetch_assoc($result_income);
        $canteen = mysqli_fetch_assoc($result_canteen);
        $expense = mysqli_fetch_assoc($result_expense);

        $snooker_billard_sales = $income['snooker_billard_sales'] ?? 0;
        $canteen_sales = $canteen['canteen_sales'] ?? 0;
        $total_expense = $expense['total_expense'] ?? 0;
        $total_sales = $snooker_billard_sales + $canteen_sales;
        $profit = $total_sales - $total_expense;

        $response = [
            'success' => true,
            'data' => [
                'snooker_billard_sales' => $snooker_billard_sales,
                'canteen_sales' => $canteen_sales,
                'total_sales' => $total_sales,
                'total_expense' => $total_expense,
                'profit' => $profit
            ]
        ];
    } else {
        $response = ['error' => 'Error fetching closing details: ' . mysqli_error($conn)];
    }

    mysqli_close($conn);
    echo json_encode($response);
}
?>
