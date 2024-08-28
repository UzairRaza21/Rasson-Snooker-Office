<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "conn.php";

// Get the club name from the session
$cue_clubname = $_SESSION['clubname'];

$table_id = $_POST['table_id'];

// Query to fetch all the tables from the database
$sql_fetch_tables = "SELECT * FROM `{$cue_clubname}_table` WHERE table_id = {$table_id}";
$result = mysqli_query($conn, $sql_fetch_tables);
$output = "";

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
   $output = '';
    
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<form id="edit-table">

        <div class="cue-input-field">
            <label for="table_name">Table Name:*</label>
            <input type="text"  id="edit_table_name" class="table-input" value= "'. htmlspecialchars($row['table_name']) .'" required>
            <input type="text"  id="edit_table_id" class="table-input" hidden value= "'. htmlspecialchars($row['table_id']) .'" required>
        </div>

        <h3>Game Type- Snooker</h3>

        <div class="cue-input-field">
            <label for="snooker_customer_price">Snooker Rate per Min:*</label>
            <input type="text"  id="edit_snooker_table_price"   value= "'. htmlspecialchars($row['snooker_table_price']) .'"  class="table-input" required>
        </div>

        <h3>Game Type- Century</h3>

        <div class="cue-input-field">
            <label for="century_customer_price">Century Rate per Min:*</label>
            <input type="text"  id="edit_century_table_price"  value= "'. htmlspecialchars($row['century_table_price']) .'" class="table-input" placeholder="e.g 4.5" required>
        </div>

        <div class="cue-input-field">
            <input type="submit" class="table-button" value="Edit Table" id="edit-button">
        </div>

        </form>';
    }

    echo $output;

} else {
    echo 'No tables found.';
}

mysqli_close($conn);
?>



