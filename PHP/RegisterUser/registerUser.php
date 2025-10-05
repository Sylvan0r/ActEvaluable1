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
        <link rel="stylesheet" href="../../CSS/mainStyle.css">
    </head>
    <body>
        <h1>Actividad Evaluable 1</h1>

        <form action="insertUser.php" method="post">
            <input name="user" type="text" placeholder="Introduzca su usuario">
            <br><br>            
            <input name="gmail" placeholder="Introduzca su Gmail">
            <br><br>
            <input name="passwd" type="password" placeholder="Introduzca su contraseña">
            <br><br>
            <button type="submit">Registrar usuario</button>
        </form>
        <br>
        <a href="../../index.php"><button>Volver</button></a>

        <?php
            if(isset($_SESSION["error"]) && $_SESSION["error"] != "") {
                echo "<br>";
                echo "<p class='error'>Error: " . $_SESSION["error"] . "</p>";
                $_SESSION["error"] = "";
            }
            if(isset( $_SESSION["exito"]) && $_SESSION["exito"] != "") {
                echo "Exito: " . $_SESSION["exito"];
                $_SESSION["exito"] = "";
            }
        ?>        
    </body>
</html>