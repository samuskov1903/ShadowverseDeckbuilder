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

// Tjekker om mail, brugernavn og adgangskode er sat i POST
if(isset($_POST['mail']) && isset($_POST['username']) && isset($_POST['password'])) {
    $mail = $_POST['mail'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL forespørgsel til at tjekke om e-mail eller brugernavn allerede findes
    $sql = "SELECT * FROM brugere WHERE Email = '$mail' OR Username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output besked hvis e-mail eller brugernavn allerede findes
        echo "E-mail eller brugernavn findes allerede";
    } else {
        // SQL forespørgsel til at indsætte ny bruger
        $sql = "INSERT INTO brugere (Email, Username, Password) VALUES ('$mail', '$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "Ny bruger oprettet succesfuldt";
            // Omdirigerer til Login_side.php
            header("Location: Login_side.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="da">

<head>
    <title>Opret_bruger</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Opretbruger</h1>
    <form method="POST">
        <input type="email" name="mail" placeholder="Email"><br>
        <input type="text" name="username" placeholder="Username"> <br>
        <input type="password" name="password" placeholder="Password"> <br>
        <input type="submit" value="Opret Bruger" >
        <p>Har du allerede en konto? <a href="Login_side.php">Log ind her</a></p>
    </form>
</body>

</html>