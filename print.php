<?php
// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// njibou les moyennes w les choix des étudiant
$sql = "SELECT id, name, specialty, average FROM students";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>Nom</th><th>Spécialité</th><th>Moyenne</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"]. "</td><td>" . $row["name"]. "</td><td>" . $row["specialty"]. "</td><td>" . $row["average"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 résultats";
}

$conn->close();
?>
