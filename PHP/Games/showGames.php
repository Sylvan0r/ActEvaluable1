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
            while ($row = $result->fetch_assoc()) {
                echo '<div style="display: inline-block; margin: 15px; text-align: center;">';
                echo '<div style="font-weight: bold;">' . htmlspecialchars($row['Título']) . '</div>';
                echo '<div>' . htmlspecialchars($row['Año']) . '</div>';
                
                echo '<a href="gameDetails.php?id=' . urlencode($row['ID']) . '">';
                echo '<img width="300" height="300" src="data:image/jpg;base64,' . base64_encode($row['Caratula']) . '" />';
                echo '</a>';

                echo '</div>';
            }
        } else {
            echo "No hay juegos disponibles.";
        }
    }
?>