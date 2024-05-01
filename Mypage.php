<?php
// Opretter forbindelse til databasen
$servername = "localhost";
$username = "samuskov";
$password = ""; // ingen adgangskode
$dbname = "shadowversedata";

// Opretter forbindelse
$conn = new mysqli($servername, $username, $password, $dbname);

// Tjekker forbindelsen
if ($conn->connect_error) {
    die("Forbindelsen mislykkedes: " . $conn->connect_error);
}
// Starter en ny session
session_start();

// Henter bruger_id fra session
$bruger_id = $_SESSION['bruger_id'];

// SQL forespørgsel til at hente brugernavn
$sql = "SELECT Username FROM brugere WHERE Bruger_ID = '$bruger_id'";
$result = $conn->query($sql);

// Tjekker om der er resultater
if ($result->num_rows > 0) {
    // output data for hver række
    while ($row = $result->fetch_assoc()) {
        $bruger_navn = $row["Username"];
    }
}
?>
<!DOCTYPE html>
<html lang="da">

<head>
    <title>Min side</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <h1>Min side</h1>
    <br>
    <h2> <?php
    // Viser brugernavn
    echo $bruger_navn;
    ?> </h2>
    <br>
<h2> Dine decks</h2>
    <?php
    // Link til at oprette et nyt deck
    echo "<a href='Opret%20deck.php'>Opret et nyt deck</a><br><br>";

    // SQL forespørgsel til at hente decks
    $sql = "SELECT * FROM decks WHERE Bruger_ID = '$bruger_id'";
    $result = $conn->query($sql);

    // Tjekker om der er resultater
    if ($result === false) {
        echo "Fejl: " . $conn->error;
    } elseif ($result->num_rows > 0) {
        // output data for hver række
        while ($row = $result->fetch_assoc()) {
            echo "<a href='Deckbuilder.php?deck_id=" . $row["Deck_ID"] . "'> " . $row["Navn"] . "</a><br>";
        }
    } else {
        echo "Ingen decks fundet";
    }
?>
</head>