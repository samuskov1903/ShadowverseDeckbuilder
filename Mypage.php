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
if (isset($_POST['Bruger_ID'])) {
    $_SESSION['Bruger_ID'] = $_POST['Bruger_ID']; // Store the variable in the session
}

?>
<!DOCTYPE html>
<html lang="da">

<head>
    <title>Opret_bruger</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php
    $sql = "SELECT Bruger_ID FROM brugere where Bruger_ID = '$Bruger_ID'";
jjjjjjjjj    ?>
</head>
