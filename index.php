<?php
    session_start();
    include "PHP/connection.php"; // ajusta la ruta según tu estructura

    // Si no hay sesión activa, pero sí hay cookie de "recordar sesión"
    if (!isset($_SESSION["user"]) && isset($_COOKIE["remember_token"])) {
        $token = $_COOKIE["remember_token"];

        // Buscar el usuario que tenga ese token
        $stmt = $conn->prepare("SELECT Nombre, Gmail FROM users WHERE remember_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        // Si existe un usuario con ese token, reestablecemos su sesión
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            $_SESSION["user"] = $user["Nombre"];
            $_SESSION["gmail"] = $user["Gmail"];
            $_SESSION["exito"] = "Sesión restaurada automáticamente.";

            // Opcional: renovar la cookie por 7 días más
            setcookie("remember_token", $token, time() + (86400 * 7), "/", "", true, true);
        }
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Actividad 1</title>
        <link rel="stylesheet" href="CSS/mainStyle.css">
    </head>
    <body>
        <!-- Botones de registro -->
         <header>
            <div class="top-right">
                <h2 class="top-left">Tienda de juegos</h2>
                <input class="top-left" placeholder="Introducir nombre de juego" name="busquedajuegos" onkeyup="search(this.value)" onkeydown="search(this.value)">
                    <?php if (!isset($_SESSION["user"])): ?>            
                        <a href="PHP/RegisterUser/registerUser.php"><button>Registrar usuario</button></a>
                        <a href="PHP/LoginUser/loginUser.php"><button>Iniciar sesion</button></a>
                    <?php endif; ?>               
                    <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): ?>
                        <a href="PHP/Games/registerGame.php"><button>Registrar juegos</button></a>
                    <?php endif; ?>               
                <!-- Boton de salir de sesion, se esconde cuando no hay sesion iniciada -->           
                <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): 
                    include "PHP/connection.php";
                    $result = $conn->query("SELECT userImg FROM users where Gmail like '$_SESSION[gmail]'");
                        while ($row = $result->fetch_assoc()) {
                            echo '<img onclick="show()" width="50" height="50" src="data:image/jpg;base64,' . base64_encode($row['userImg']) . '" alt="Carátula del juego">';                    
                        }
                    ?>           
                    <div id="extraBut" style="display: none;">             
                        <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): ?>
                            <a href="PHP/User/userInfo.php"><button>Información usuario</button></a>
                        <?php endif; ?>                        
                        <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): ?>
                            <a href="PHP\Games\gamesStats.php"><button>Estadisticas juegos</button></a>
                        <?php endif; ?>
                        <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != ""): ?>
                            <a href="PHP/User/logout.php"><button>Cerrar sesión</button></a>
                        <?php endif; ?>                    
                    </div>     
                <?php endif; ?>                          
            </div>
        </header>
        
        <div>
            <span id="showGames"></span>
        </div>   
        <div id="test">
            <?php
                include "PHP/Games/showGames.php";
                showGames();
            ?>
        </div>
        <!-- Funciones de los botones al ser pulsados -->         
        <script>
            $array = [];               
            function search(str) {
                if (str == "") {
                    document.getElementById("showGames").innerHTML = "";
                    document.getElementById("test").style.display = "flex";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("showGames").innerHTML = this.responseText;                        
                        }
                    };
                    xmlhttp.open("GET","PHP/Games/searchGame.php?q="+str,true);
                    xmlhttp.send();                  
                    document.getElementById("test").style.display = "none";
                }
            }    

            function show(){
                const extra = document.getElementById("extraBut");
                if (extra.style.display === "block") {
                    extra.style.display = "none";
                } else {
                    extra.style.display = "block";
                }
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
