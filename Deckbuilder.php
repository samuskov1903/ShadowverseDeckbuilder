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

// Get the deck id from the URL
if (isset($_GET['deck_id'])) {
    $deck_id = $_GET['deck_id'];
} else {
    echo "No deck id provided in the URL.";
    exit;
}

// SQL query to select data from database
$sql = "SELECT * FROM decks WHERE Deck_ID = '$deck_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $navn = $row["Navn"];
    }
} else {
    echo "No deck found with the provided id.";
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
        ?> has been selected</h2>
    <form method="POST">
        <h2> Cards in deck</h2>
        <?php
        $sql = "SELECT kort.*, kort_i_deck.Add_ID FROM kort
        INNER JOIN kort_i_deck ON kort.Kort_ID = kort_i_deck.Kort_ID
        WHERE kort_i_deck.Deck_ID = $deck_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "id: " . $row["Kort_ID"]. " - Name: " . $row["Navn"]. " - Type: " . $row["Type"].'&nbsp';
                echo "<button type='submit' name='remove_card' value='".$row["Add_ID"]."'>REMOVE</button>";
                echo "<br>";
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

    // SQL query to count the number of the same card in the deck
    $sql = "SELECT COUNT(*) AS card_count FROM kort_i_deck WHERE Deck_ID = '$deck_id' AND Kort_ID = '$card_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $card_count = $row['card_count'];

    // Check if there are already 3 of the same card in the deck
    if ($card_count >= 3) {
        echo "You can only have 3 of the same card in a deck.";
    } else {
        // SQL query to add the card to the deck
        $sql = "INSERT INTO kort_i_deck (Deck_ID, Kort_ID) VALUES ('$deck_id', '$card_id')";

        if ($conn->query($sql) === TRUE) {
            echo "Card added successfully";
            // Redirect to the same page
            header("Location: Deckbuilder.php?deck_id=$deck_id");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} // This is the missing closing brace

if (isset($_POST['remove_card'])) {
    // Get the Add_ID from the 'remove_card' POST variable
    $add_id = $_POST['remove_card'];

    // SQL query to remove the card from the deck
    $sql = "DELETE FROM kort_i_deck WHERE Add_ID = '$add_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Card removed successfully";
        // Redirect to the same page
        header("Location: Deckbuilder.php?deck_id=$deck_id");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
        ?>
    </form>
</body>
</html>