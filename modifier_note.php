<?php
// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// modifier la note
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $module_id = $_POST['module_id'];
    $grade = $_POST['grade'];

    $sql = "UPDATE grades SET grade='$grade' WHERE student_id='$student_id' AND module_id='$module_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Note mise à jour avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
