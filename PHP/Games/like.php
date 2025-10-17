<?php
session_start();
include "../connection.php";

if (!isset($_SESSION["user"])) {
    echo "No autorizado";
    exit;
}

if (isset($_POST['id']) && isset($_POST['type'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type'];

    // Obtener título
    $stmtTitle = $conn->prepare("SELECT Título FROM games WHERE ID = ?");
    $stmtTitle->bind_param("i", $id);
    $stmtTitle->execute();
    $result = $stmtTitle->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $titulo = $row["Título"];
        $user = $_SESSION["user"];

        // Verificar si ya hay un voto
        $check = $conn->prepare("SELECT * FROM gamesLikes WHERE nombreJuego = ? AND userName = ?");
        $check->bind_param("ss", $titulo, $user);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            echo "Ya has votado.";
        } else {
            $likes = ($type === "like") ? 1 : 0;
            $dislikes = ($type === "dislike") ? 1 : 0;

            $stmt = $conn->prepare("INSERT INTO gamesLikes(nombreJuego, likes, dislikes, userName) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siis", $titulo, $likes, $dislikes, $user);
            $stmt->execute();

            echo ucfirst($type) . " registrado correctamente.";
        }
    } else {
        echo "Juego no encontrado.";
    }
} else {
    echo "Datos inválidos.";
}
?>