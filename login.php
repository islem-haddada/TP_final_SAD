<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "orientation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//nkomparou username m3a tableau ta3 etudiant
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);

    // narbtou ma3loumat m3a DB 
    $stmt = $conn->prepare("SELECT id FROM students WHERE code = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($student_id);
        $stmt->fetch();
        $_SESSION['student_id'] = $student_id;
        header("Location: student_profil.php");
        exit();
    } else {
        echo "Invalid credentials";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="styles1.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 20px;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .nav-buttons {
            display: flex; /* Flexbox pour afficher les boutons côte à côte */
            justify-content: center; /* Centrer les boutons horizontalement */
            gap: 20px; /* Espace entre les boutons */
            padding: 20px;
        }
        .nav-buttons a {
            text-decoration: none;
            background: #007BFF;
            color: #fff;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 5px;
            transition: background 0.3s ease, transform 0.2s ease;
            width: 200px; /* Largeur fixe pour les boutons */
            text-align: center; /* Centrer le texte à l'intérieur du bouton */
        }
        .nav-buttons a:hover {
            background: #0056b3;
            transform: translateY(-3px);
        }
        .nav-buttons a:active {
            transform: translateY(0);
        }
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
            .nav-buttons {
                flex-direction: column; /* Empiler les boutons verticalement sur les écrans plus petits */
                gap: 10px; /* Réduire l'écart entre les boutons */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Login</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
