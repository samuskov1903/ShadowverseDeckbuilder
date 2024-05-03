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

// Henter deck id fra URL
if (isset($_GET['deck_id'])) {
    $deck_id = $_GET['deck_id'];
} else {
    echo "Ingen deck id angivet i URL.";
    exit;
}

// SQL forespørgsel til at vælge data fra databasen
$sql = "SELECT * FROM decks WHERE Deck_ID = '$deck_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data for hver række
    while ($row = $result->fetch_assoc()) {
        $navn = $row["Navn"];
    }
} else {
    echo "Ingen deck fundet med det angivne id.";
}
?>
<!DOCTYPE html>
<html lang="Da">
<head>
    <title>Deckbuilder</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Deckbuilder</h1>
    <h2> <?php
    echo $navn;
        ?> er blevet valgt</h2>
    <form method="POST">
        <h2> Kort i deck</h2>
        <?php
        $sql = "SELECT kort.*, kort_i_deck.Add_ID FROM kort
        INNER JOIN kort_i_deck ON kort.Kort_ID = kort_i_deck.Kort_ID
        WHERE kort_i_deck.Deck_ID = $deck_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data for hver række
            while($row = $result->fetch_assoc()) {
                echo "Navn: " . $row["Navn"]. " - Type: " . $row["Type"].'&nbsp';
                echo "<button type='submit' name='remove_card' value='".$row["Add_ID"]."'>REMOVE</button>";
                echo "<br>";
            }
        } else {
            echo "Ingen kort i deck.";
        }
        ?>
        <h2> Tilføj kort</h2>
        <?php
        $sql = "SELECT * FROM kort";
        $result = $conn->query($sql);
        //viser kort
        if ($result->num_rows > 0) {
            // output data for hver række
            while($row = $result->fetch_assoc()) {
                echo "Navn: " . $row["Navn"]. " - Type: " . $row["Type"].'&nbsp';
                echo "<button type='submit' name='add_card' value='".$row["Kort_ID"]."'>ADD</button>";
                echo "<br>";
            }
        } else {
            echo "0 resultater";
        }
        if (isset($_POST['add_card'])) {
    // Henter kort ID fra 'add_card' POST variabel
    $card_id = $_POST['add_card'];

    // SQL forespørgsel til at tælle antallet af det samme kort i deck
    $sql = "SELECT COUNT(*) AS card_count FROM kort_i_deck WHERE Deck_ID = '$deck_id' AND Kort_ID = '$card_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $card_count = $row['card_count'];

    // Tjekker om der allerede er 3 af det samme kort i deck
    if ($card_count >= 3) {
        echo "Du kan kun have 3 af det samme kort i en deck.";
    } else {
        // SQL forespørgsel til at tilføje kortet til deck
        $sql = "INSERT INTO kort_i_deck (Deck_ID, Kort_ID) VALUES ('$deck_id', '$card_id')";

        if ($conn->query($sql) === TRUE) {
            echo "Kort tilføjet succesfuldt";
            // Omdirigerer til den samme side
            header("Location: Deckbuilder.php?deck_id=$deck_id");
            exit;
        } else {
            echo "Fejl: " . $sql . "<br>" . $conn->error;
        }
    }
} // Dette er den manglende lukkende krølleparentes

if (isset($_POST['remove_card'])) {
    // Henter Add_ID fra 'remove_card' POST variabel
    $add_id = $_POST['remove_card'];

    // SQL forespørgsel til at fjerne kortet fra deck
    $sql = "DELETE FROM kort_i_deck WHERE Add_ID = '$add_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Kort fjernet succesfuldt";
        // Omdirigerer til den samme side
        header("Location: Deckbuilder.php?deck_id=$deck_id");
        exit;
    } else {
        echo "Fejl: " . $sql . "<br>" . $conn->error;
    }
}
        ?>
<!Sebastians kode>
        <h2> Recommended cards</h2>
        <?php
        $sql_deckid = $deck_id;
        $sql_deck = "SELECT Kort_ID FROM kort_i_deck WHERE Deck_ID = $sql_deckid"; // Henter alle kort fra bestemt deck.
        $result_deck = $conn->query($sql_deck);

        $kort_ids = array();
        while ($row_deck = $result_deck->fetch_assoc()) {
            $kort_ids[] = $row_deck['Kort_ID'];
        }

        $result_deck->close();

        $sql_korttrait = "SELECT Kort_ID FROM kort_har_trait WHERE Trait_ID IN (SELECT Trait_ID FROM kort_har_trait 
                WHERE Kort_ID IN (" . implode(",",$kort_ids) . "))"; // Finder alle kort med matchende traits.
        $sql_kortarc = "SELECT Kort_ID FROM kort_har_archetype WHERE Arc_ID IN (SELECT Arc_ID FROM kort_har_archetype 
            WHERE Kort_ID IN (" . implode(",",$kort_ids) . "))"; // Finder alle kort med matchende archetypes.
        $sql_kortkey = "SELECT Kort_ID FROM keywords_i_kort WHERE Keyword_ID IN (SELECT Keyword_ID FROM keywords_i_kort
            WHERE Kort_ID IN (" . implode(",",$kort_ids) . "))"; // Finder alle kort med matchende keywords.


        $sql_recommend = "SELECT Billede FROM kort WHERE Kort_ID IN
                               ($sql_korttrait UNION $sql_kortarc UNION $sql_kortkey)";
        $result_recommend = $conn->query($sql_recommend);
        $count = 0;

        echo "<table>";
        while($row_recommend = $result_recommend->fetch_assoc()) {
            if ($count%5==0){
                echo '<tr>';
            }
            echo "<td><img src='" . $row_recommend['Billede']."' style='width:74%; height:auto'></td>";
            $count++;
            if ($count%5==0) {
                echo '</tr>';
            }
        }
        echo "</table>";

        $result_recommend->close();
        $conn->close();
        ?>
    </form>
</body>
</html>