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

//nzidou spec
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $name = htmlspecialchars($_POST['name']);
        $places = htmlspecialchars($_POST['places']);
       

        $stmt = $conn->prepare("INSERT INTO specialties (name, places) VALUES (?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("si", $name, $places);
        if ($stmt->execute()) {
            echo "Specialty added successfully";
            header("Location: admin_dashboard.php");
        } else {
            echo "Execute failed: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        $specialty_id = htmlspecialchars($_POST['specialty_id']);

        $stmt = $conn->prepare("DELETE FROM specialties WHERE id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("i", $specialty_id);
        if ($stmt->execute()) {
            echo "Specialty deleted successfully";
            header("Location: admin_dashboard.php");
        } else {
            echo "Execute failed: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>
