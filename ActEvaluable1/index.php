<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>?</title>
    </head>
    <body>
        <h1>Actividad Evaluable 1</h1>

        <form action="comprobante.php" method="post">
            <input name="email" type="email" placeholder="Introduzca su Gmail">
            <br><br>
            <input name="passwd" type="password" placeholder="Introduzca su contraseña">
            <br><br>
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