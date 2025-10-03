<?php
    session_start();
    if($_SESSION["user"] == null){
        header("Location: loginUser.php");
        $_SESSION["error"] = "No se ha iniciado sesion";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Juegos</title>
    </head>
    <body>
        <h1>Registro de juego</h1>

        <form action="insertGame.php" method="post">
            <input name="name" type="text" placeholder="Nombre del juego">
            <br><br>            
            <input name="desc" placeholder="Introduzca una descripción breve">
            <br><br>
            <input name="comp" placeholder="Compañia creadora">
            <br><br>
            <input name="carat" type="file" placeholder="Introduzca su caratula">
            <br><br>
            <input name="date" type="date" placeholder="Introduzca su fecha de salida">
            <br><br>                        
            <button type="submit">Registrar juego</button>
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