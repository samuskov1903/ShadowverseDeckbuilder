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
session_start();

// Henter bruger_id fra session
$bruger_id = $_SESSION['bruger_id'];

// Tjekker om deckname og Class er sat i POST
if (isset($_POST['deckname']) && isset($_POST['Class'])) {
    // Gemmer deckname og Class i session
    $_SESSION['deckname'] = $_POST['deckname'];
    $_SESSION['Class'] = $_POST['Class'];
}

// Tjekker om deckname er sat i POST
if(isset($_POST['deckname'])) {
    // Tjekker om Class er sat til "None"
    if ($_POST['Class'] == "None") {
        echo "Vælg venligst en klasse til dit deck";
    } else {
        // Henter deckname og Class fra POST
        $deckname = $_POST['deckname'];
        $Class = $conn->real_escape_string($_POST['Class']);

        // SQL forespørgsel til at hente Klasse_ID
        $sql = "SELECT Klasse_ID FROM klasse WHERE Navn = '$Class'";
        $result = $conn->query($sql);

        // Tjekker om der er resultater
        if ($result->num_rows > 0) {
            // Henter Klasse_ID
            while ($row = $result->fetch_assoc()) {
                $klasse_id = $row["Klasse_ID"];
            }
        } else {
            echo "Fejl: Klasse ikke fundet";
            exit;
        }

        // SQL forespørgsel til at indsætte nyt deck
        $sql = "INSERT INTO decks (Navn, Klasse_ID, Bruger_ID) VALUES ('$deckname', '$klasse_id','$bruger_id')";
        if ($conn->query($sql) === TRUE) {
            echo "Deck oprettet succesfuldt";

            // Henter id for sidst indsatte række
            $deck_id = $conn->insert_id;

            // Omdirigerer til Deckbuilder.php med deck_id som en forespørgselsparameter
            header("Location: Deckbuilder.php?deck_id=$deck_id");
            exit;
        } else {
            echo "Fejl: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="da">

<head>
    <title>Opret deck</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Opret deck</h1>
    <form method="POST">
        <label>
            <input type="text" name="deckname" placeholder="Deckname">
        </label> <br>
        Vælg venligst en klasse til dit deck: <br>
        <label>
            <select name="Class">
                <option value="None">Ingen</option>
                <option value="Neutral">Neutral</option>
                <option value="Forestcraft">Forestcraft</option>
                <option value="Swordcraft">Swordcraft</option>
                <option value="Runecraft">Runecraft</option>
                <option value="Dragoncraft">Dragoncraft</option>
                <option value="Shadowcraft">Shadowcraft</option>
                <option value="Bloodcraft">Bloodcraft</option>
                <option value="Havencraft">Havencraft</option>
                <option value="Portalcraft">Portalcraft</option>
            </select>
        </label> <br>
        <input type="submit" value="Opret deck" >
    </form>
</body>

</html>