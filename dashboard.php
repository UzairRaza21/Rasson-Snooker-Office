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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <title>Cue Book- Admin Dashboard</title>
</head>
<body>
<!-- Nav Start -->
<nav>
    <!-- <div id="logo-pic">
        <img src="assets/logo/svg/logo-no-background.svg" alt="threads" width="180" height="60">
    </div> -->
    
    <div>
        <ul id="nav-links">
            <!-- <li><a href="dashboard.php" target="_blank">New Table</a></li> -->
            <!-- <li><a href="history.php" target="_blank">History</a></li> -->
            <!-- <li><a href="add-on.php" target="_blank">Add-ons</a></li> -->
            <!-- <li class="view-inventory-button nav-button">Inventory</li> -->
            <!-- <li class="view-expense-button nav-button">Expense</li> -->
            <li class="view-closing-button nav-button">Day History</li>
            <li class="view-shift-closing nav-button">Shift Closing</li>
            <li class="start-new-shift nav-button">Start New Shift</li>
            
            <!-- <li><a href="logout.php">Logout</a></li> -->
        </ul>
    </div>

    <div id="menu" onclick="openMenu()">&#9776;</div>
</nav>

<div id="nav-col">
    <div id="nav-col-links" class="nav-col-links">
        <a id="link" href="new-table.php" target="_blank">New Table</a>
        <!-- <a id="link" href="history.php" target="_blank">History</a> -->
        <!-- <a id="link" href="add-on.php" target="_blank">Add-ons</a> -->
        <button id="link" class="view-inventory-button nav-button-mobile">Inventory</button>
        <button id="link" class="view-expense-button nav-button-mobile" >Expense</button>
        <button id="link" class="view-closing-button nav-button-mobile" >Day History</button>
        <button id="link" class="view-shift-closing nav-button-mobile" >Shift Closing</button>
        <button id="link" class="start-new-shift nav-button-mobile" >Start New Shift</button>
        <a id="link" href="logout.php">Log out</a>
    </div>
</div>
<!-- Nav End -->
 
<div class="sidebar" id="sidebar">

    <div id="logo-pic">
        <img src="assets/logo/svg/logo-no-background.svg" alt="threads" width="180" height="60">
    </div>

  <ul>
    <li><a href="dashboard.php">New Table</a></li>
    <li class="view-inventory-button nav-button">Inventory</li>
    <li class="view-expense-button nav-button">Expense</li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<div class="content" id="content">
  <!-- <h1>Content</h1> -->


<?php echo "Welcome, " . htmlspecialchars($_SESSION['clubname']); ?>



<!-- Modal to create Table -->
<div id="createTableModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Create Table</h2>
        
        <form id="create-table">

        <div class="cue-input-field">
            <label for="table_name">Table Name:*</label>
            <input type="text" name="table_name" class="table-input" id="table_name" placeholder="e.g Table 1" required>
        </div>

        <h3>Game Type- Snooker</h3>

        <div class="cue-input-field">
            <label for="snooker_customer_price">Snooker Rate per Min:*</label>
            <input type="text" name="snooker_customer_price" id="snooker_customer_price" class="table-input" placeholder="e.g 4.5" required>
        </div>

        <h3>Game Type- Century</h3>

        <div class="cue-input-field">
            <label for="century_customer_price">Century Rate per Min:*</label>
            <input type="text" name="century_customer_price" id="century_customer_price" class="table-input" placeholder="e.g 4.5" required>
        </div>

        <div class="cue-input-field">
            <input type="submit" class="table-button" value="Create Table">
        </div>

        </form>

    </div>
</div>



<!-- Display Created Tables -->
<div class="website-tables-container">
    <button class="view-createTable-button green-button" >Add New Table +</button>
    <?php include 'display_snooker_tables.php'; ?>
</div>

<!-- Edit Created Table -->
<div id="editTableModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Table</h2>
        <div class="edit-form"></div>
    </div>
</div>


           
<!-- Register customer Modal -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Register Customer</h2>

        <form id="registerForm" method="post">
            <div class="cue-input-field">
                <label for="customer_username">Customer Name:*</label>
                <input type="text" name="customer_username" id="customer_username" class="table-input" placeholder="e.g Adil Khan" required>
            </div>
            <div class="cue-input-field">
                <label for="customer_mobile_no">Mobile:*</label>
                <input type="text" name="customer_mobile_no" id="customer_mobile_no" class="table-input" placeholder="e.g 0300-1234567" required>
            </div>
            <div class="cue-input-field">
                <label for="customer_email">Email:*</label>
                <input type="email" name="customer_email" id="customer_email" class="table-input" placeholder="e.g abc@email.com" required>
            </div>

            <input type="hidden" name="customer_id" id="customer_id" value="">

            <div class="cue-input-field">
                <input type="submit" class="table-button" value="Register">
            </div>
        </form>

    </div>
</div>

<!-- Bill Modal -->
<div id="billModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Bill Details</h2>
        <p><strong>Name:</strong> <span id="billCustomerName"></span></p>
        <p><strong>Mobile:</strong> <span id="billCustomerMobile"></span></p>
        <p><strong>Email:</strong> <span id="billCustomerEmail"></span></p>
        <p><strong>Check In:</strong> <span id="billCheckInTime"></span></p>
        <p><strong>Check Out:</strong> <span id="billCheckOutTime"></span></p>
        <p><strong>Played Time:</strong> <span id="billPlayedTime"></span></p>
        <p><strong>Rate per Min:</strong> Rs. <span id="billRate"></span></p>
        <p><strong>Total Price:</strong> Rs. <span id="billTotalPrice"></span></p>
        <button id="printBillButton" onclick="printBill()">Print Bill</button>
    </div>
