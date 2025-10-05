<?php
    session_start();
    include "../connection.php";

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];
        /* Select del userID por id */
        $stmt = $conn->prepare("SELECT userID FROM games WHERE ID = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        /* Seccion de borrado de juego a traves de ID */
        if ($row = $result->fetch_assoc()) {
            if ($row['userID'] === $_SESSION['gmail']) {
                $deleteStmt = $conn->prepare("DELETE FROM games WHERE ID = ?");
                $deleteStmt->bind_param("s", $id);
                $deleteStmt->execute();
                header("Location: showGames.php");
                exit();
            } else {
                die("No tienes permiso para borrar este juego.");
            }
        } else {
            die("Juego no encontrado.");
        }
    }
?>