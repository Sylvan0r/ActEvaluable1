<?php
    /* Si no hay nadie como login entonces nos redirige a la pagina */

    function showGames() {
        include "PHP/connection.php";
        /* Query que da todos los resultados dentro de la tabla games */
        $result = $conn->query("SELECT ID, Título, Año, Caratula FROM games");
        if ($result->num_rows > 0) {
            echo '<div class="games-container">';
            /* HTML hecho con echo con valores del query */
            while ($row = $result->fetch_assoc()) {
                echo '<div class="game-card">';
                echo '<div class="name">' . htmlspecialchars($row['Título']) . '</div>';
                echo '<div class="date">' . htmlspecialchars($row['Año']) . '</div>';
                echo '<a href="PHP/Games/gameDetails.php?id=' . urlencode($row['ID']) . '">';
                echo '<img width="300" height="300" src="data:image/jpg;base64,' . base64_encode($row['Caratula']) . '" alt="Carátula del juego">';
                echo '</a>';
                echo '</div>';
            }

            echo '</div>'; 
        } else {
            echo "<h1>No hay juegos disponibles.</h1>";
            echo '</div>'; 
        }
    }
?>
