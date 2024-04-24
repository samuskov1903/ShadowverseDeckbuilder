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
if (isset($_SESSION['deckname'])) {
    $navn = $_SESSION['deckname'];
}
// midlertidig variabler
$bruger_id = 1;
$_SESSION['bruger_id'] = $bruger_id;

// SQL query to select data from database
$sql = "SELECT Deck_ID FROM decks WHERE Navn = '$navn' AND Bruger_ID = '$bruger_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $deck_id = $row["Deck_ID"];
    }
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
    <form method="POST">
        <h2> Cards in deck</h2>
        <?php
        $sql = "SELECT kort.* FROM kort 
        INNER JOIN kort_i_deck ON kort.Kort_ID = kort_i_deck.Kort_ID 
        WHERE kort_i_deck.Deck_ID = $deck_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "id: " . $row["Kort_ID"]. " - Name: " . $row["Navn"]. " - Type: " . $row["Type"]. "<br>";
            }
        } else {
            echo "";
        }
        ?>
        <h2> Add cards</h2>
        <?php
        $sql = "SELECT * FROM kort";
        $result = $conn->query($sql);
        //display cards
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "id: " . $row["Kort_ID"]. " - Name: " . $row["Navn"]. " - Type: " . $row["Type"].'&nbsp';
                echo "<button type='submit' name='add_card' value='".$row["Kort_ID"]."'>ADD</button>";
                echo "<br>";
            }
        } else {
            echo "0 results";
        }
        if (isset($_POST['add_card'])) {
    // Get the card ID from the 'add_card' POST variable
    $card_id = $_POST['add_card'];

    // SQL query to add the card to the deck
    $sql = "INSERT INTO kort_i_deck (Deck_ID, Kort_ID) VALUES ('$deck_id', '$card_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Card added successfully";
        // Redirect to the same page
        header("Location: Deckbuilder.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

        ?>
    </form>
</body>
</html>