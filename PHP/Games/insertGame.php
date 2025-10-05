<?php
    session_start();
    if (isset($_SESSION["user"]) && $_SESSION["user"]!=""){
        send();
    }else{
        $_SESSION["error"] = "Inicie sesion para ver los juegos";
        header("Location: ../LoginUser/loginUser.php");
    }

    function send(){
        include "../connection.php";

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES["image"]["tmp_name"];
            $imgContent = file_get_contents($image);
        } else {
            $defaultImagePath = '../../IMG/default-placeholder.png'; 
            $imgContent = file_get_contents($defaultImagePath);
        }
        $stmt = $conn->prepare("INSERT INTO games(Título, Descripción, Compañia, Caratula, año) VALUES (?,?,?,?,?)");            
        $stmt->bind_param("sssss", $_POST["name"], $_POST["desc"], $_POST["comp"], $imgContent, $_POST["date"]);
        $stmt->execute();
        $_SESSION["exito"] = "Juego creado exitosamente";
        header("Location: ../../index.php");              
    }
?>