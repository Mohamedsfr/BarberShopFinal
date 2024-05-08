<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "salon.sql";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}

?>