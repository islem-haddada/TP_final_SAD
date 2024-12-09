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

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$stmt = $conn->prepare("SELECT code, name, address, specialty, average, photo FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT sc.specialty_id, sp.name, sc.choice_order FROM student_choices sc JOIN specialties sp ON sc.specialty_id = sp.id WHERE sc.student_id = ? ORDER BY sc.choice_order");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$choices_result = $stmt->get_result();
$choices = $choices_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil de l'Étudiant</title>
    <link rel="stylesheet" type="text/css" href="styles1.css">
    <style>
      
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            background-color: white;
            width: 60%;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #333;
            font-size: 32px;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .nav-buttons {
            margin-top: 30px;
            text-align: center; /* Ensures buttons are centered */
        }

        .nav-buttons a {
            text-decoration: none;
            background: #007BFF;
            color: white;
            padding: 15px 30px;
            font-size: 20px;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%; /* Make buttons full width */
            max-width: 300px; /* Limit the maximum width */
            display: block; /* Ensure buttons stack vertically */
            margin: 15px auto; /* Center the buttons and provide space between them */
        }

        .nav-buttons a:hover {
            background: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .nav-buttons a:active {
            transform: translateY(0);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .container {
                width: 80%;
            }

            .nav-buttons a {
                width: 80%; /* Adjust width for small screens */
                margin: 10px auto; /* Stack the buttons vertically with spacing */
            }
        }
 
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            text-align: left;
            color: #555;
            margin: 10px 0;
            font-size: 16px;
        }
        h3 {
            color: #333;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <a class="home-button" href="Home.html">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($student['photo']); ?>" alt="Student Photo">
        </a>
        <h2>Student Profile </h2>
        <p><strong>Code:</strong> <?php echo htmlspecialchars($student['code']); ?></p>
        <p><strong>Nom:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
        <p><strong>Adresse:</strong> <?php echo htmlspecialchars($student['address']); ?></p>
        <p><strong>Spécialité:</strong> <?php echo htmlspecialchars($student['specialty']); ?></p>
        <p><strong>Moyenne:</strong> <?php echo htmlspecialchars($student['average']); ?></p>
        <h3> Spécialités Choisis</h3>
        <table>
            <tr>
                <th>Ordre</th>
                <th>Spécialité</th>
                <th>Action</th>
            </tr>
            <?php
            if (count($choices) > 0) {
                foreach ($choices as $choice) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($choice['choice_order']) . "</td>";
                    echo "<td>" . htmlspecialchars($choice['name']) . "</td>";
                    echo "<td><a href='changer_placement.php?choice_id=" . $choice['specialty_id'] . "&direction=up'>↑</a> <a href='changer_placement.php?choice_id=" . $choice['specialty_id'] . "&direction=down'>↓</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Aucun choix n'a été effectué.</td></tr>";
            }
            ?>
        </table>
        <h3>Ajouter des Spécialités</h3>
        <form action="update_choices.php" method="post">
            <?php
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $average = $student['average'];
            $sql = "SELECT id, name FROM specialties";
            $stmt = $conn->prepare($sql);
            
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<label><input type='checkbox' name='specialty_ids[]' value='" . $row["id"] . "'>" . $row["name"] . "</label><br>";
                }
            } else {
                echo "<p>Aucune spécialité disponible pour votre moyenne.</p>";
            }
            $stmt->close();
            $conn->close();
            ?>
            <input type="submit" value="Ajouter les Spécialités">
        </form>
    </div>
</body>
</html>
