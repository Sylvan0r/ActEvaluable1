<?php
    session_start();
    include "../connection.php";

    try{
        if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
            $image = $_FILES["image"]["tmp_name"];
            $imgContent = file_get_contents($image);
            $stmt = $conn->prepare("INSERT INTO games(Título, Descripción, Compañia, Caratula, año, userID) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("ssssss", $_POST["name"], $_POST["desc"], $_POST["comp"], $imgContent, $_POST["date"], $_SESSION["user"]);
            $stmt->execute();

            $_SESSION["exito"] = "Juego creado exitosamente";
            header("Location: ../../index.php");            
        }
    }catch(PDOException $e){
        $_SESSION["error"] = $e->getMessage();
    }

?>