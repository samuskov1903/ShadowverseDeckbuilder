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

// Tjekker om Bruger_ID er sat i POST
if (isset($_POST['Bruger_ID'])) {
    $_SESSION['Bruger_ID'] = $_POST['Bruger_ID']; // Gemmer variablen i sessionen
}

// Tjekker om brugernavn og adgangskode er sat i POST
if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL forespørgsel til at tjekke om e-mail eller brugernavn allerede findes
    $sql = "SELECT * FROM brugere WHERE Password = '$password' AND Username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        // output besked hvis bruger ikke findes
        echo "Bruger findes ikke";
    } else {
        $sql = "SELECT Bruger_ID FROM brugere WHERE Password = '$password' AND Username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $bruger_id = $row['Bruger_ID'];
            echo "Bruger logget ind succesfuldt";
            $_SESSION['bruger_id'] = $bruger_id;
            echo $bruger_id;
            // Omdirigerer til Mypage.php
            header("Location: Mypage.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="da">

<head>
    <title>Loginpage</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Login</h1>
    <form method="POST">
        <label>
            <input type="text" name="username" placeholder="Username">
        </label> <br>
        <label>
            <input type="password" name="password" placeholder="Password">
        </label> <br>
        <input type="submit" value="Login" >
        <p>Har du ikke en konto? <a href="Opret_bruger.php">Opret en her</a></p>
    </form>
</body>

</html>