</div>

<!-- Add Inventory -->
<div id="inventoryModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Inventory Details</h2>

        <div class="inventory-modal-content">

        <form action="" method="post" id="create_inventory" class="inventory-form">
            <h3>Create Inventory</h3>

            <div class="inventory-input">
            <label for="">Inventory Name</label>
            <input type="text" name="inventory_name" id=""><br>
            </div>

            <div class="inventory-input">
            <label for="">Inventory Price</label>
            <input type="text" name="inventory_price" id=""><br>
            </div>

            <div class="inventory-input">
            <label for="">Inventory Qty</label>
            <input type="text" name="inventory_qty" id=""><br>
            </div>

            <input type="submit" value="Create Inventory" name="create_inventory" class="green-button">
        </form>

        <div class="inventory-list">
            <h3>Inventory List</h3>
        </div>

        </div>

    </div>
</div>

<!-- Canteen Modal -->
<div id="canteenModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Canteen Details</h2>

        <div class="canteen-container">
        <table id="canteen-items-table" class="canteen-table-style">
        <thead>
            <tr>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
        <!-- Rows will be inserted here by JavaScript -->
        </tbody>
        </table>

        <form id="canteen-form" action="" method="post" class="canteen-form-style">
            <label for="canteen_inventory_name">Select Item:</label>
            <select name="canteen_inventory_name" id="canteen_inventory_name" required>
                <!-- Options will be added dynamically -->
            </select>

            <label for="canteen_item_qty">Qty:</label>
            <input type="number" name="canteen_item_qty" id="canteen_item_qty" value="1" required>
            <input type="hidden" name="customer_id" id="customer_id" value="">

            <input type="submit" value="Add Item" name="add_canteen_item" class="green-button">
        </form>
        </div>

    </div>
</div>

<!-- Add Expense -->
<div id="expenseModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Expense Details</h2>

        <div class="expense-modal-content">

        <form action="" method="post" id="create_expense" class="inventory-form">
            <h3>Add Expense</h3>

            <div class="inventory-input">
            <label for="">Expense Name</label>
            <input type="text" name="expense_name" id=""><br>
            </div>

            <div class="inventory-input">
            <label for="">Amount</label>
            <input type="text" name="expense_amount" id=""><br>
            </div>

            <input type="submit" value="Add Expense" name="add_expense" class="green-button">
        </form>

        <div class="expense-list">
            <h3>Expense List</h3>
        </div>

        </div>

    </div>
</div>


<div id="closingModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Closing Details</h2>
        <table id="closing-items-table">
        <thead>
            <tr>
            <th>Snooker/Billard</th>
            <th>Canteen</th>
            <th>Total</th>
            <th>Expense</th>
            <th>Profit</th>
            </tr>
        </thead>
        <tbody>
        <!-- Rows will be inserted here by JavaScript -->
        </tbody>
        </table>
        <button id="printButton" class="green-button">Print Table</button>
    </div>
</div>

<!-- History Modal -->
<div id="historyModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>History Table</h2>
        <div class="date-search-bar">
            <label for="search">Search:</labe>
            <input type="text" id="search-customer" name="search-customer" placeholder="mobile or customer name" >
        </div>

        <div id="modal-body"> <!-- Container for AJAX content --> </div>
    </div>
</div>

<!-- Shift Closing Modal -->
<div id="shiftModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Shift Closing</h2>

        <div id="shift-closing-body"> <!-- Container for AJAX content --> </div>
    </div>
</div>

<!-- Notifications -->

<div class='toast-container'>
  <div class="toast success-message">
    <div class="icon">
      <i class="fa-solid fa-circle-check fa-2xl"></i>
    </div>
    <div class="message">
      <h2>Success</h2>
      <p>This is a success message</p>
    </div>
  </div>
  <div class="toast error-message">
    <div class="icon">
      <i class="fa-solid fa-circle-xmark fa-2xl"></i>
    </div>
    <div class="message">
      <h2>Error</h2>
      <p>This is a error message</p>
    </div>
  </div>
  <div class="toast info-message">
    <div class="icon">
      <i class="fa-solid fa-circle-info fa-2xl"></i>
    </div>
    <div class="message">
      <h2>Info</h2>
      <p>This is a info message</p>
    </div>
  </div>
  <div class="toast warning-message">
    <div class="icon">
    <i class="fa-solid fa-triangle-exclamation fa-2xl"></i>
    </div>
    <div class="message">
      <h2>Warning</h2>
      <p>This is a Warning message</p>
    </div>
  </div>
</div>




</div> 
<!-- Content End -->
<script src="jquery.js"></script>
<script src="app.js"></script>

<script>


// Create Table

