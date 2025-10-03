<?php
    session_start();
    include "../connection.php";

    try{
        $image = $_FILES["image"]["carat"];
        $tempName = $_FILES["image"]["tmp_name"];
        $imageName = $image;
        $targetDirectory = "upload/" . $imageName;
        move_uploaded_file($tempName, $targetDirectory);

        $stmtcr = $conn->prepare("INSERT INTO games(Título, Descripción, Compañia, Caratula, año, userID) VALUES (?,?,?,?,?,?)");
        $stmtcr->bind_param("ssssss", $_POST["name"], $_POST["desc"], $_POST["comp"], $targetDirectory, $_POST["date"], $_SESSION["gmail"]);        
        $stmtcr->execute();

        $_SESSION["exito"] = "Juego creado exitosamente";
        header("Location: ../../index.php");
    }catch(PDOException $e){
        $_SESSION["error"] = $e->getMessage();
    }

?>