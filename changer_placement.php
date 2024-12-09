<?php 
 //conexion m3a DB
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "orientation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// tvirifi log in as stident 
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];


//get method beh tjiblna les utidiants et les choix
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['choice_id']) && isset($_GET['direction'])) {
    $choice_id = $_GET['choice_id'];
    $direction = $_GET['direction'];

    // njibou tartib ta3hem
    $stmt = $conn->prepare("SELECT choice_order FROM student_choices WHERE student_id = ? AND specialty_id = ?");
    $stmt->bind_param("ii", $student_id, $choice_id);
    $stmt->execute();
    $stmt->bind_result($current_order);
    $stmt->fetch();
    $stmt->close();

    if ($direction == 'up') {
        $new_order = $current_order - 1;
    } elseif ($direction == 'down') {
        $new_order = $current_order + 1;
    }

    // nvirifou ida tartib jdid s7i7
    $stmt = $conn->prepare("SELECT COUNT(*) FROM student_choices WHERE student_id = ? AND choice_order = ?");
    $stmt->bind_param("ii", $student_id, $new_order);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // nswapiw tqrtib m3a les choix
        $stmt = $conn->prepare("UPDATE student_choices SET choice_order = ? WHERE student_id = ? AND choice_order = ?");
        $stmt->bind_param("iii", $current_order, $student_id, $new_order);
        $stmt->execute();
        $stmt->close();
    }

    // modifi tartib 3la 7asseb choice 
    $stmt = $conn->prepare("UPDATE student_choices SET choice_order = ? WHERE student_id = ? AND specialty_id = ?");
    $stmt->bind_param("iii", $new_order, $student_id, $choice_id);
    $stmt->execute();
    $stmt->close();

    header("Location: student_profil.php");
    exit();
}

$conn->close();
?>