// View Create Table Button Click
$(document).on('click', '.view-createTable-button', function() {
    $('#createTableModal').show();
    
    // Create Table
    $('#create-table').on('submit', function(event) {
        event.preventDefault();
        
        $.ajax({
            url: "create_snooker_table.php",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                let data = JSON.parse(response);

                if (data.success) {
                    // Fetch the newly created table's HTML
                    $.ajax({
                        url: "display_snooker_tables.php",
                        type: "GET",
                        success: function(displayResponse) {
                            let newTableHtml = $(displayResponse).find('.snooker-table-style').last().prop('outerHTML');
                            $(".snooker-table-container").append(newTableHtml);
                            $('#createTableModal').hide();
                            $('#create-table')[0].reset();
                        },
                        error: function(xhr, status, error) {
                            alert("An error occurred while fetching the new table: " + error);
                        }
                    });
                } 
            },
            error: function(xhr, status, error) {
                alert("An error occurred: " + error);
            }
        });
    });
});

  
// Close the modal
$('.close').click(function() {
    $('#createTableModal').hide();
});

// Close the modal when clicking outside of the modal content
$(window).click(function(event) {
    if ($(event.target).is('#createTableModal')) {
        $('#createTableModal').hide();
    }
});

// Edit Button and show edit Modal
// showing Data for Edit
$(document).on('click', '.view-editTable-button', function() {
    var tableId = $(this).data('table_id');

    $('#editTableModal').show();

    $.ajax({
        url:"edit_snooker_table.php",
        type: "POST",
        data: {table_id : tableId},
        success:function(edit_table){
            $('.edit-form').html(edit_table);
        }
    })
});

// Saving Edited Data
$(document).on('click', '#edit-button', function() {
    var editTableId = $('#edit_table_id').val();
    var editTableName = $('#edit_table_name').val();
    var editTableSnookerPrice = $('#edit_snooker_table_price').val();
    var editTableCenturyPrice = $('#edit_century_table_price').val();

    $.ajax({
        url: "edited_snooker_table.php",
        type: "POST",
        data: {
            id: editTableId,
            table_name: editTableName, 
            edit_snooker: editTableSnookerPrice, 
            edit_century: editTableCenturyPrice 
        },
        success: function(response) {
            if(response == 1) {
                // Remove the old table row
                $('#table_' + editTableId).remove();

                // Create a new row with the updated data
                var newRow = `
                    <tr id="table_${editTableId}">
                        <td class="table-name">${editTableName}</td>
                        <td class="snooker-price">${editTableSnookerPrice}</td>
                        <td class="century-price">${editTableCenturyPrice}</td>
                        <td>
                            <button id="edit-button" class="btn btn-primary">Edit</button>
                        </td>
                    </tr>
                `;

                // Append the new row to the table body
                $('#table-body').append(newRow);

                $('#editTableModal').hide();
                alert("Table details updated successfully.");
            } else {
                alert("Failed to update table details.");
            }
        }
    });
});



// Close the modal
$('.close').click(function() {
$('#editTableModal').hide();
});

// Close the modal when clicking outside of the modal content
$(window).click(function(event) {
if ($(event.target).is('#editTableModal')) {
    $('#editTableModal').hide();
}
});

// Delete Table 

$(document).on('click', '.delete-table', function(){
    if(confirm("do you want to delete Table?")){

    var tableId = $(this).data('table_id');
    var delete_element = this;
    $.ajax({
        url:"delete_snooker_table.php",
        type: "POST",
        data:  {table_id : tableId},
        success:function(delete_table){
            if(delete_table == 1){
                $(delete_element).closest(".snooker-table-style").fadeOut();
            }else{
                alert("Cannot Delete Record");
            }
        }
    })


    }
})


// ------------------------------------------------------------------------------------------------------------
 

// Handle the click event for the "Re-Book" button
$(document).on('click', '.re-book-button', function() {
    var tableId = $(this).data('table_id'); // Get the table ID from the button's data attribute
    performRebookAction(tableId);
});

// Function to handle the rebooking action
function performRebookAction(tableId) {
    // Hide the existing table information and show the new customer form only for the specific table
    $('.existing-table[data-table_id="' + tableId + '"]').fadeOut();
    $('.create-new-customer[data-table_id="' + tableId + '"]').fadeIn();
}

// Handle the click event for the "Create New Customer" button
$(document).on('click', '.create-new-customer', function(event) {
    event.preventDefault();
    var tableId = $(this).data('table_id'); // Get the table ID from the button's data attribute
    performActionOnTable(tableId);
});

// Function to perform actions related to creating a new customer
function performActionOnTable(tableId) {
    // Hide the "Create New Customer" button and show the new customer form only for the specific table
    $('.create-new-customer[data-table_id="' + tableId + '"]').hide();
    $('.new-customer[data-table_id="' + tableId + '"]').fadeIn();

    $.ajax({
        url: "save_rate.php",
        type: "POST",
        data: { table_id: tableId },
        success: function(response) {
            var jsonResponse = JSON.parse(response);
            if (jsonResponse.success) {
                var newCustomerDiv = $('.new-customer[data-table_id="' + tableId + '"]');
                newCustomerDiv.data('customer_id', jsonResponse.customer_id);
                newCustomerDiv.html(jsonResponse.form);
            } else {
                alert('Error creating customer: ' + jsonResponse.error);
            }
        },
        error: function(xhr, status, error) {
            console.log('Error: ' + error);
        }
    });
}

// Handle the change event for the rate selection dropdown
$(document).on('change', '.table_rate', function(event) {
    event.preventDefault();

    var tableId = $(this).data('table_id'); // Get the table ID from the dropdown's data attribute
    var tableRate = $(this).val();
    var customerId = $(this).data('customer_id');
    
    // Perform actions on the specific table
    performTableRateChange(tableId, tableRate, customerId);
});
 
