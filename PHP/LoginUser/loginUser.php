<?php
    session_start();
    $_SESSION["user"] = null;
    $_SESSION["gmail"] = null;
    $_SESSION["passwd"] = null;
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Inicio de sesión</title>
        <link rel="stylesheet" href="../../CSS/mainStyle.css">
    </head>

    <body>
        <h1>Inicio de sesión</h1>

        <form action="loggerUser.php" method="post">        
            <p style="color:white">Gmail: </p> 
            <input name="gmail" placeholder="Introduzca su Gmail" required>
            <br><br>
            <p style="color:white">Contraseña: </p>
            <input name="passwd" type="password" placeholder="Introduzca su contraseña" required>
            <br><br>

            <div id="checkbox">
                <input type="checkbox" name="remember" value="1">
                <label for="remember">Recordar sesión</label>
            </div>
            <br>

            <button type="submit">Iniciar sesión</button>
        </form>

        <br>
        <a href="../../index.php"><button>Volver</button></a>

        <?php
            if(isset($_SESSION["error"]) && $_SESSION["error"] != "") {
                echo "<br><p class='error'>Error: " . $_SESSION["error"] . "</p>";
                $_SESSION["error"] = "";
            }
            if(isset($_SESSION["exito"]) && $_SESSION["exito"] != "") {
                echo "<br><p>Éxito: " . $_SESSION["exito"] . "</p>";
                $_SESSION["exito"] = "";
            }
        ?>        
    </body>
</html>
