<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "actev1";
    
    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $stmtcr = $conn->prepare("CREATE DATABASE IF NOT EXISTS ActEv1");
    $stmtcr->execute();
    $stmtcr->close();
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    $stmtcr = $conn->prepare("CREATE TABLE IF NOT EXISTS users(Nombre varchar(255), Gmail varchar(255), Password varchar(255))");
    $stmtcr->execute();
    $stmtcr->close();

    $stmt = $conn->prepare('INSERT INTO users (Nombre, Gmail, Password) VALUES ("admin", "test@gmail.com", 1234)');
    $stmt->execute();
?>