// Function to perform actions related to table rate change
function performTableRateChange(tableId, tableRate, customerId) {
    // Show the "Check-In" button and other elements only for the specific table
    $('.check-in[data-table_id="' + tableId + '"]').fadeIn();
    $('.customer-playtime[data-table_id="' + tableId + '"]').fadeIn();
    $('.save_customer_rate[data-table_id="' + tableId + '"]').fadeOut();

    $.ajax({
        url: "save_rate.php",
        type: "POST",
        data: {
            table_id: tableId,
            table_rate: tableRate,
            customer_id: customerId
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success) {
                alert('Rate updated successfully!');
                $('.customer-playtime[data-table_id="' + tableId + '"]').append(
                    `<div class="existing-table" data-table_id="${tableId}" data-customer-id="${data.customer_id}">
                        <div class="club-customer-info">
                            <p>Rate: Rs. ${data.customer_rate}/min</p>
                        </div>
                        <div id="customer-info-container"></div>
                        <div class="club-customer-contact">
                            <button class="green-button view-register-button">Register Customer</button>
                        </div>
                        <div class="club-customer-checks">
                            <p>Check In Time: <br> <span class="check-in-time">${data.customer_check_in_time || 'N/A'}</span></p>
                            <div class="club-customer-checks-arrow">&#8594;</div>
                            <p>Check Out Time: <br> <span class="check-out-time">${data.customer_check_out_time || 'N/A'}</span></p>
                        </div>
                        <div class="stopwatch">
                            <span class="stopwatch-time">00:00:00</span>
                            <p>Total Price: Rs. <span class="total-price">0.00</span></p>
                        </div>
                        <form class="check-in-form" data-table-id="${tableId}">
                            <input type="hidden" name="customer_id" value="${data.customer_id}">
                            <input type="hidden" name="customer_rate" value="${data.customer_rate}">
                            <input type="submit" value="Check In" class="table-button">
                        </form>
                        <form class="check-out-form" style="display: none;" data-table-id="${tableId}">
                            <input type="hidden" name="customer_id" value="${data.customer_id}">
                            <input type="submit" value="Check Out" class="table-button">
                        </form>
                        <div class="customer_canteen">
                            <button class="view-canteen green-button" style="display: none;" onclick="viewCanteen(${data.customer_id})">Canteen</button>
                        </div>
                        <div class="view-bill">
                            <button class="view-bill-button green-button" style="display: none;" onclick="viewBill(${data.customer_id})" data-table_id="${tableId}">View and Print Bill</button>
                        </div>
                        <div class="re-book">
                            <button class="re-book-button green-button" data-table_id="${tableId}" style="display: none;">Re-Book</button>
                        </div>
                    </div>`
                );
            } else {
                ('Error updating rate: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            console.log('Error: ' + error);
        }
    });
}



// ----------------------------------------------------------------
$(document).ready(function() {

    // Handle Check In Form
    $(document).on('submit', '.check-in-form', function(event) {
        event.preventDefault();
        $('.view-editTable-button').fadeOut();
        var $form = $(this);
        var $table = $form.closest('.existing-table'); // Get the closest table
        var tableRate = $form.find('input[name="customer_rate"]').val();
        
        performTableRateChange($form, $table, tableRate);

        function performTableRateChange($form, $table, tableRate) {
            $table.find('.check-in-form').hide(); // Hide check-in form for this table
            $table.find('.check-out-form').show(); // Show check-out form for this table

            const $stopwatch = $table.find('.stopwatch-time');
            const $totalPrice = $table.find('.total-price');
            const ratePerMin = parseFloat(tableRate);
            const startTime = Date.now();
            $form.data('startTime', startTime);

            const timerInterval = setInterval(() => {
                const elapsedTime = Date.now() - startTime;
                const formattedTime = new Date(elapsedTime).toISOString().substr(11, 8);
                $stopwatch.text(formattedTime);
                const elapsedMinutes = elapsedTime / 60000;
                const totalPrice = (elapsedMinutes * ratePerMin).toFixed(2);
                $totalPrice.text(totalPrice);
            }, 1000);
            $form.data('timerInterval', timerInterval);

            $.ajax({
                url: 'check_in.php',
                type: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        $table.find('.check-in-time').text(data.check_in_time);
                        $form.hide(); // Hide form after successful check-in
                    } else {
                        clearInterval(timerInterval);
                        alert('Error: ' + data.error);
                    }
                },
                error: function() {
                    clearInterval(timerInterval);
                    alert('Error: Unable to process request.');
                }
            });
        }
    });

    // Handle Check Out Form
    $(document).on('submit', '.check-out-form', function(event) {
        event.preventDefault();
        $('.view-editTable-button').fadeIn();
        const $form = $(this);
        const $table = $form.closest('.existing-table');
        const startTime = $table.find('.check-in-form').data('startTime');
        const elapsedTime = Date.now() - startTime;
        clearInterval($table.find('.check-in-form').data('timerInterval'));
        const formattedTime = new Date(elapsedTime).toISOString().substr(11, 8);
        const ratePerMin = parseFloat($table.find('.check-in-form').find('input[name="customer_rate"]').val());
        const elapsedMinutes = elapsedTime / 60000;
        const totalPrice = (elapsedMinutes * ratePerMin).toFixed(2);

        $table.find('.stopwatch-time').text(formattedTime);
        $table.find('.total-price').text(totalPrice);

        $.ajax({
            url: 'check_out.php',
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    $table.find('.check-out-time').text(data.check_out_time);
                    $form.hide();
                    $table.find('.view-canteen').hide();
                    $table.find('.view-bill-button').show();
                } else {
                    alert('Error: ' + data.error);
                }
            },
            error: function() {
                alert('Error: Unable to process request.');
            }
        });
    });
    
    // Handle View Register Button Click
    let currentTable; // Variable to store the current table context

