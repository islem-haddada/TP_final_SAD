<?php
session_start();

//conexion DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "orientation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    
    // verifi username w password  m3a table ta3 admin
    $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ? AND password = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['admin'] = true;
        header("Location: Tableau_De_Board.php");
        exit();
    } else {
        $error_message = "Invalid credentials. Please try again.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="styles1.css">
    <style>
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
            <script>
                setTimeout(function(){ window.location.href = 'admin_login.html'; }, 2000);
            </script>
        <?php endif; ?>
        <form action="admin_login.php" method="post">
            <label for="username"> Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password"> Password:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
