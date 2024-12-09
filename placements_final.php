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

// nerbtou tou les etudian mratbin b la moyenes
$students_sql = "SELECT id, name, average FROM students ORDER BY average DESC";
$students_result = $conn->query($students_sql);

if (!$students_result) {
    die("Failed to fetch students: " . $conn->error);
}
// nerbtou tou les speciality
$specialties_sql = "SELECT id, name,places FROM specialties";
$specialties_result = $conn->query($specialties_sql);

if (!$specialties_result) {
    die("Failed to fetch specialties: " . $conn->error);
}

$specialties = [];
while ($row = $specialties_result->fetch_assoc()) {
    $specialties[$row['id']] = $row;
}

// njbdou les etudiants li ma3ndhoumch speciality
$unplaced_students = [];

while ($student = $students_result->fetch_assoc()) {
    $student_id = $student['id'];
    $student_average = $student['average'];

    
    // nerbtou les choix des etudiants 
    $choices_sql = "SELECT specialty_id FROM student_choices WHERE student_id = ? ORDER BY choice_order ASC";
    $choices_stmt = $conn->prepare($choices_sql);
    
    if (!$choices_stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }

    $choices_stmt->bind_param("i", $student_id);
    $choices_stmt->execute();
    $choices_result = $choices_stmt->get_result();

    $placed = false;

    while ($choice = $choices_result->fetch_assoc()) {
        $specialty_id = $choice['specialty_id'];

        if (isset($specialties[$specialty_id])) {
            $specialty = &$specialties[$specialty_id];

            if ($specialty['places'] > 0) {
                // n7atou les etudiant fi speciality 
                $assign_stmt = $conn->prepare("UPDATE students SET specialty = ? WHERE id = ?");
                
                if (!$assign_stmt) {
                    die("Failed to prepare assignment statement: " . $conn->error);
                }

                $assign_stmt->bind_param("si", $specialty['name'], $student_id);
                $assign_stmt->execute();

                // decrimontiw nb des places
                $specialty['places']--;

                $placed = true;
                break;
            }
        }
    }

    if (!$placed) {
        $unplaced_students[] = $student;
    }
}

// Notify admin if there are unplaced students
if (count($unplaced_students) > 0) {
    echo "Some students couldn't be placed due to a lack of available places in their chosen specialties:<br>";
    foreach ($unplaced_students as $unplaced_student) {
        echo "Student: " . $unplaced_student['name'] . " (Average: " . $unplaced_student['average'] . ")<br>";
    }
    echo "<br>Please assign them manually.";
} else {
    echo "All students have been successfully placed in their chosen specialties.";
}

$conn->close();
?>
