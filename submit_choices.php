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

$student_id = $_SESSION['student_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['specialty_ids'])) {
    $specialty_ids = $_POST['specialty_ids'];

    // Clear previous choices
    $stmt = $conn->prepare("DELETE FROM student_choices WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->close();

    // nzidou choice jdid
    $choice_order = 1;
    foreach ($specialty_ids as $specialty_id) {
        $stmt = $conn->prepare("INSERT INTO student_choices (student_id, specialty_id, choice_order) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $student_id, $specialty_id, $choice_order);
        $stmt->execute();
        $choice_order++;
    }

    header("Location: student_profil.php");
    exit();
}

$conn->close();
?>
