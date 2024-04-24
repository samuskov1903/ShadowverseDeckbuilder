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

// SQL query to select data from database
$sql = "SELECT * FROM kort";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Deckbuilder</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Deckbuilder</h1>
    <form method="POST">
        <h2> Cards in deck</h2>
        <?php
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "id: " . $row["Kort_ID"]. " - Name: " . $row["Navn"]. " - Type: " . $row["Type"]. "<br>";
            }
        } else {
            echo "0 results";
        }
        ?>
    </form>
</body>
</html>