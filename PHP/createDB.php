<!-- Creacion de base de datos rapida con admin incluido para testeo -->
<?php
    $dbHost     = "localhost";  
    $dbUsername = "root";  
    $dbPassword = "";  

    $conn = new mysqli($dbHost, $dbUsername, $dbPassword);

    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $stmtcr = $conn->prepare("CREATE DATABASE IF NOT EXISTS ActEv1");
    $stmtcr->execute();
    $stmtcr->close();
    
    $dbName= "ActEv1";


    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    $stmtcr = $conn->prepare("CREATE TABLE IF NOT EXISTS users(
                                        ID int not null unique auto_increment,
                                        Nombre varchar(255) unique, 
                                        Gmail varchar(255), 
                                        Password varchar(255),
                                        userImg BLOB, 
                                        remember_token varchar(64) NULL,
                                        PRIMARY KEY(Gmail))");
    $stmtcr->execute();
    $stmtcr->close();

    $nombre = 'admin';
    $gmail = 'test@gmail.com';    
    $passwd = password_hash('1234', PASSWORD_DEFAULT);
    $defaultImagePath = '../IMG/default-placeholder.png'; 

    if (!file_exists($defaultImagePath)) {
        die("La imagen por defecto no se encontró en: $defaultImagePath");
    }

    $imgContent = file_get_contents($defaultImagePath);
    $stmt = $conn->prepare("INSERT IGNORE INTO users (Nombre, Gmail, Password, userImg) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $gmail, $passwd, $imgContent);

    if (!$stmt->execute()) {
        die("Error al insertar usuario: " . $stmt->error);
    }

    $stmt->close();


    $stmtcr = $conn->prepare("CREATE TABLE IF NOT EXISTS games (ID INT NOT NULL AUTO_INCREMENT, 
                                                                        Título varchar(255) unique, 
                                                                        Descripción varchar(255), 
                                                                        Compañia varchar(255), 
                                                                        Caratula BLOB, 
                                                                        Caratula_hash varchar(64),
                                                                        año date, 
                                                                        userID varchar(255),
                                                                        views int default 0,
                                                                        PRIMARY KEY(ID), 
                                                                        FOREIGN KEY (userID) REFERENCES users(Gmail) on delete cascade)");
    $stmtcr->execute();
    $stmtcr->close();    

    $stmtcr = $conn->prepare("CREATE TABLE IF NOT EXISTS gamesLikes (
                                                                            nombreJuego varchar(255),
                                                                            likes int(1),
                                                                            dislikes int(1),
                                                                            userName varchar(255),
                                                                            PRIMARY KEY (nombreJuego, userName),
                                                                            FOREIGN KEY (nombreJuego) REFERENCES games(`Título`) on delete cascade,
                                                                            FOREIGN KEY (userName) REFERENCES users(Nombre) on delete cascade
                                                                        ) ENGINE=InnoDB");
    $stmtcr->execute();
    $stmtcr->close();    

    echo "DB creada con exito"
?>