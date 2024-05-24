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

    
    <?php include "header.php" ?>
    
    
    <!-- Main content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Services list</h3>
                        </div>
                        <div class="card-body">
                        <div class="container mt-4">
        <h2>Services Disponibles</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Designation</th>
                    <th>Description</th>
                    <th>Tarif (€)</th>
                    <th>Durée (min)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Connexion à la base de données
                $conn = new mysqli("localhost", "root", "", "salon");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Exécution de la requête SQL pour obtenir les services
                $sql = "SELECT designation, description, tarif, duree FROM service";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Affichage des données de chaque ligne
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . htmlspecialchars($row["designation"]) . "</td><td>" . htmlspecialchars($row["description"]) . "</td><td>" . htmlspecialchars($row["tarif"]) . "</td><td>" . htmlspecialchars($row["duree"]) . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucun service disponible</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
                      
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
