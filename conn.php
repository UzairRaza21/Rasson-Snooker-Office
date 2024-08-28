<?php
$host = "localhost";
$user = "root";
$pw = "";
$db = "cue_book";

$conn = mysqli_connect($host, $user, $pw, $db) or die ("Connection failed :" . mysqli_connect_error());
?>