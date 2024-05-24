<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="employee.css">
</head>
<body>
    <?php include "header.php"; ?>

    <!-- Main content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">New Employee</h3>
                        </div>
                        <div class="card-body">
                            <form action="employee-new.php" method="post">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom:</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom:</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Telephone:</label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="services">Services:</label>
                                    <select id="services" name="services[]" class="form-control" multiple required>
                                        <?php
                                        $conn = new mysqli("localhost", "root", "", "salon");
                                        if ($conn->connect_error) {
                                            die("Connection failed: " . $conn->connect_error);
                                        }
                                        $sql = "SELECT idservice, designation FROM service";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['idservice'] . "'>" . $row['designation'] . "</option>";
                                        }
                                        $conn->close();
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Ajouter l'employé</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'database.php';  // Ensure this path is correct
        $conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $services = $_POST['services'];
        
        // Assuming salon ID is handled dynamically or statically defined here
        $idsalon = 4; // Ensure this ID exists in your `salon` table

        $sql = "INSERT INTO employe (idsalon, nom, prenom, email, telephone, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $idsalon, $nom, $prenom, $email, $telephone, $password);
        if ($stmt->execute()) {
            $last_id = $stmt->insert_id;
            $stmt->close();

            $sql = "INSERT INTO employe_service (idemploye, idservice) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($services as $idservice) {
                $stmt->bind_param("ii", $last_id, $idservice);
                $stmt->execute();
            }
            $stmt->close();
            echo "<p>New employee added successfully!</p>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $conn->close();
    }
    ?>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
