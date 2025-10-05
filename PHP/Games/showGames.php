<?php
    session_start();
    /* Si no hay nadie como login entonces nos redirige a la pagina */
    if (isset($_SESSION["user"]) && $_SESSION["user"]!=""){
        showGames();
    }else{
        $_SESSION["error"] = "Inicie sesion para ver los juegos";
        header("Location: ../LoginUser/loginUser.php");
    }


    function showGames() {
        include "../connection.php";
        /* Query que da todos los resultados dentro de la tabla games */
        $result = $conn->query("SELECT ID, Título, Año, Caratula FROM games");

        if ($result->num_rows > 0) {
            echo '<div class="games-container">';
            /* HTML hecho con echo con valores del query */
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
            echo "<h1>No hay juegos disponibles.</h1>";
            echo '</div>'; 
            echo '<div class="back-button"><a href="../../index.php"><button>Volver</button></a></div>';
        }
    }
?>

<html>
    <head>
        <title>Biblioteca de juegos</title>
        <link rel="stylesheet" href="../../CSS/mainStyle.css">
    </head>
</html>