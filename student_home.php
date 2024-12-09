<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "orientation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $average = $_POST['average'];
    $sql = "SELECT id, name, min_average FROM specialties";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table><tr><th>ID</th><th>Specialty</th><th>Minimum Average</th></tr>";
        while($row = $result->fetch_assoc()) {
            if ($average >= $row["min_average"]) {
                echo "<tr><td>" . $row["id"]. "</td><td>" . $row["name"]. "</td><td>" . $row["min_average"]. "</td></tr>";
            }
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
}

$conn->close();
?>
