<?php
session_start();
if (isset($_SESSION["employe"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>salon Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["click"])) {
           $raisonsocial = $_POST["raisonsocial"];
           $telephone = $_POST["telephone"];
           $adresse = $_POST["adresse"];
           //$passwordRepeat = $_POST["repeat_password"];
           
           //$passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($raisonsocial) OR empty($telephone) OR empty($adresse) ) {
            array_push($errors,"All fields are required");
           }
           //if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
           // array_push($errors, "Email is not valid");}
           //if (strlen($telephone)!==10) {
           // array_push($errors,"telephone is not valid");}
           //if ($password!==$passwordRepeat) {
            //array_push($errors,"Password does not match"); }
           require_once "database.php";
          /* $sql = "SELECT * FROM employe WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           }else{*/
            
            $sql = "INSERT INTO salon (raisonsocial, telephone, adresse) VALUES ( ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"sss",$raisonsocial, $telephone, $adresse);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>continue your registration.</div>";
            }else{
                die("Something went wrong");
            }
           }
          

        
        ?>
        <form action="salon.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="raisonsocial" placeholder="raisonsocial:">
            </div>
            <div class="form-group">
            <label for="phone">Enter a phone number:</label><br><br>
  <input type="tel" id="phone" name="phone" placeholder="0665224789" pattern="[0-9]{10}" required><br><br>
  <small>Format: 0665224789</small><br><br>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="adresse" placeholder="adresse:">
            </div>
            <!--<div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>-->
        </form>
        </br>
        <div class="form-btn">
                    <a href="registration.php">continue registration</a>
            </div>
        
        
    </div>
</body>
</html>