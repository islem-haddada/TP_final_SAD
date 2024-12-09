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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Existing Modules</title>
    <link rel="stylesheet" type="text/css" href="styles1.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Les Modules</h2>
    <table>
        <tr>
            <th> ID</th>
            <th> Nom du Module</th>
            <th>Specialty</th>
        </tr>
        <?php
        // Fetch modules and their associated specialties
        $sql = "SELECT modules.id, modules.name AS module_name, specialties.name AS specialty_name
                FROM modules
                JOIN specialties ON modules.specialty = specialties.id";
        $result = $conn->query($sql);

        // Loop through each module and display it
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['module_name'] . "</td>";
            echo "<td>" . $row['specialty_name'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>