<?php
    session_start();
    include "../connection.php";

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']);

        $stmt = $conn->prepare("SELECT userID FROM games WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if ($row['userID'] === $_SESSION['gmail']) {
                $deleteStmt = $conn->prepare("DELETE FROM games WHERE ID = ?");
                $deleteStmt->bind_param("i", $id);
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