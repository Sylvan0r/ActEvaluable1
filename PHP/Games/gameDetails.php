<?php
session_start();
include "../connection.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT Título, Año, Caratula, Compañia, userID FROM games WHERE ID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        echo '<h1>' . htmlspecialchars($row['Título']) . '</h1>';
        echo '<p><strong>Año:</strong> ' . htmlspecialchars($row['Año']) . '</p>';
        echo '<p><strong>Compañía:</strong> ' . htmlspecialchars($row['Compañia']) . '</p>';
        echo '<img width="400" src="data:image/jpg;base64,' . base64_encode($row['Caratula']) . '" />';

        if ($row['userID'] === $_SESSION["gmail"]) {
            echo '<br><br>';
            
            echo '<a href="editGame.php?id=' . urlencode($_GET['id']) . '" style="margin-right: 10px;">
                    <button>Editar</button>
                </a>';

            echo '<a href="deleteGame.php?id=' . urlencode($_GET['id']) . '" onclick="return confirm(\'¿Estás seguro de que quieres borrar este juego?\');">
                    <button style="background-color: red; color: white;">Borrar</button>
                </a>';
        }

        echo '<br><br><a href="showGames.php">← Volver</a>';
    } else {
        echo "Juego no encontrado.";
    }

    $stmt->close();
} else {
    echo "ID inválido.";
}
?>