// Handle View Register Button Click
$(document).on('click', '.view-register-button', function() {
    currentTable = $(this).closest('.existing-table'); // Store the current table context
    const customerId = currentTable.data('customer-id');
    if (customerId) {
        $('#customer_id').val(customerId); // Set the customer ID in the register form
        $('#registerModal').show(); // Show the modal when the button is clicked
    } else {
        alert('Customer ID is missing.');
    }
});

// Handle Register Form Submission
$('#registerForm').on('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting the default way

    $.ajax({
        url: 'register_customer.php', // URL of the PHP script to handle the request
        type: 'POST',
        data: $(this).serialize(), // Serialize form data including customer_id
        dataType: 'json', // Expect JSON response
        success: function(response) {
            if (response.success) {
                alert('Customer registered successfully!');
                $('#registerModal').hide(); // Hide the modal on success
                $('#registerForm')[0].reset(); // Reset form fields

                // Construct the HTML with all customer details
                const customerInfoHtml = `
                    <div class="club-customer-contact">
                        <p>Customer: ${response.customer_username}</p>
                        <p>Mobile: ${response.customer_phone}</p>
                        <p>Email: ${response.customer_email}</p>
                    </div>
                `;
                
                // Update the specific table with customer information
                currentTable.find('#customer-info-container').html(customerInfoHtml); // Adjust the selector as needed

                // Hide the register button only in the specific table
                currentTable.find('.view-register-button').fadeOut();
            } else {
                alert('Error: ' + response.error); // Show error message
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred: ' + xhr.responseText);
        }
    });
});
    // Close modal when the close button is clicked
    $(document).on('click', '.modal .close', function() {
        $(this).closest('.modal').hide(); // Hide the modal when the close button is clicked
    });

    // Close modal when clicking outside the modal content
    $(window).click(function(event) {
        if ($(event.target).is('.modal')) {
            $(event.target).hide(); // Hide the modal when clicking outside the modal content
        }
    });
});



$(document).ready(function() {

// Handle View Bill Button Click
$(document).on('click', '.view-bill-button', function() {
    const $table = $(this).closest('.existing-table');
    const tableId = $table.data('table_id'); // Get the table ID of the specific table

    // Hide all "Re-Book" buttons
    $('.re-book-button').hide();

    // Show the "Re-Book" button only for the specific table
    $table.find('.re-book-button').show();

    // Extract and display bill details
    const customerName = $table.find('#customer-info-container p:first').text().split(': ')[1];
    const customerMobile = $table.find('#customer-info-container p:eq(1)').text().split(': ')[1];
    const customerEmail = $table.find('.club-customer-contact p:last').text().split(': ')[1];
    const checkInTime = $table.find('.check-in-time').text();
    const checkOutTime = $table.find('.check-out-time').text();
    const playedTime = $table.find('.stopwatch-time').text();
    const rate = $table.find('input[name="customer_rate"]').val();
    const totalPrice = $table.find('.total-price').text();

    // Update modal content
    $('#billCustomerName').text(customerName);
    $('#billCustomerMobile').text(customerMobile);
    $('#billCustomerEmail').text(customerEmail);
    $('#billCheckInTime').text(checkInTime);
    $('#billCheckOutTime').text(checkOutTime);
    $('#billPlayedTime').text(playedTime);
    $('#billRate').text(rate);
    $('#billTotalPrice').text(totalPrice);

    // Show the bill modal
    $('#billModal').show();
});


// Close the modal
$('.close').click(function() {
    $('#billModal').hide();
});

// Close the modal when clicking outside of the modal content
$(window).click(function(event) {
    if ($(event.target).is('#billModal')) {
        $('#billModal').hide();
    }
});

function printBill() {
const { jsPDF } = window.jspdf;
const doc = new jsPDF();

doc.setFontSize(12);
doc.text("Bill Details", 10, 10);
doc.text(`Name: ${$('#billCustomerName').text()}`, 10, 20);
doc.text(`Mobile: ${$('#billCustomerMobile').text()}`, 10, 30);
doc.text(`Email: ${$('#billCustomerEmail').text()}`, 10, 40);
doc.text(`Check In: ${$('#billCheckInTime').text()}`, 10, 50);
doc.text(`Check Out: ${$('#billCheckOutTime').text()}`, 10, 60);
doc.text(`Played Time: ${$('#billPlayedTime').text()}`, 10, 70);
doc.text(`Rate per Min: Rs. ${$('#billRate').text()}`, 10, 80);
doc.text(`Total Price: Rs. ${$('#billTotalPrice').text()}`, 10, 90);

// Open the PDF in a new tab
const pdfDataUri = doc.output('dataurlnewwindow');
const printWindow = window.open(pdfDataUri);

// Trigger print and close the window after printing
printWindow.onload = function() {
    printWindow.print();
    printWindow.onafterprint = function() {
        printWindow.close();
    };
};
}

// Bind printBill function to the print button
$('#printBillButton').click(function() {
    printBill();
});


function printBill() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(12);
    doc.text("Bill Details", 10, 10);
    doc.text(`Name: ${$('#billCustomerName').text()}`, 10, 20);
    doc.text(`Mobile: ${$('#billCustomerMobile').text()}`, 10, 30);
    doc.text(`Email: ${$('#billCustomerEmail').text()}`, 10, 40);
    doc.text(`Check In: ${$('#billCheckInTime').text()}`, 10, 50);
    doc.text(`Check Out: ${$('#billCheckOutTime').text()}`, 10, 60);
    doc.text(`Played Time: ${$('#billPlayedTime').text()}`, 10, 70);
    doc.text(`Rate per Min: Rs. ${$('#billRate').text()}`, 10, 80);
    doc.text(`Total Price: Rs. ${$('#billTotalPrice').text()}`, 10, 90);

    // Open the PDF in a new tab
    const pdfDataUri = doc.output('dataurlnewwindow');
    const printWindow = window.open(pdfDataUri);

    // Trigger print and close the window after printing
    printWindow.onload = function() {
        printWindow.print();
        printWindow.onafterprint = function() {
            printWindow.close();
        };
    };
}

});
// -----------------------------------------------------------------------------------------------------------

