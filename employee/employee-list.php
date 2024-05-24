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
                            <h3 class="card-title">List Employees</h3>
                        </div>
                        <div class="card-body">
                            
                        <div class="container mt-4">
        <h2>Liste des Employés</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Employé</th>
                    <th>ID Salon</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Connexion à la base de données
                $conn = new mysqli("localhost", "root", "", "salon");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Requête SQL pour obtenir tous les employés
                $sql = "SELECT idemploye, idsalon, nom, prenom, telephone, email FROM employe";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Affichage des données de chaque ligne
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['idemploye']}</td>
                            <td>{$row['idsalon']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['prenom']}</td>
                            <td>{$row['telephone']}</td>
                            <td>{$row['email']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Aucun employé trouvé</td></tr>";
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

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
