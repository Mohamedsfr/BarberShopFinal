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
                            <h3 class="card-title">Appointments list</h3>
                        </div>
                        <div class="card-body">
                            <?php
                            session_start();
                            include 'database.php'; // Ensure this path is correct

                            $conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Handle cancellation
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])) {
                                $idrendezvous = $_POST['idrendezvous'];
                                $sql = "UPDATE rendezvous SET statut = 'annulé' WHERE idrendezvous = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $idrendezvous);
                                $stmt->execute();
                                $stmt->close();
                            }

                            // Fetch appointments
                            $sql = "SELECT idrendezvous, idclient, date, heure_debut FROM rendezvous WHERE statut != 'annulé'";
                            $result = $conn->query($sql);

                            echo "<table class='table'>";
                            echo "<tr><th>ID</th><th>Client ID</th><th>Date</th><th>Start Time</th><th>Actions</th></tr>";

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . $row["idrendezvous"] . "</td><td>" . $row["idclient"] . "</td><td>" . $row["date"] . "</td><td>" . $row["heure_debut"] . "</td>";
                                    echo "<td><form method='POST'><input type='hidden' name='idrendezvous' value='" . $row['idrendezvous'] . "'><button type='submit' name='cancel' class='btn btn-danger'>Cancel</button></form></td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No appointments found</td></tr>";
                            }
                            echo "</table>";

                            $conn->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
