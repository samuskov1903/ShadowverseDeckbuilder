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
$bruger_id = $_SESSION['bruger_id'];
echo $bruger_id;
$sql = "SELECT Username FROM brugere WHERE Bruger_ID = '$bruger_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $bruger_navn = $row["Username"];
    }
}
?>
<!DOCTYPE html>
<html lang="da">

<head>
    <title>My page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <h1>My page</h1>
    <br>
    <h2> <?php
    echo $bruger_navn;
    ?> </h2>
    <br>
<h2> Your decks</h2>
    <?php
     echo "<a href='Opret%20deck.php'>Create a new deck</a><br><br>";
     $sql = "SELECT * FROM decks WHERE Bruger_ID = '$bruger_id'";
    $result = $conn->query($sql);
    if ($result === false) {
    echo "Error: " . $conn->error;
} elseif ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<a href='Deckbuilder.php?deck_id=" . $row["Deck_ID"] . "'> " . $row["Navn"] . "</a><br>";
        }
    } else {
        echo "No decks found";
    }
?>
</head>
