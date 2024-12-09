<!DOCTYPE html>
<html>
<head>
    <title>Results des Spécialités</title>
    <link rel="stylesheet" type="text/css" href="styles1.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
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
        input[type="checkbox"] {
            transform: scale(1.2);
        }
        input[type="submit"] {
            padding: 10px 20px;
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
    <script>
        function limitCheckboxes() {
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            let limit = 7;
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', function() {
                    let checkedCount = document.querySelectorAll('input[type="checkbox"]:checked').length;
                    if (checkedCount >= limit) {
                        checkboxes.forEach((checkbox) => {
                            if (!checkbox.checked) {
                                checkbox.disabled = true;
                            }
                        });
                    } else {
                        checkboxes.forEach((checkbox) => {
                            checkbox.disabled = false;
                        });
                    }
                });
            });
        }
        window.onload = limitCheckboxes;
    </script>
</head>
<body>
    <div class="container">
        <h2>Spécialités disponibles</h2>
        <form action="" method="post">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($_GET['student_id']); ?>">
            <table>
                <tr>
                    <th>Spécialité</th>
                    <th>Sélectionner</th>
                </tr>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "orientation";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT id, name FROM specialties";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td><input type='checkbox' name='specialty_ids[]' value='" . $row["id"] . "'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Aucune spécialité disponible.</td></tr>";
                }

                $conn->close();
                ?>
            </table>
            <input type="submit" value="Confirmer les Spécialités">
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $student_id = $_POST['student_id'];
        $specialty_ids = isset($_POST['specialty_ids']) ? $_POST['specialty_ids'] : [];

        if (!empty($specialty_ids)) {
            $conn->begin_transaction();

            try {
                // Remove any existing choices for the student
                $delete_sql = "DELETE FROM student_choices WHERE student_id = ?";
                $stmt = $conn->prepare($delete_sql);
                $stmt->bind_param("i", $student_id);
                $stmt->execute();
                $stmt->close();

                // Insert new choices
                $insert_sql = "INSERT INTO student_choices (student_id, specialty_id, choice_order) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);

                $choice_order = 1;
                foreach ($specialty_ids as $specialty_id) {
                    $stmt->bind_param("iii", $student_id, $specialty_id, $choice_order);
                    $stmt->execute();
                    $choice_order++;
                }

                $conn->commit();
                echo "<p>Les choix des spécialités ont été enregistrés avec succès.</p>";
            } catch (Exception $e) {
                $conn->rollback();
                echo "<p>Erreur lors de l'enregistrement des choix: " . $e->getMessage() . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p>Aucune spécialité sélectionnée.</p>";
        }

        $conn->close();
    }
    ?>
</body>
</html>
