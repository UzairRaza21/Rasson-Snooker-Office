<?php
session_start();
include "conn.php";
if (isset($_SESSION['club_email'])){
    header("location: dashboard.php");
}

?>

<?php

if (isset($_POST['login'])){

    $login_email = mysqli_real_escape_string($conn, $_POST['club_email']);
    $login_password = mysqli_real_escape_string($conn, $_POST['club_password']);

    $sql_login = "SELECT * FROM `club_user` WHERE `club_email` = '{$login_email}' AND `club_password` = '{$login_password}'";
    $result_login = mysqli_query($conn, $sql_login);
    $count = mysqli_num_rows($result_login);

    if ($count == 1){
        $row = mysqli_fetch_assoc($result_login);
        $_SESSION['club_email'] = $login_email;
        $_SESSION['clubname'] = $row['clubname'];
        // echo "<script>
        // startNewShift();
        // </script>";
        header("location: dashboard.php");
    }else{
        echo "<p class='invalid-password-error'>Invalid Username or Password</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cuebook.css">
    <link rel="shortcut icon" href="assets/logo/favicon/favicon.ico" type="image/x-icon">
    <title>Snooker Cue Book - Login</title>
</head>
<body class="login-body">
    
<div class="login-section">
    <div id="logo-pic">
        <img src="assets/logo/svg/logo-no-background.svg" alt="threads" width="180" height="60">
    </div>
    <h3>Login User</h3>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="club-email">Club Email</label><br>
        <input type="email" name="club_email" id="club-email" placeholder="Enter Email" required><br><br>
        
        <label for="club-password">Password</label><br>
        <input type="password" name="club_password" id="club-password" placeholder="Enter Password" required><br><br>

        <input type="submit" class="login-button" value="Log In" name="login">
    </form>

    <p>Don't have an Account? <a href="index.php">Sign up</a></p>
</div>

<script src="jquery.js"></script>

<script>
    // Shift Creation
// function startNewShift() {
//     $.ajax({
//         url: "add_shift.php",
//         type: "POST",
//         dataType: "json",
//         success: function(response) {
//             if (response.status === "success") {
//                 console.log(response.message);
//                 alert("Shift started successfully!");
//             } else {
//                 console.error(response.message);
//                 alert("Error: " + response.message);
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error("AJAX Error:", error);
//             alert("An error occurred while starting the shift. Please try again.\nError details: " + xhr.responseText);
//         }
//     });
// };
</script>
</body>
</html>