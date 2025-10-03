<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Actividad 1</title>
        <link rel="stylesheet" href="CSS/mainStyle.CSS">
    </head>
    <body>
        <div class="session">
            <button onclick="register()">Registrar usuario</button>
            <button onclick="login()">Iniciar sesion</button>
        </div>
        <br>
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
                window.location.href = "PHP/Games/gamesMain.php"                
            }
            function seeGames(){
                window.location.href = "PHP/Games/imagesTest.php"                
            }
        </script>

        <?php
            if(isset($_SESSION["error"]) && $_SESSION["error"] != "") {
                echo "Error: " . $_SESSION["error"];
                $_SESSION["error"] = "";
            }
            if(isset( $_SESSION["exito"]) && $_SESSION["exito"] != "") {
                echo "<br>Exito: " . $_SESSION["exito"];
                echo "<br>Esta usted iniciado como: ".$_SESSION["user"];
                $_SESSION["exito"] = "";
            }
        ?>           
    </body>
</html>