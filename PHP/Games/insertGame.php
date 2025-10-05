<?php
    session_start();
    /* Si no hay nadie como login entonces nos redirige a la pagina */
    if (isset($_SESSION["user"]) && $_SESSION["user"] != "") {
        send();
    } else {
        $_SESSION["error"] = "Inicie sesión para ver los juegos";
        header("Location: ../LoginUser/loginUser.php");
        exit();
    }

    function send() {
        include "../connection.php";
        validator(); 

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES["image"]["tmp_name"];
            $imgContent = file_get_contents($image);
            $imageHash = hash('sha256', $imgContent);
        } else {
            $defaultImagePath = '../../IMG/default-placeholder.png'; 
            $imgContent = file_get_contents($defaultImagePath);
        }

        /* Seccion que comprueba que la misma imagen no sea introducida dos veces */
        $checkImg = $conn->prepare("SELECT ID FROM games WHERE Caratula_hash = ?");
        $checkImg->bind_param("s", $imageHash);
        $checkImg->execute();
        $imgResult = $checkImg->get_result();

        if ($imgResult->num_rows > 0) {
            $_SESSION["error"] = "Esa imagen ya ha sido utilizada para otro juego.";
            header("Location: registerGame.php");
            exit();
        }
        
        $checkImg->close();

        /* Seccion que introduce todo dentro de la BD */
        $stmt = $conn->prepare("INSERT INTO games(Título, Descripción, Compañia, Caratula, año, userID, Caratula_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $_POST["name"], $_POST["desc"], $_POST["comp"], $imgContent, $_POST["date"], $_SESSION["gmail"], $imageHash);
        $stmt->execute();
        $stmt->close();

        $_SESSION["exito"] = "Juego creado exitosamente";
        header("Location: ../../index.php");
        exit();
    }

    /* Comprobante de valores correctos dentro del juego */
    function validator() {
        include "../connection.php";
        /* Seccion que indica que si o si necesitan valores */
        if (empty($_POST["name"])) {
            $_SESSION["error"] = "El nombre del juego es obligatorio";
            header("Location: registerGame.php");
            exit();
        }

        if (empty($_POST["desc"])) {
            $_SESSION["error"] = "La descripción del juego es obligatoria";
            header("Location: registerGame.php");
            exit();
        }

        if (empty($_POST["comp"])) {
            $_SESSION["error"] = "La compañía del juego es obligatoria";
            header("Location: registerGame.php");
            exit();
        }

        if (empty($_POST["date"])) {
            $_SESSION["error"] = "La fecha de salida del juego es obligatoria";
            header("Location: registerGame.php");
            exit();
        }

        /* Seccion que impide el meter el mismo juego dos veces */
        $check = $conn->prepare("SELECT ID FROM games WHERE Título = ? AND Compañia = ? AND año = ?");
        $check->bind_param("sss", $_POST["name"], $_POST["comp"], $_POST["date"]);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $_SESSION["error"] = "Ya existe ese juego en la base de datos.";
            header("Location: registerGame.php");
            exit();
        }

        $check->close();
    }
?>