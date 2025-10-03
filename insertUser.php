<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "actev1";
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $stmtcr = $conn->prepare("CREATE TABLE IF NOT EXISTS users(Gmail varchar(255), Password varchar(255))");
    $stmtcr->execute();
    $stmtcr->close();
?>