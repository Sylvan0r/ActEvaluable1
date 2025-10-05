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
    $stmtcr = $conn->prepare("CREATE TABLE IF NOT EXISTS users(
                                        Nombre varchar(255), 
                                        Gmail varchar(255), 
                                        Password varchar(255), 
                                        PRIMARY KEY(Gmail))");
    $stmtcr->execute();
    $stmtcr->close();

    $passwd = password_hash(1234, PASSWORD_DEFAULT);
    $stmtcr = $conn->prepare("INSERT INTO users (Nombre, Gmail, Password) VALUES ('admin', 'test@gmail.com', '$passwd')");
    $stmtcr->execute();
    $stmtcr->close();

    $stmtcr = $conn->prepare("CREATE TABLE IF NOT EXISTS games (ID INT NOT NULL AUTO_INCREMENT, 
                                                                        Título varchar(255), 
                                                                        Descripción varchar(255), 
                                                                        Compañia varchar(255), 
                                                                        Caratula BLOB, año date, 
                                                                        userID varchar(255), 
                                                                        PRIMARY KEY(ID), 
                                                                        FOREIGN KEY (userID) REFERENCES users(Gmail))");
    $stmtcr->execute();
    $stmtcr->close();    

    echo "DB creada con exito"
?>