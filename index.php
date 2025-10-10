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
        <!-- Botones de registro -->
        <div class="top-right">
            <h2>Tienda de juegos</h2>
            <button onclick="register()">Registrar usuario</button>
            <button onclick="login()">Iniciar sesion</button>
            <!-- Boton de salir de sesion, se esconde cuando no hay sesion iniciada -->
            <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): ?>
                <button onclick="games()">Registrar juegos</button>
            <?php endif; ?>                
            <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): ?>
                <button onclick="logout()">Cerrar sesión</button>
            <?php endif; ?>                     
        </div>
        
        <div>
            <h2>Barra de busqueda</h2>
            <input placeholder="Introducir nombre de juego" name="busquedajuegos" onkeyup="search(this.value)">
            <span id="showGames"></span>
        </div>   

        <?php
            include "PHP/Games/showGames.php";
            showGames();
        ?>   

        <!-- Funciones de los botones al ser pulsados -->         
        <script>
            $array = [];               
            function search(str) {
                if (str == "") {
                    document.getElementById("showGames").innerHTML = "";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("showGames").innerHTML = this.responseText;                        
                        }
                    };
                    xmlhttp.open("GET","PHP/searchGame.php?q="+str,true);
                    xmlhttp.send();
                }
            }
            function register(){
                window.location.href = "PHP/RegisterUser/registerUser.php"
            }
            function login(){
                window.location.href = "PHP/LoginUser/loginUser.php"                
            }
            function logout(){
                window.location.href = "PHP/logout.php";
            }                        
            function games(){
                window.location.href = "PHP/Games/registerGame.php"                
            }
        </script>

        <!-- Sección de errores y exitos -->
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