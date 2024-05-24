<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prise de Rendez-Vous</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="employee/employee.css">
</head>
<body>
    <?php
    session_start();
    include "employee/header.php";
    $conn = new mysqli("localhost", "root", "", "salon");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $telephone = $_POST['telephone'];
        $dateRendezVous = $_POST['dateRendezVous'];
        $heureRendezVous = $_POST['heureRendezVous'];
        $services = $_POST['services'] ?? [];

        // Insertion du client
        $sql = "INSERT INTO client (nom, prenom, telephone) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nom, $prenom, $telephone);
        $stmt->execute();
        $last_idclient = $stmt->insert_id;
        $stmt->close();

        // Insertion du rendez-vous
        $sql = "INSERT INTO rendezvous (idclient, date, heure_debut) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $last_idclient, $dateRendezVous, $heureRendezVous);
        $stmt->execute();
        $last_idrendezvous = $stmt->insert_id;
        $stmt->close();

        // Insertion des services choisis
        $sql = "INSERT INTO choisir (idrendezvous, idservice) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($services as $idservice) {
            $stmt->bind_param("ii", $last_idrendezvous, $idservice);
            $stmt->execute();
        }
        $stmt->close();

        $_SESSION['services'] = [];
        foreach ($services as $idservice) {
            $stmt = $conn->prepare("SELECT designation, tarif, duree FROM service WHERE idservice = ?");
            $stmt->bind_param("i", $idservice);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($service = $result->fetch_assoc()) {
                    $_SESSION['services'][] = $service;
                }
            }
            $stmt->close();
        }

        $conn->close();
        header("Location: rendezvous-summary.php");
        exit();
    }
    ?>

    <div class="container mt-5">
        <h2>Prise de Rendez-Vous</h2>
        <form method="post">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom:</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom:</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone:</label>
                <input type="text" class="form-control" id="telephone" name="telephone" required>
            </div>
            <div class="mb-3">
                <label for="dateRendezVous" class="form-label">Date du rendez-vous:</label>
                <input type="date" class="form-control" id="dateRendezVous" name="dateRendezVous" required>
            </div>
            <div class="mb-3">
                <label for="heureRendezVous" class="form-label">Heure du rendez-vous:</label>
                <input type="time" class="form-control" id="heureRendezVous" name="heureRendezVous" required>
            </div>
            <div class="mb-3">
                <h4>Services disponibles :</h4>
                <?php
                $result = $conn->query("SELECT * FROM service");
                while($row = $result->fetch_assoc()) {
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="services[]" id="service<?= $row['idservice'] ?>" value="<?= $row['idservice'] ?>">
                    <label class="form-check-label" for="service<?= $row['idservice'] ?>">
                        <?= htmlspecialchars($row['designation']) ?> (<?= htmlspecialchars($row['tarif']) ?>€ - <?= htmlspecialchars($row['duree']) ?>min)
                    </label>
                </div>
                <?php
                }
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Prendre rendez-vous</button>
        </form>
    </div>
</body>
</html>
