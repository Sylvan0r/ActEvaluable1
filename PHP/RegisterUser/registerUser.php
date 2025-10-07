<!-- Quitamos todo lo importante de sesion para que no tengan conflictos a futuro por si entran de otro lado -->
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
        <!-- Sección del formulario -->
        <form action="insertUser.php" method="post">
            <p style="color:white">Usuario: </p>
            <input name="user" type="text" placeholder="Introduzca su usuario">
            <br><br>   
            <p style="color:white">Gmail: </p>         
            <input name="gmail" placeholder="Introduzca su Gmail">
            <br><br>
            <p style="color:white">Contraseña: </p>
            <input name="passwd" type="password" placeholder="Introduzca su contraseña">
            <br><br>
            <p style="color:white">Contraseña otra vez: </p>
            <input name="passwd2" type="password" placeholder="Introduzca su contraseña nuevamente">
            <br><br>
            <button type="submit">Registrar usuario</button>
        </form>
        <br>
        <a href="../../index.php"><button>Volver</button></a>

        <!-- Sección de errores y exitos -->        
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