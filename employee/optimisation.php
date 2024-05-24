<?php
$conn = new mysqli("localhost", "root", "", "salon");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {
    $date = $_POST['date'];

    // Préparation de la requête SQL
    $stmt = $conn->prepare("SELECT rv.idrendezvous, cl.nom AS client_nom, cl.prenom AS client_prenom, srv.designation AS service, emp.nom AS employe_nom, emp.prenom AS employe_prenom, rv.heure_debut, ADDTIME(rv.heure_debut, SEC_TO_TIME(srv.duree * 60)) AS heure_fin FROM rendezvous rv JOIN client cl ON rv.idclient = cl.idclient JOIN choisir ch ON rv.idrendezvous = ch.idrendezvous JOIN service srv ON ch.idservice = srv.idservice JOIN employe_service es ON srv.idservice = es.idservice JOIN employe emp ON es.idemploye = emp.idemploye WHERE rv.date = ? ORDER BY rv.heure_debut");
    if (!$stmt) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affectation des Employés</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="employee.css"> <!-- Assurez-vous que le chemin est correct -->
</head>
<body>
    <?php include "header.php"; // Assurez-vous que le chemin est correct ?>

    <div class="container mt-5">
        <h1>Affectation Optimale des Employés</h1>
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="date" class="form-label">Choisir une date :</label>
                <input type="date" id="date" name="date" class="form-control" required>
                <button type="submit" class="btn btn-primary mt-3">Générer</button>
            </div>
        </form>

        <?php if (!empty($appointments)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Employé</th>
                        <th>Heure Début</th>
                        <th>Heure Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['client_nom']) . " " . htmlspecialchars($appointment['client_prenom']) ?></td>
                            <td><?= htmlspecialchars($appointment['service']) ?></td>
                            <td><?= htmlspecialchars($appointment['employe_nom']) . " " . htmlspecialchars($appointment['employe_prenom']) ?></td>
                            <td><?= $appointment['heure_debut'] ?></td>
                            <td><?= $appointment['heure_fin'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-info">Aucune donnée à afficher pour la date sélectionnée.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

