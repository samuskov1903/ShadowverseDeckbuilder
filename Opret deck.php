<?php
$servername = "localhost";
$username = "samuskov";
$password = ""; // no password
$dbname = "shadowversedata";

//test variables


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
session_start();
$bruger_id = $_SESSION['bruger_id'];
if (isset($_POST['deckname'])) {
    $_SESSION['deckname'] = $_POST['deckname']; // Store the variable in the session
}
if (isset($_POST['Class'])) {
    $_SESSION['Class'] = $_POST['Class']; // Store the variable in the session
}



if(isset($_POST['deckname'])) {
    if ($_POST['Class'] == "None") {
        echo "Please choose a class for your deck";
    } else {
        $deckname = $_POST['deckname'];
        $Class = $conn->real_escape_string($_POST['Class']);
        $sql = "SELECT Klasse_ID FROM klasse WHERE Navn = '$Class'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $klasse_id = $row["Klasse_ID"];
            }
        } else {
            echo "No results";
            exit;
        }

        // Check if Klasse_ID equals 0
        if ($klasse_id == 0 and $deckname) {
            echo "Error: Klasse_ID cannot be 0";
            exit;
        }
        $sql = "INSERT INTO decks (Navn, Klasse_ID, Bruger_ID) VALUES ('$deckname', '$klasse_id','$bruger_id')";
        if ($conn->query($sql) === TRUE) {
            echo "Deck created successfully";
            // Get the id of the last inserted row
            $deck_id = $conn->insert_id;
            // Redirect to the Deckbuilder.php with the deck id as a query parameter
            header("Location: Deckbuilder.php?deck_id=$deck_id");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
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
        <input type="text" name="deckname" placeholder="Deckname"> <br>
        Please choose a Class for your deck: <br>
        <select name="Class">
            <option value="None">None</option>
            <option value="Neutral">Neutral</option>
            <option value="Forestcraft">Forestcraft</option>
            <option value="Swordcraft">Swordcraft</option>
            <option value="Runecraft">Runecraft</option>
            <option value="Dragoncraft">Dragoncraft</option>
            <option value="Shadowcraft">Shadowcraft</option>
            <option value="Bloodcraft">Bloodcraft</option>
            <option value="Havencraft">Havencraft</option>
            <option value="Portalcraft">Portalcraft</option>
        </select> <br>
        <input type="submit" value="Create deck" >

        
    </form>
</body>

</html>
