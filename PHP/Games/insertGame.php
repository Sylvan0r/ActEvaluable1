<?php
    session_start();

    if (isset($_SESSION["user"]) && $_SESSION["user"] != "") {
        send();
    } else {
        $_SESSION["error"] = "Inicie sesión para ver los juegos";
        header("Location: ../LoginUser/loginUser.php");
        exit();
    }

    function send() {
        include "../connection.php";
        validator(); // Ejecuta validación antes de continuar

        // Imagen (opcional)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES["image"]["tmp_name"];
            $imgContent = file_get_contents($image);
        } else {
            $defaultImagePath = '../../IMG/default-placeholder.png'; 
            $imgContent = file_get_contents($defaultImagePath);
        }

        // Insertar en la base de datos
        $stmt = $conn->prepare("INSERT INTO games(Título, Descripción, Compañia, Caratula, año, userID) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $_POST["name"], $_POST["desc"], $_POST["comp"], $imgContent, $_POST["date"], $_SESSION["gmail"]);
        $stmt->execute();
        $stmt->close();

        $_SESSION["exito"] = "Juego creado exitosamente";
        header("Location: ../../index.php");
        exit();
    }

    function validator() {
        include "../connection.php";

        // Validación individual con mensajes personalizados
        if (empty($_POST["name"])) {
            $_SESSION["error"] = "El nombre del juego es obligatorio";
            header("Location: gamesMain.php");
            exit();
        }

        if (empty($_POST["desc"])) {
            $_SESSION["error"] = "La descripción del juego es obligatoria";
            header("Location: gamesMain.php");
            exit();
        }

        if (empty($_POST["comp"])) {
            $_SESSION["error"] = "La compañía del juego es obligatoria";
            header("Location: gamesMain.php");
            exit();
        }

        if (empty($_POST["date"])) {
            $_SESSION["error"] = "La fecha de salida del juego es obligatoria";
            header("Location: gamesMain.php");
            exit();
        }

        // Validar duplicado
        $check = $conn->prepare("SELECT ID FROM games WHERE Título = ? AND Compañia = ? AND año = ?");
        $check->bind_param("sss", $_POST["name"], $_POST["comp"], $_POST["date"]);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $_SESSION["error"] = "Ya existe ese juego en la base de datos.";
            header("Location: gamesMain.php");
            exit();
        }

        $check->close();
    }
?>