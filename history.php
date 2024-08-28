<?php
session_start();
include "conn.php";

if (!isset($_SESSION['club_email'])) {
    header("location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cuebook.css">
    <link rel="shortcut icon" href="assets/logo/favicon/favicon.ico" type="image/x-icon">
    <title>Snooker Cue Book- History</title>
</head>
<body>
<!-- Nav Start -->
<nav>
    <div id="logo-pic">
        <img src="assets/logo/svg/logo-no-background.svg" alt="Logo" width="180" height="60">
    </div>
    
    <div>
        <ul id="nav-links">
            <li><a href="dashboard.php" target="_blank">New Table</a></li>
            <li><a href="history.php">History</a></li>
            <!-- <li><a href="add-on.php">Add-ons</a></li> -->
            <!-- <li><a href="inventory.php">Inventory</a></li> -->
            <!-- <li><a href="expense.php">Expense</a></li> -->
            <!-- <li><a href="closing.php">Closing</a></li> -->
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div id="menu" onclick="openMenu()">&#9776;</div>
</nav>

<div id="nav-col">
    <div id="nav-col-links" class="nav-col-links">
        <a id="link" href="new-table.php" target="_blank">New Table</a>
        <a id="link" href="history.php">History</a>
        <!-- <a id="link" href="add-on.php">Add-ons</a> -->
        <!-- <a id="link" href="inventory.php">Inventory</a> -->
        <!-- <a id="link" href="expense.php">Expense</a> -->
        <!-- <a id="link" href="closing.php">Closing</a> -->
        <a id="link" href="logout.php">Log out</a>
    </div>
</div>
<!-- Nav End -->

<?php echo "Welcome, " . htmlspecialchars($_SESSION['clubname']); ?>

<div class="date-search-bar">
    <label for="search-date">Select Date:</label>
    <input type="date" id="search-date" max="<?php echo date('Y-m-d'); ?>" autocomplete="off">
</div>

<div id="existing-tables-container"></div>

<script src="jquery.js"></script>
<script src="app.js"></script>
<!-- Live Search -->
<script>
  $(document).ready(function() {
    $('#search-date').on("change", function() {
      var searchTerm = $(this).val();
      $.ajax({
        url: "ajax-table-search.php",
        type: "POST",
        data: { search: searchTerm },
        success: function(response) {
          if (response.trim().length > 0) {
            $("#existing-tables-container").html(response);
          } else {
            $("#existing-tables-container").html("<h4>No Record Found</h4>");
          }
        },
        error: function() {
          $("#existing-tables-container").html("<h4>Error: Unable to process request.</h4>");
        }
      });
    });
  });
</script>
 
</body>
</html>
