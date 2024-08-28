<?php
session_start();
include "conn.php";

if (!isset($_SESSION['club_email'])) {
    header("location: login.php");
    exit();
}

$cue_clubname = $_SESSION['clubname'];

$search_value = $_POST["search"];

$sql_search = "SELECT * FROM `{$cue_clubname}_customer` WHERE `customer_visit_date` LIKE '%{$search_value}%'";

$result = mysqli_query($conn, $sql_search) or die("SQL Query for load data Failed");

if (mysqli_num_rows($result) > 0) {
    echo '<div id="existing-tables-container" class="new-tables-booking-container">';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="existing-table" data-customer-id="' . htmlspecialchars($row['customer_id']) . '" data-check-in-time="' . htmlspecialchars($row['customer_check_in_time']) . '">
        <div class="club-customer-info">
        <p>Name: ' . htmlspecialchars($row['customer_username']) . '</p>
        <p>Rate: Rs. ' . htmlspecialchars($row['customer_price']) . '/min</p>
        </div>
        <div class="club-customer-contact">
        <p>Mobile: ' . htmlspecialchars($row['customer_mobile_no']) . '</p>
        <p>Email: ' . htmlspecialchars($row['customer_email']) . '</p>
        <p><strong> Visit Date: ' . htmlspecialchars($row['customer_visit_date']) . ' </p> <br>
        </div>
        <div class="club-customer-checks">
            <p>Check In Time: <br> <span class="check-in-time">' . ($row['customer_check_in_time'] ? htmlspecialchars($row['customer_check_in_time']) : 'N/A') . '</span></p>
            <div class="club-customer-checks-arrow">&#8594;</div>
            <p>Check Out Time: <br> <span class="check-out-time">' . ($row['customer_check_out_time'] ? htmlspecialchars($row['customer_check_out_time']) : 'N/A') . '</span></p>
        </div>
        <div class="stopwatch">
            <span class="stopwatch-time">Time: ' . htmlspecialchars($row['stopwatch_elapsed_time']) . ' mins</span>
            <p>Total Price: Rs. <span class="total-price">' . htmlspecialchars($row['total_price']) . '</span></p>
        </div>
        <form class="check-in-form" ' . ($row['customer_check_in_time'] ? 'style="display: none;"' : '') . '>
            <input type="hidden" name="customer_id" value="' . htmlspecialchars($row['customer_id']) . '">
            <input type="hidden" name="customer_rate" value="' . htmlspecialchars($row['customer_price']) . '">
            <input type="submit" value="Check In" class="table-button">
        </form>
        <form class="check-out-form" ' . (!$row['customer_check_in_time'] || $row['customer_check_out_time'] ? 'style="display: none;"' : '') . '>
            <input type="hidden" name="customer_id" value="' . htmlspecialchars($row['customer_id']) . '">
            <input type="submit" value="Check Out" class="table-button">
        </form>
    </div>';
    }

    echo '</div>';
    mysqli_close($conn);

} else {
    echo "<h2>No Record Found</h2>";
}
?>
