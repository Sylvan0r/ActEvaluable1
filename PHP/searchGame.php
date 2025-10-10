<?php
    $q = $_REQUEST["q"];

    searchGames($q);
    
    function searchGames($name){
        include "connection.php";
        /* Query que da todos los stmtados dentro de la tabla games */

        $name = "%".$name."%";

        $stmt = $conn->prepare("SELECT ID, Título, Año, Caratula FROM games WHERE Título LIKE ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<div class="games-container">'; 
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
            echo "<h1>Juego no encontrado</h1>";
            echo '</div>'; 
        } 
    }
?>