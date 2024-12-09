<?php
// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ajouter  spec
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $name = $_POST['name'];
    $places = $_POST['places'];

    $sql = "INSERT INTO specialties (name, places) VALUES ('$name', '$places')";

    if ($conn->query($sql) === TRUE) {
        echo "Nouvelle filière ajoutée avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Modifier spec
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $places = $_POST['places'];

    $sql = "UPDATE specialties SET name='$name', places='$places' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Filière mise à jour avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Supprimer spec
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM specialties WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Filière supprimée avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
