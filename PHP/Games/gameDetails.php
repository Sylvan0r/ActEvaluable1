<?php
    session_start();
    include "../connection.php";

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        $stmt = $conn->prepare("SELECT Título, Descripción, Año, Caratula, Compañia, userID FROM games WHERE ID = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            echo '<div class="game-details">';
            echo '<h1 class="game-title">' . htmlspecialchars($row['Título']) . '</h1>';
            echo '<p class="game-desc"><strong>Descripción:</strong> ' . htmlspecialchars($row['Descripción']) . '</p>';
            echo '<p class="game-year"><strong>Año:</strong> ' . htmlspecialchars($row['Año']) . '</p>';
            echo '<p class="game-company"><strong>Compañía:</strong> ' . htmlspecialchars($row['Compañia']) . '</p>';
            echo '<div class="game-cover">';
            echo '<img src="data:image/jpg;base64,' . base64_encode($row['Caratula']) . '" alt="Carátula del juego">';
            echo '</div>';

            if ($row['userID'] === $_SESSION["gmail"]) {
                echo '<div class="game-actions">';
                echo '<a href="editGame.php?id=' . urlencode($_GET['id']) . '" class="btn edit">Editar</a>';
                echo '<a href="deleteGame.php?id=' . urlencode($_GET['id']) . '" class="btn delete" onclick="return confirm(\'¿Estás seguro de que quieres borrar este juego?\');">Borrar</a>';
                echo '</div>';
            }

            echo '<div class="back-button">';
            echo '<a href="showGames.php" class="btn">Volver</a>';
            echo '</div>';
            echo '</div>'; 
        } else {
            echo "<p class='error'>Juego no encontrado.</p>";
        }
        $stmt->close();
    } else {
        echo "ID inválido.";
    }
?>

<html>
    <head>
        <title>Detalles de <?php echo htmlspecialchars($row['Título'])?></title>
        <link rel="stylesheet" href="../../CSS/mainStyle.css">
    </head>
</html>