// History Table
$(document).on('click', '.history-table', function(){
    var tableId = $(this).data('table_id'); // Get the table ID from the button's data attribute
    performActionOnTable(tableId);


// Function to perform actions related to History
function performActionOnTable(tableId) {
    $('#historyModal').show();

    $.ajax({
        url: "history_table.php",
        type: "POST",
        data: { table_id: tableId }, // Send the table ID as data
        success: function(history_data){
            if(history_data.indexOf('<table>') !== -1){ // Check if the response contains a table
                $('#modal-body').html(history_data); // Update modal content with the received data
            } else {
                $('#modal-body').html("<p>No Record</p>"); // Hide the modal on failure
            }
        },
        error: function(){
            alert("An error occurred while processing your request.");
            $('#historyModal').hide(); // Hide the modal on error
        }
    });
}

});
// Close the modal when the close button is clicked
$(document).on('click', '.close', function(){
    $('#historyModal').hide();
});

// Close the modal when clicking outside the modal content
$(window).on('click', function(event) {
    if ($(event.target).is('#historyModal')) {
        $('#historyModal').hide();
    }
});

// -----------------------------------------------------------------------------------------------------------


// Canteen 
// $(document).ready(function() {
//     // Show the canteen modal and populate dropdown
//     $(document).on('click', '.view-canteen', function() {
//     $('#canteenModal').show();

//     // Fetch inventory names and prices and populate dropdown
//     $.ajax({
//         url: 'fetch_canteen.php', // Ensure this path is correct
//         type: 'GET',
//         success: function(response) {
//             try {
//                 const inventoryData = JSON.parse(response);
//                 const $dropdown = $('#canteen_inventory_name');

//                 // Clear existing options
//                 $dropdown.empty();

//                 // Add default option
//                 $dropdown.append('<option value="">Select an item</option>');

//                 // Populate dropdown with fetched inventory names and display prices
//                 if (Array.isArray(inventoryData)) {
//                     $.each(inventoryData, function(index, item) {
//                         $dropdown.append(`<option value="${item.name}">${item.name} - Rs. ${item.price}</option>`);
//                     });
//                 } else if (inventoryData.error) {
//                     alert(inventoryData.error);
//                 }
//             } catch (error) {
//                 alert('Error: Unable to parse inventory data.');
//             }
//         },
//         error: function() {
//             alert('Error: Unable to fetch inventory data.');
//         }
//     });
// });


//    // Handle form submission
//    $('#canteen-form').on('submit', function(event) {
//     event.preventDefault();

//     $.ajax({
//         url: 'add_canteen_item.php',
//         type: 'POST',
//         data: $(this).serialize(),
//         success: function(response) {
//             const data = JSON.parse(response);

//             if (data.success) {
//                 // Create the new row dynamically
//                 const totalPrice = data.item_price * data.item_qty;
//                 const newRow = `
//                     <tr class="canteen-table-style">
//                         <td>${data.item_name}</td>
//                         <td>${data.item_qty}</td>
//                         <td>${data.item_price}</td> <!-- Display the price -->
//                         <td>${totalPrice}</td>
//                     </tr>
//                 `;

//                 // Append new row to the table
//                 $('#canteenModal table tbody').append(newRow);

//                 // Reset the form
//                 $('#canteen-form')[0].reset();
//             } else {
//                 alert('Error: ' + data.error);
//             }
//         },
//         error: function() {
//             alert('Error: Unable to process request.');
//         }
//     });
// });

//     // Close the modal
//     $('.close').click(function() {
//         $('#canteenModal').hide();
//     });

//     // Close the modal when clicking outside of the modal content
//     $(window).click(function(event) {
//         if ($(event.target).is('#canteenModal')) {
//             $('#canteenModal').hide();
//         }
//     });
// });







// Inventory 

// Handle Inventory Button Click
$(document).on('click', '.view-inventory-button', function() {
$('#inventoryModal').show();
});

// Close the modal
$('.close').click(function() {
$('#inventoryModal').hide();
});

