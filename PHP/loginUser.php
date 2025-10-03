<?php
    session_start();
    $_SESSION["user"] = null;
    $_SESSION["gmail"] = null;
    $_SESSION["passwd"] = null;
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Creacion de usuario</title>
    </head>
    <body>
        <h1>Inicio sesion</h1>

        <form action="loggerUser.php" method="post">         
            <input name="gmail" placeholder="Introduzca su Gmail">
            <br><br>
            <input name="passwd" type="password" placeholder="Introduzca su contraseña">
            <br><br>
            <input name="passwd2" type="password" placeholder="Introduzca su contraseña otra vez">
            <button type="submit">Iniciar sesion</button>
        </form>

        <?php
            if(isset($_SESSION["error"]) && $_SESSION["error"] != "") {
                echo "Error: " . $_SESSION["error"];
                $_SESSION["error"] = "";
            }
            if(isset( $_SESSION["exito"]) && $_SESSION["exito"] != "") {
                echo "Exito: " . $_SESSION["exito"];
                $_SESSION["exito"] = "";
            }
        ?>        
    </body>
</html>