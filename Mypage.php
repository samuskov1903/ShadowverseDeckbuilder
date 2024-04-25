<?php
$servername = "localhost";
$username = "samuskov";
$password = ""; // no password
$dbname = "shadowversedata";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="da">

<head>
    <title>Opret_bruger</title>
    <link rel="stylesheet" type="text/css" href="style.css">

    <?php
    echo $username;
    ?>
</head>
