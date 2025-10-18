<?php
session_start();
include "../connection.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION["gmail"])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["gmail"];

// Obtener todos los juegos del usuario, incluyendo la carátula
$stmt = $conn->prepare("SELECT ID, Título, Año, views, Caratula FROM games WHERE userID = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de mis juegos</title>
    <link rel="stylesheet" href="../../CSS/mainStyle.css">
</head>
<body>
    <h1>Estadísticas de mis juegos</h1>

    <?php
    if ($result->num_rows > 0) {
        echo '<div class="stats-container">';
        while ($row = $result->fetch_assoc()) {
            $gameId = $row['ID'];
            $views = (int)$row['views'];
            $caratulaBase64 = base64_encode($row['Caratula']);

            // Obtener total de likes y dislikes por juego
            $stmtLikes = $conn->prepare("
                SELECT 
                    COALESCE(SUM(likes),0) AS totalLikes,
                    COALESCE(SUM(dislikes),0) AS totalDislikes,
                    COUNT(DISTINCT userName) AS totalUsuarios
                FROM gamesLikes
                WHERE nombreJuego = (SELECT Título FROM games WHERE ID = ?)
            ");
            $stmtLikes->bind_param("i", $gameId);
            $stmtLikes->execute();
            $resultLikes = $stmtLikes->get_result();
            $stats = $resultLikes->fetch_assoc();

            $likes = (int)$stats['totalLikes'];
            $dislikes = (int)$stats['totalDislikes'];
            $totalVotos = $likes + $dislikes;
            $numUsuarios = (int)$stats['totalUsuarios'];

            $porcentajeLikes = $totalVotos > 0 ? round(($likes / $totalVotos) * 100) : 0;

            echo '<div class="stat-card">';
            echo '<h2>' . htmlspecialchars($row['Título']) . ' (' . htmlspecialchars($row['Año']) . ')</h2>';
            echo '<div class="game-image">';
            echo '<img src="data:image/jpg;base64,' . $caratulaBase64 . '" alt="Carátula del juego">';
            echo '</div>';

            if ($totalVotos === 0) {
                echo '<p>Este juego aún no tiene votos.</p>';
                echo '<progress value="0" max="100"></progress>';
            } else {
                echo '<p>👍 ' . $porcentajeLikes . '% de likes (' . $likes . '/' . $totalVotos . ')</p>';
                echo '<p>👥 Total de personas que han votado: ' . $numUsuarios . '</p>';
                echo '<progress value="' . $porcentajeLikes . '" max="100"></progress>';
            }

            echo '<p>👀 Visualizaciones totales: ' . $views . '</p>';
            echo '<a href="gameDetails.php?id=' . urlencode($gameId) . '" class="btn">Ver detalles</a>';
            echo '</div>';
            $stmtLikes->close();
        }
        echo '</div>';
        
        echo '<a href="../../index.php"><button>Volver</button></a>';

    } else {
        echo "<h2>No has subido ningún juego.</h2>";
    }

    $stmt->close();
    $conn->close();
    ?>
</body>
</html>