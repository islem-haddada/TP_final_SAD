<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "orientation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $stmt = $conn->prepare("SELECT name, places, min_average FROM specialties WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $specialty = $result->fetch_assoc();
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = htmlspecialchars($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $places = htmlspecialchars($_POST['places']);
    $min_average = htmlspecialchars($_POST['min_average']);

    $stmt = $conn->prepare("UPDATE specialties SET name = ?, places = ?, min_average = ? WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sisi", $name, $places, $min_average, $id);
    if ($stmt->execute()) {
        echo "Specialty updated successfully";
        header("Location: Tableau_De_Board.php");
        exit();
    } else {
        echo "Execute failed: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Specialty</title>
    <link rel="stylesheet" type="text/css" href="styles1.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #00c6ff, #0072ff);
    margin: 0;
    padding: 0;
}

.container {
    width: 50%;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

h2 {
    text-align: center;
    font-style: bold ;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin: 10px 0 5px;
    color: #555;
}

input[type="text"], input[type="password"] {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    padding: 10px;
    background: linear-gradient(to right, #00c6ff, #0072ff);
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background: linear-gradient(to right, #00c6ff, #0072ff);
}

a {
    display: block;
    margin: 10px 0;
    text-align: center;
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>?odifier Specialte</h2>
        <form action="update_specialty.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <label for="name">Specialty Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($specialty['name']); ?>"><br>
            <label for="places"> Number of Places:</label>
            <input type="text" id="places" name="places" value="<?php echo htmlspecialchars($specialty['places']); ?>"><br>
            <label for="min_average">Minimum ,oyenene:</label>
            <input type="text" id="min_average" name="min_average" value="<?php echo htmlspecialchars($specialty['min_average']); ?>"><br>
            <input type="submit" value="Update Specialty">
        </form>
    </div>
</body>
</html>
