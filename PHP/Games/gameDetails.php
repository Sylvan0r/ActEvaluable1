<?php
    session_start();
    include "../connection.php";

    $likes = 0;
    $dislikes = 0;
    $contentLikes = 0;
    $titulo = "Juego";

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        // Obtener detalles del juego
        $stmt = $conn->prepare("SELECT Título, Descripción, Año, Caratula, Compañia, userID FROM games WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Obtener likes y dislikes
        $stmtlikes = $conn->prepare("SELECT likes, dislikes FROM gamesLikes WHERE nombreJuego = (SELECT Título FROM games WHERE ID = ?)");
        $stmtlikes->bind_param("i", $id);
        $stmtlikes->execute();
        $resultLikes = $stmtlikes->get_result();

        if ($resultLikes->num_rows === 1) {
            $rowLikes = $resultLikes->fetch_assoc();
            $likes = $rowLikes["likes"];
            $dislikes = $rowLikes["dislikes"];
            $contentLikes = $likes - $dislikes;
        }

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $titulo = $row['Título'];
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
                    <?php
                        // Forzar tipos a entero por seguridad
                        $likes = isset($likes) ? (int)$likes : 0;
                        $dislikes = isset($dislikes) ? (int)$dislikes : 0;

                        $totalVotos = $likes + $dislikes;

                        echo '<div class="like-bar">';

                        if ($totalVotos > 0) {
                            $porcentajeLikes = round(($likes / $totalVotos) * 100);
                            $porcentajeDislikes = 100 - $porcentajeLikes;

                            echo '<p>👍 Le gusta al ' . $porcentajeLikes . '% de los usuarios</p>';
                            echo '<progress value="' . $porcentajeLikes . '" max="100"></progress>';
                        }else{
                                    
                        }
                        
                        if(is_null($likes) && is_null($dislikes)){
                            echo '<p>Este juego aún no tiene votos.</p>';
                            echo '<progress value="0" max="100"></progress>';
                        }

                        echo '</div>';
                    ?>

                    <button onclick="darDislike(<?php echo $id; ?>)">Dislike</button>
                </div>

                <?php
                if (isset($_SESSION["gmail"]) && $row['userID'] === $_SESSION["gmail"]) {
                    echo '<div class="game-actions">';
                    echo '<a href="editGame.php?id=' . urlencode($_GET['id']) . '" class="btn edit">Editar</a>';
                    echo '<a href="deleteGame.php?id=' . urlencode($_GET['id']) . '" class="btn delete" onclick="return confirm(\'¿Estás seguro de que quieres borrar este juego?\');">Borrar</a>';
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
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
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
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
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
            <?php
        } else {
            echo "<p class='error'>Juego no encontrado.</p>";
        }

        $stmt->close();
        $stmtlikes->close();
    } else {
        echo "ID inválido.";
    }
?>