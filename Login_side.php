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
if (isset($_POST['Bruger_ID'])) {
    $_SESSION['Bruger_ID'] = $_POST['Bruger_ID']; // Store the variable in the session
}
if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to check if email or username already exists
    $sql = "SELECT * FROM brugere WHERE Password = '$password' AND Username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        // output message if user doesn't exist
        echo "User doesn't exist";
    } else {
        $sql = "SELECT Bruger_ID FROM brugere WHERE Password = '$password' AND Username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $bruger_id = $row['Bruger_ID'];
            echo "User logged in successfully";
            $_SESSION['bruger_id'] = $bruger_id;
            echo $bruger_id;
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
        <input type="text" name="username" placeholder="Username"> <br>
        <input type="password" name="password" placeholder="Password"> <br>
        <input type="submit" value="Login" >
        <p>Don't have an account? <a href="Opret_bruger.php">Create one here</a></p>


    </form>
</body>