// Close the modal when clicking outside of the modal content
$(window).click(function(event) {
if ($(event.target).is('#inventoryModal')) {
    $('#inventoryModal').hide();
}
});


// Inventory Table
$('#create_inventory').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
        url: 'inventory_create.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                let newRow = `
                    <tr>
                        <td>${data.inventory_id}</td>
                        <td>${data.inventory_name}</td>
                        <td>${data.inventory_price}</td>
                        <td>${data.inventory_qty}</td>
                    </tr>
                `;
                
                if ($('.inventory-list table').length === 0) {
                    $('.inventory-list').append(`
                        <table class="inventory-table">
                            <thead>
                                <tr>
                                    <th>S no.</th>
                                    <th>Inv Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${newRow}
                            </tbody>
                        </table>
                    `);
                } else {
                    $('.inventory-list table tbody').append(newRow);
                }

                $('#create_inventory')[0].reset();
            } else {
                alert('Error: ' + data.error);
            }
        },
        error: function() {
            alert('Error: Unable to process request.');
        }
    });
});

$(document).ready(function() {
    function loadInventory() {
        $.ajax({
            url: 'fetch_inventory.php',
            type: 'GET',
            success: function(response) {
                const data = JSON.parse(response);
                if (Array.isArray(data)) {
                    let rows = data.map(item => `
                        <tr>
                            <td>${item.inventory_id}</td>
                            <td>${item.inventory_name}</td>
                            <td>${item.inventory_price}</td>
                            <td>${item.inventory_qty}</td>
                        </tr>
                    `).join('');
                    
                    if ($('.inventory-list table').length === 0) {
                        $('.inventory-list').append(`
                            <table class="inventory-table">
                                <thead>
                                    <tr>
                                        <th>S no.</th>
                                        <th>Inv Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${rows}
                                </tbody>
                            </table>
                        `);
                    } else {
                        $('.inventory-list table tbody').html(rows);
                    }
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            },
            error: function() {
                alert('Error: Unable to fetch inventory.');
            }
        });
    }

    $(document).on('click', '.view-inventory-button', function() {
        loadInventory();
        $('#inventoryModal').show();
    });
});


// Expenses
// Handle form submission for adding expenses
$('#create_expense').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
        url: 'expense_create.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                let newRow = `
                    <tr>
                        <td>${data.expense_id}</td>
                        <td>${data.expense_name}</td>
                        <td>${data.expense_amount}</td>
                        <td>${data.expense_date}</td>
                    </tr>
                `;
                
                if ($('.expense-list table').length === 0) {
                    $('.expense-list').append(`
                        <table class="expense-table">
                            <thead>
                                <tr>
                                    <th>S no.</th>
                                    <th>Expense</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${newRow}
                            </tbody>
                        </table>
                    `);
                } else {
                    $('.expense-list table tbody').append(newRow);
                }

                $('#create_expense')[0].reset();
            } else {
                alert('Error: ' + data.error);
            }
        },
        error: function() {
            alert('Error: Unable to process request.');
        }
    });
});

$(document).ready(function() {
    function loadExpense() {
        $.ajax({
            url: 'fetch_expense.php',
            type: 'GET',
            success: function(response) {
                const data = JSON.parse(response);
                if (Array.isArray(data)) {
                    let rows = data.map(item => `
                        <tr>
                            <td>${item.expense_id}</td>
                            <td>${item.expense_name}</td>
                            <td>${item.expense_amount}</td>
                            <td>${item.expense_date}</td>
                        </tr>
                    `).join('');
                    
                    if ($('.expense-list table').length === 0) {
                        $('.expense-list').append(`
                            <table class="expense-table">
                                <thead>
                                    <tr>
                                        <th>S no.</th>
                                        <th>Expense</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${rows}
                                </tbody>
                            </table>
                        `);
                    } else {
                        $('.expense-list table tbody').html(rows);
                    }
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            },
            error: function() {
                alert('Error: Unable to fetch expenses.');
            }
        });
    }

    // Show expense modal and load data when the button is clicked
    $(document).on('click', '.view-expense-button', function() {
        loadExpense();  // Load expenses instead of inventory
        $('#expenseModal').show();
    });

    // Close the modal when the close button is clicked
    $(document).on('click', '.modal .close', function() {
        $('#expenseModal').hide();
    });

        // Close the modal when clicking outside of the modal content
        $(window).click(function(event) {
        if ($(event.target).is('#expenseModal')) {
            $('#expenseModal').hide();
        }
    });
    
});


// Closing modal
$(document).ready(function() {
    function loadClosingDetails() {
        $.ajax({
            url: 'fetch_closing.php',
            type: 'GET',
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    const details = data.data;

                    // Format numbers to two decimal places
                    const formattedSnookerBillardSales = parseFloat(details.snooker_billard_sales).toFixed(2);
                    const formattedCanteenSales = parseFloat(details.canteen_sales).toFixed(2);
                    const formattedTotalSales = parseFloat(details.total_sales).toFixed(2);
                    const formattedTotalExpense = parseFloat(details.total_expense).toFixed(2);
                    const formattedProfit = parseFloat(details.profit).toFixed(2);

                    $('#closing-items-table tbody').html(`
                        <tr>
                            <td>${formattedSnookerBillardSales}</td>
                            <td>${formattedCanteenSales}</td>
                            <td>${formattedTotalSales}</td>
                            <td>${formattedTotalExpense}</td>
                            <td>${formattedProfit}</td>
                        </tr>
                    `);
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            },
            error: function() {
                alert('Error: Unable to fetch closing details.');
            }
        });
    }

    $(document).on('click', '.view-closing-button', function() {
        loadClosingDetails();
        $('#closingModal').show();
    });

    $(document).on('click', '.modal .close', function() {
        $('#closingModal').hide();
    });

    $(window).click(function(event) {
        if ($(event.target).is('#closingModal')) {
            $('#closingModal').hide();
        }
    });
});

document.getElementById('printButton').addEventListener('click', function() {
    // Create a new window for printing
    const printWindow = window.open('', '', 'height=600,width=800');

    // Get the table HTML
    const tableHTML = document.getElementById('closing-items-table').outerHTML;

    // Write the HTML for the print window
    printWindow.document.open();
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Closing Details</title>
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                }
                th {
                    background-color: #f2f2f2;
                }
                @media print {
                    #printButton {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <h2>Closing Details</h2>
            ${tableHTML}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
});


// Live Search

$(document).ready(function() {
    $('#search-customer').on("keyup", function() {
      var searchTerm = $(this).val();
      $.ajax({
        url: "ajax-customer-search.php",
        type: "POST",
        data: { search: searchTerm },
        success: function(response) {
          if (response.trim().length > 0) {
            $("table").html(response);
          } else {
            $("table").html("<h4>No Record Found</h4>");
          }
        },
        error: function() {
          $("table").html("<h4>Error: Unable to process request.</h4>");
        }
      });
    });
  });


// Shift closing

// Shift Creation
$(window).on('load', function() {
    $.ajax({
        url: "add_shift.php",
        type: "POST",
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                console.log(response.message);
                alert("Shift started successfully!");
                $('.start-new-shift').hide();
                $('.view-shift-closing').show();
            } else {
                console.error(response.message);
                alert("Error: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("An error occurred while starting the shift. Please try again.\nError details: " + xhr.responseText);
        }
    });
});

$('.start-new-shift').on('click', function(){
    $.ajax({
        url: "add_shift.php",
        type: "POST",
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                console.log(response.message);
                alert("Shift started successfully!");
                $('.start-new-shift').hide();
                $('.view-shift-closing').show();
            } else {
                console.error(response.message);
                alert("Error: " + response.message);
                // $('.toast success-message').show()
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("An error occurred while starting the shift. Please try again.\nError details: " + xhr.responseText);
        }
    });
});

// Shift Details Veiwing

$(document).on('click', '.view-shift-closing', function() {
    // Show the shift closing modal
    $('#shiftModal').show();

    // Function to load shift details
    function loadShiftDetails() {
        $.ajax({
            url: 'fetch_shift_closing.php',
            type: 'GET',
            success: function(response) {
                const data = JSON.parse(response); // Parse the JSON response
                if (Array.isArray(data)) {
                    let rows = data.map(item => {
                        // Determine if the shift is closed
                        const isClosed = item.close_time !== null;
                        
                        return `
                            <tr>
                                <td>${item.shift_id}</td>
                                <td>${item.opening_balance}</td>
                                <td>${item.closing_balance}</td>
                                <td>${item.total_collection}</td>
                                <td>${item.open_by}</td>
                                <td>${item.close_by}</td>
                                <td>${item.open_time}</td>
                                <td>${isClosed ? item.close_time : ''}</td>
                                <td>${!isClosed ? '<button class="close-shift green-button">Close Shift</button>' : ''}</td>
                            </tr>
                        `;
                    }).join('');

                    // Check if table exists; if not, create it
                    if ($('#shift-closing-body table').length === 0) {
                        $('#shift-closing-body').append(`
                            <table>
                                <thead>
                                    <tr>
                                        <th>Shift no.</th>
                                        <th>Opening Balance</th>
                                        <th>Closing Balance</th>
                                        <th>Total Collection</th>
                                        <th>Open By</th>
                                        <th>Close By</th>
                                        <th>Open Time</th>
                                        <th>Close Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${rows}
                                </tbody>
                            </table>
                        `);
                    } else {
                        $('#shift-closing-body table tbody').html(rows);
                    }
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            },
            error: function() {
                alert('Error: Unable to fetch shift details.');
            }
        });
    }

    loadShiftDetails();
});

// Shift Close
$(document).on('click', '.close-shift', function() {
    const $row = $(this).closest('tr');
    const shiftId = $row.find('td').eq(0).text(); // Adjust to get the shift ID correctly

    $.ajax({
        url: 'close_shift.php',
        type: 'POST',
        data: { shift_id: shiftId },
        success: function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                alert(data.success);
                // Update the row directly with the new close_time
                $row.find('td').eq(7).text(new Date().toLocaleString()); // Adjust index to match 'Close Time' column
                $row.find('.close-shift').fadeOut(); // Hide the button after closing
                $('#shiftModal').hide();
                $('.view-shift-closing').hide();
                $('.start-new-shift').show();

            } else {
                alert('Error: ' + data.error);
            }
        },
        error: function() {
            alert('Error: Unable to close the shift.');
        }
    });
});

// Close the modal
$('.close').click(function() {
    $('#shiftModal').hide();
});

// Close the modal when clicking outside of the modal content
$(window).click(function(event) {
    if ($(event.target).is('#shiftModal')) {
        $('#shiftModal').hide();
    }
});



</script>


</body>
</html>
