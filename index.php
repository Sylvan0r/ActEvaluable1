<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Actividad 1</title>
        <link rel="stylesheet" href="CSS/mainStyle.css">
    </head>
    <body>
        <div class="top-right">
            <button onclick="register()">Registrar usuario</button>
            <button onclick="login()">Iniciar sesion</button>
        </div>

        <h1>Biblioteca de juegos</h1>

        <div>
            <button onclick="games()">Registrar juegos</button>
            <button onclick="seeGames()">Ver juegos registrados</button>
        </div>

        <script>
            function register(){
                window.location.href = "PHP/RegisterUser/registerUser.php"
            }
            function login(){
                window.location.href = "PHP/LoginUser/loginUser.php"                
            }
            function games(){
                window.location.href = "PHP/Games/registerGame.php"                
            }
            function seeGames(){
                window.location.href = "PHP/Games/showGames.php"                
            }
        </script>

        <?php
            if(isset($_SESSION["error"]) && $_SESSION["error"] != "") {
                echo "<br>";
                echo "<p class='error'>Error: " . $_SESSION["error"] . "</p>";
                $_SESSION["error"] = "";
            }
            if(isset( $_SESSION["exito"]) && $_SESSION["exito"] != "") {
                echo "<br>";
                echo "<p class='exito'>Esta usted iniciado como: ".$_SESSION["user"] . "</p>";
                $_SESSION["exito"] = "";
            }
        ?>           
    </body>
</html>