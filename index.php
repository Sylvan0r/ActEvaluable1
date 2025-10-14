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
            <h2 class="top-left">Tienda de juegos</h2>
                <?php if (!isset($_SESSION["user"])): ?>            
                    <button onclick="register()">Registrar usuario</button>
                    <button onclick="login()">Iniciar sesion</button>
                <?php endif; ?>               
                <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): ?>
                    <button onclick="games()">Registrar juegos</button>
                <?php endif; ?>               
            <!-- Boton de salir de sesion, se esconde cuando no hay sesion iniciada -->           
            <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): 
                include "PHP/connection.php";
                $result = $conn->query("SELECT userImg FROM users where Gmail like '$_SESSION[gmail]'");
                    while ($row = $result->fetch_assoc()) {
                        echo '<button onclick="show()"><img width="30" height="30" src="data:image/jpg;base64,' . base64_encode($row['userImg']) . '" alt="Carátula del juego"></button>';                    
                    }
                ?>           
                <div id="extraBut" style="display: none;">             
                    <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): ?>
                        <button onclick="userInfo()">Información usuario</button>
                    <?php endif; ?>
                    <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): ?>
                        <button onclick="logout()">Cerrar sesión</button>
                    <?php endif; ?>                    
                </div>     
            <?php endif; ?>                          
        </div>
        
        <div>
            <h2>Barra de busqueda</h2>
            <input placeholder="Introducir nombre de juego" name="busquedajuegos" onkeyup="search(this.value)">
            <span id="showGames"></span>
        </div>   

        <?php
            if (isset($_SESSION["user"]) && $_SESSION["user"] != ""){
                include "PHP/Games/showGames.php";
                showGames();
            }
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
            function show(){
                document.getElementById("extraBut").style = "display:block";
            }
            function userInfo(){
                window.location.href = "PHP/User/userInfo.php"                
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