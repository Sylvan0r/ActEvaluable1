<?php
include "../connection.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT Título, Año, Caratula, Compañia FROM games WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        echo '<h1>' . htmlspecialchars($row['Título']) . '</h1>';
        echo '<p><strong>Año:</strong> ' . htmlspecialchars($row['Año']) . '</p>';
        echo '<p><strong>Compañía:</strong> ' . htmlspecialchars($row['Compañia']) . '</p>';
        echo '<img width="400" src="data:image/jpg;base64,' . base64_encode($row['Caratula']) . '" />';
        echo '<br><br><a href="showGames.php">← Volver</a>';
    } else {
        echo "Juego no encontrado.";
    }

    $stmt->close();
} else {
    echo "ID inválido.";
}
?>
