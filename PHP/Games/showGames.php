<?php
    session_start();

    if (isset($_SESSION["user"]) && $_SESSION["user"]!=""){
        showGames();
    }else{
        $_SESSION["error"] = "Inicie sesion para ver los juegos";
        header("Location: ../LoginUser/loginUser.php");
    }


    function showGames() {
        include "../connection.php";

        $result = $conn->query("SELECT ID, Título, Año, Caratula FROM games");

        if ($result->num_rows > 0) {
            echo '<div class="games-container">';

            while ($row = $result->fetch_assoc()) {
                echo '<div class="game-card">';
                echo '<div class="name">' . htmlspecialchars($row['Título']) . '</div>';
                echo '<div class="date">' . htmlspecialchars($row['Año']) . '</div>';
                echo '<a href="gameDetails.php?id=' . urlencode($row['ID']) . '">';
                echo '<img width="300" height="300" src="data:image/jpg;base64,' . base64_encode($row['Caratula']) . '" alt="Carátula del juego">';
                echo '</a>';
                echo '</div>';
            }

            echo '</div>'; 
            echo '<div class="back-button"><a href="../../index.php"><button>Volver</button></a></div>';
        } else {
            echo "No hay juegos disponibles.";
        }
    }
?>

<html>
    <head>
        <title>Biblioteca de juegos</title>
        <link rel="stylesheet" href="../../CSS/mainStyle.css">
    </head>
</html>