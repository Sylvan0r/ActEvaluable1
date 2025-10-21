<?php
    use Dom\Document;
    session_start();
    include "../connection.php";

    if (!isset($_SESSION["user"])) {
        echo "No autorizado";
        exit;
    }

    if (isset($_POST['id']) && isset($_POST['type'])) {
        $id = intval($_POST['id']);
        $type = $_POST['type'];

        $stmtTitle = $conn->prepare("SELECT Título FROM games WHERE ID = ?");
        $stmtTitle->bind_param("i", $id);
        $stmtTitle->execute();
        $result = $stmtTitle->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $titulo = $row["Título"];
            $user = $_SESSION["user"];

            $check = $conn->prepare("SELECT likes, dislikes FROM gamesLikes WHERE nombreJuego = ? AND userName = ?");
            $check->bind_param("ss", $titulo, $user);
            $check->execute();
            $res = $check->get_result();

            if ($res->num_rows > 0) {
                $voteRow = $res->fetch_assoc();
                if ($voteRow['likes'] == 1) {
                    echo "Ya has votado con un like.";
                } elseif ($voteRow['dislikes'] == 1) {
                    echo "Ya has votado con un dislike.";
                } else {
                    echo "Ya has votado anteriormente.";
                }
                exit; 
            }

            $likes = ($type === "like") ? 1 : 0;
            $dislikes = ($type === "dislike") ? 1 : 0;

            $stmt = $conn->prepare("INSERT INTO gamesLikes(nombreJuego, likes, dislikes, userName) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siis", $titulo, $likes, $dislikes, $user);
            $stmt->execute();

            echo ucfirst($type) . " registrado correctamente.";

            $countStmt = $conn->prepare("SELECT COUNT(DISTINCT userName) AS totalUsuarios FROM gamesLikes WHERE nombreJuego = ?");
            $countStmt->bind_param("s", $titulo);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();
            $numPersonas = $countRow['totalUsuarios'];
        } else {
            echo "Juego no encontrado.";
        }
    } else {
        echo "Datos inválidos.";
    }
?>