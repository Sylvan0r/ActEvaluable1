<!-- Si no hay nadie como login entonces nos redirige a la pagina -->
<?php
    session_start();
    if (!isset($_SESSION["user"])){
        $_SESSION["error"] = "Inicie sesion para ver los juegos";
        header("Location: ../LoginUser/loginUser.php");
    }    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registro de juegos</title>
        <link rel="stylesheet" href="../../CSS/mainStyle.css">
    </head>
    <body>
        <h1>Registro de juego</h1>
        <!-- Sección del formulario -->
        <form action="insertGame.php" method="post" enctype="multipart/form-data">
            <input name="name" type="text" placeholder="Nombre del juego">
            <br><br>            
            <input name="desc" placeholder="Introduzca una descripción breve">
            <br><br>
            <input name="comp" placeholder="Compañia creadora">
            <br><br>            
            <input class="img" name="image" type="file" accept="image/*" placeholder="Introduzca su caratula">
            <br><br>
            <input name="date" type="date" placeholder="Introduzca su fecha de salida">
            <br><br>                        
            <button type="submit">Registrar juego</button>
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