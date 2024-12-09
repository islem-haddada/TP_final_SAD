<?php
//virifi ida admin ?
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
// conexion m3a DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "orientation";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//tajouti module
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_module'])) {
        $specialty_id = $_POST['specialty'];
        $module_name = $_POST['module_name'];
        $stmt = $conn->prepare("INSERT INTO modules (name, specialty) VALUES (?, ?)");
        $stmt->bind_param("si", $module_name, $specialty_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_module'])) {
        $module_id = $_POST['module_id'];
        $stmt = $conn->prepare("DELETE FROM modules WHERE id = ?");
        $stmt->bind_param("i", $module_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Modules</title>
    <link rel="stylesheet" type="text/css" href="styles1.css">
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(to right, #00c6ff, #0072ff);; }
        .container { width: 80%; margin: auto; overflow: hidden; }
        .container h2 { text-align: center; padding: 20px; color: #333; }
        .form-section { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label, select, input[type="text"], input[type="submit"] {
            display: block; width: 100%; margin: 10px 0; padding: 10px;
            border: 1px solid #ccc; border-radius: 3px;
        }
        input[type="submit"] { background: #5A9; background: linear-gradient(to right, #00c6ff, #0072ff);; border: none; cursor: pointer; }
        input[type="submit"]:hover { background: linear-gradient(to right, #00c6ff, #0072ff); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #f2f2f2; color: #333; }
        .delete-button { background: #d9534f; color: #fff; cursor: pointer; border: none; padding: 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>ajouter et supprimer des modules </h2>
        
        <div class="form-section">
            <form method="post">
                <label for="specialty">Choisis Specialte:</label>
                <select name="specialty" id="specialty">
                    <?php
                    $result = $conn->query("SELECT id, name FROM specialties");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
                
                <label for="module_name">Module Name:</label>
                <input type="text" id="module_name" name="module_name" required>
                
                <input type="submit" name="add_module" value="Add Module">
            </form>
        </div>

        <h2>Les  Modules</h2>
        <table>
            <tr>
                <th>Module ID</th>
                <th> Nom du Module</th>
                <th>lq specialite</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT modules.id, modules.name AS module_name, specialties.name AS specialty_name
                    FROM modules
                    JOIN specialties ON modules.specialty = specialties.id";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['module_name'] . "</td>";
                echo "<td>" . $row['specialty_name'] . "</td>";
                echo "<td>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='module_id' value='" . $row['id'] . "'>
                        <input type='submit' name='delete_module' class='delete-button' value='Delete'>
                    </form>
                </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
