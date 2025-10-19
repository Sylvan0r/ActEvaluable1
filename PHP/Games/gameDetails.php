<?php
    session_start();
    include "../connection.php";

    $likes = 0;
    $dislikes = 0;
    $titulo = "Juego";
    $numPersonas = 0;

    if (!isset($_GET['id'])) {
        echo "ID inválido.";
        exit();
    }

    $id = intval($_GET['id']);

    // Actualizar visualizaciones
    $upd = $conn->prepare("UPDATE games SET views = views + 1 WHERE ID = ?");
    $upd->bind_param("i", $id);
    $upd->execute();
    $upd->close();

    // Obtener detalles del juego
    $stmt = $conn->prepare("SELECT Título, Descripción, Año, Caratula, Compañia, userID, views FROM games WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        echo "<p class='error'>Juego no encontrado.</p>";
        exit();
    }

    $row = $result->fetch_assoc();
    $titulo = $row['Título'];
    $views = (int)$row['views'];

    // Obtener total de likes y dislikes del juego
    $stmtLikes = $conn->prepare("SELECT COALESCE(SUM(likes),0) AS totalLikes,COALESCE(SUM(dislikes),0) AS totalDislikes,COUNT(DISTINCT userName) AS totalUsuarios FROM gamesLikes WHERE nombreJuego = ?");
    $stmtLikes->bind_param("s", $titulo);
    $stmtLikes->execute();
    $resultLikes = $stmtLikes->get_result();
    $stats = $resultLikes->fetch_assoc();

    $likes = (int)$stats['totalLikes'];
    $dislikes = (int)$stats['totalDislikes'];
    $numPersonas = (int)$stats['totalUsuarios'];

    $stmtLikes->close();
    $stmt->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Detalles de <?php echo htmlspecialchars($titulo); ?></title>
        <link rel="stylesheet" href="../../CSS/mainStyle.css">
    </head>
    <body>
    <div class="game-details">
        <h1 class="game-title"><?php echo htmlspecialchars($titulo); ?></h1>
        <p class="game-desc"><strong>Descripción:</strong> <?php echo htmlspecialchars($row['Descripción']); ?></p>
        <p class="game-year"><strong>Año:</strong> <?php echo htmlspecialchars($row['Año']); ?></p>
        <p class="game-company"><strong>Compañía:</strong> <?php echo htmlspecialchars($row['Compañia']); ?></p>

        <div class="game-cover">
            <img src="data:image/jpg;base64,<?php echo base64_encode($row['Caratula']); ?>" alt="Carátula del juego">
        </div>

        <div class="game-actions">
            <button onclick="darLike(<?php echo $id; ?>)">Like</button>

            <div class="like-bar">
                <?php
                $totalVotos = $likes + $dislikes;
                if ($totalVotos === 0) {
                    echo '<p>Este juego aún no tiene votos.</p>';
                    echo '<progress value="0" max="100"></progress>';
                } else {
                    $porcentajeLikes = round(($likes / $totalVotos) * 100);
                    echo '<p>Le gusta al ' . $porcentajeLikes . '% de los usuarios (' . $likes . '/' . $totalVotos . ')</p>';
                    echo '<p>Total de personas que han votado: ' . $numPersonas . '</p>';
                    echo '<progress value="' . $porcentajeLikes . '" max="100"></progress>';
                }
                ?>
            </div>
            <button onclick="darDislike(<?php echo $id; ?>)">Dislike</button>
        </div>

        <p class="game-views">Visualizaciones totales: <?php echo $views; ?></p>

        <?php
            // Mostrar opciones de edición solo al creador
            if (isset($_SESSION["gmail"]) && $row['userID'] === $_SESSION["gmail"]) {
                echo '<div class="game-actions">';
                echo '<a href="editGame.php?id=' . urlencode($id) . '" class="btn edit">Editar</a>';
                echo '<a href="deleteGame.php?id=' . urlencode($id) . '" class="btn delete" onclick="return confirm(\'¿Estás seguro de que quieres borrar este juego?\');">Borrar</a>';
                echo '</div>';
            }
        ?>

        <div class="back-button">
            <a href="../../index.php" class="btn">Volver</a>
        </div>
    </div>

    <script>
        function darLike(id) {
            fetch("like.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + id + "&type=like"
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            });
        }

        function darDislike(id) {
            fetch("like.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + id + "&type=dislike"
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            });
        }
    </script>
    </body>
</html>