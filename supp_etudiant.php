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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $delete_sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT id, code, name, specialty, average FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supprimer les étudiants</title>
    <link rel="stylesheet" type="text/css" href="styles1.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        h2 {
            text-align: center;
            padding: 20px;
            color: #333;
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
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .remove-button {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 3px;
            cursor: pointer;
        }
        .remove-button:hover {
            background: #c0392b;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Supprimer les étudiants</h2>
        <table>
            <tr>
                <th>Code</th>
                <th>Le nom</th>
                <th>La Specialty</th>
                <th>La moyenne</th>
                <th>Les Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['code']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['specialty']; ?></td>
                    <td><?php echo $row['average']; ?></td>
                    <td>
                        <form action="supp_etudiant.php" method="post">
                            <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                            <input type="submit" class="remove-button" value="Remove">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
