<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "actev1";
    try{
        $conn = new mysqli($servername, $username, $password, $dbname);   

        $stmtcr = $conn->prepare("INSERT INTO games(Título, Descripción, Compañia, Caratula, año) VALUES (?,?,?,?,?)");
        $stmtcr->bind_param("sssss", $_POST["name"], $_POST["desc"], $_POST["comp"], $_FILES["carat"]["tmp_name"], $_POST["date"]);        
        $stmtcr->execute();

        $_SESSION["exito"] = "Juego creado exitosamente";
        header("Location: ../index.php");
    }catch(PDOException $e){
        $_SESSION["error"] = $e->getMessage();
    }

?>