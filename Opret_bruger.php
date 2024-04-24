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
if(isset($_POST['mail']) && isset($_POST['username']) && isset($_POST['password'])) {
    $mail = $_POST['mail'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to check if email or username already exists
    $sql = "SELECT * FROM brugere WHERE Email = '$mail' OR Username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output message if email or username already exists
        echo "Email or Username already exists";
    } else {
        $sql = "INSERT INTO brugere (Email, Username, Password) VALUES ('$mail', '$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "New user created successfully";
            // Redirect to the same page
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
        <p>Already have an account? <a href="Login_side.php">Login here</a></p>
    </form>
</body>