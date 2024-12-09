<!DOCTYPE html>
<html>
<head>
    <title>Gestion des étudiants</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
            text-align: left;
        }
        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 15px;
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
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 50%;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    text-align: center;
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

input[type="text"] {
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

    </style>
</head>
<body>
    <div class="container">
        <h2>Add new étudiant</h2>
        <form action="add_student.php" method="post" enctype="multipart/form-data">
            <label for="code">Code:</label>
            <input type="text" id="code" name="code" required><br>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="address">Adresse:</label>
            <input type="text" id="address" name="address"><br>
            <label for="specialty">Spécialité:</label>
            <input type="text" id="specialty" name="specialty"><br>
            <label for="average">Average:</label>
            <input type="text" id="average" name="average" required><br>
            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo"><br>
            <input type="submit" value="Ajouter">
        </form>
    </div>
</body>
</html>

<?php
//tconecti m3a DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "orientation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//post methode bah tajouti etudiant
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = htmlspecialchars($_POST['code']);
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);
    $specialty = htmlspecialchars($_POST['specialty']);
    $average = htmlspecialchars($_POST['average']);

    // تيليشارجي صورة
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo = file_get_contents($_FILES['photo']['tmp_name']);
    }

    // تاكد ادا كاين ايتيديون
    $stmt = $conn->prepare("SELECT id FROM students WHERE code = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($student_id);
        $stmt->fetch();
        header("Location: student_profil.php?student_id=$student_id");
        exit();
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO students (code, name, address, photo, specialty, average) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }        
        $stmt->bind_param("sssbss", $code, $name, $address, $photo, $specialty, $average);
        if ($photo) {
            $stmt->send_long_data(3, $photo);
        }

        if ($stmt->execute()) {
            header("Location: results.php?average=$average");
            exit();
        } else {
            echo "Erreur: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
