<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "conn.php";

// Get the club name from the session
$cue_clubname = $_SESSION['clubname'];

// Query to fetch all the tables from the database
$sql_fetch_tables = "SELECT * FROM {$cue_clubname}_table ORDER BY table_creation_date ASC";
$result = mysqli_query($conn, $sql_fetch_tables);

// Start displaying the tables
if (mysqli_num_rows($result) > 0) {
    echo '<div class="snooker-table-container">';

    // Loop through the result set and display each row
    while ($row = mysqli_fetch_assoc($result)) {
        $tableId = htmlspecialchars($row['table_id']);
        $current_customer_id = isset($row['current_customer_id']) ? htmlspecialchars($row['current_customer_id']) : '';
 
        echo '<div class="snooker-table-style">';

        echo '<div class="snooker-table-details">';

        echo '<div class="snooker-table-name">';
        echo '<p class="table-heading" >'. htmlspecialchars($row['table_name']) . '</p>';
        echo '<p>' . htmlspecialchars($row['table_creation_date']) . '</p>';
        echo '</div>'; // End snooker-name
        
        echo '<div class="snooker-table-price">';
        echo '<p>Snooker Price ' . htmlspecialchars($row['snooker_table_price']) . '/min</p>';
        echo '<p>Century Price ' . htmlspecialchars($row['century_table_price']) . '/min</p>';
        echo '</div>'; // End snooker-price

        echo '</div>'; // End snooker-table-details


        echo '<div class="new-customer" data-table_id="'. $tableId .'" style="display:none;"></div>';
        echo '<div class="customer-playtime" data-table_id="'. $tableId .'" style="display:none;"></div>';
        
        echo '<div class="new-table-button">';
        echo '<button class="create-new-customer green-button" data-table_id="'. $tableId .'">Play</button>';
        echo '<button class="history-table green-button" data-table_id="'. $tableId .'">History</button>';
        echo '<button class="view-editTable-button green-button" data-table_id="'. $tableId .'">Edit</button>';
        echo '<button class="delete-table green-button" data-table_id="'. $tableId .'">Delete</button>';
        echo '</div>';

        echo '</div>'; // End snooker-table-style
    }
 
    echo '</div>'; // End snooker-table-container
} else {
    echo '<div class="snooker-table-container"></div>';
}

mysqli_close($conn);
?>
