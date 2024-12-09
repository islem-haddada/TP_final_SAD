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
<html>
<head>
    <title>Tableau de board</title>
    <link rel="stylesheet" type="text/css" href="styles1.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            background: linear-gradient(to right, #00c6ff, #0072ff);
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
        form {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }
        input[type="text"], input[type="submit"], select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            background: #5A9;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: linear-gradient(to right, #00c6ff, #0072ff);
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
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: #333;
        }
        tr:nth-child(even) {
            background: linear-gradient(to right, #00c6ff, #0072ff);
        }
        .action-buttons form {
            display: inline;
        }
        .home-button, .module-button {
            display: block;
            width: 100%;
            text-align: center;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            border: none;
            cursor: pointer;
        }
        .home-button:hover, .module-button:hover {
            background: linear-gradient(to right, #00c6ff, #0072ff);
        }
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
        <h2>Tableau de board</h2>
        
        <!-- Add Specialty Form -->
        <form action="specialty_managment.php" method="post">
            <label for="name">Le nom du speciality :</label>
            <input type="text" id="name" name="name"><br>
            <label for="places">Numero de places :</label>
            <input type="text" id="places" name="places"><br>
            
            <input type="submit" name="add" value="ajouter Specialte">
        </form>

        <!-- Button to Navigate to Add Modules Page -->
        <form action="add_module.php" method="get">
            <input type="submit" class="module-button" value="ajouter Modules">
        </form>

        <!-- Display Existing Specialties -->
        <h2>Les Specialties</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Le nom</th>
                <th>Places</th>
                
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT id, name, places FROM specialties";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["places"] . "</td>";
                
                echo "<td class='action-buttons'>";
                echo "<form action='specialty_managment.php' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='specialty_id' value='" . $row["id"] . "'>";
                echo "<input type='submit' name='suprimer' value='suprimer'>";
                echo "</form>";
                echo "<form action='update_specialty.php' method='get' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<input type='submit' value='modifier'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <!-- Finalize Placements Button -->
        <form action="placements_final.php" method="post">
            <input type="submit" value="placements_final ">
        </form>
        <form action="supp_etudiant.php" method="post">
            <input type="submit" value="supprimer les etudiant">
        </form>

        <!-- Home Button -->
        <form action="Home.html" method="get">
            <input type="submit" class="home-button" value="Home">
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
