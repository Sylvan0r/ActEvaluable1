<?php
    session_start();
    if (!isset($_SESSION["user"])){
        send();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Información usuario</title>
        <link rel="stylesheet" href="../../CSS/mainStyle.css">
    </head>
    <body>
        <h1>Información del usuario</h1>
        <!-- Sección del formulario -->
        <?php
            include "../connection.php";
            $result = $conn->query("SELECT nombre,gmail,userImg FROM users where gmail like '$_SESSION[gmail]'");
            while ($row = $result->fetch_assoc()) {
                echo '<img width="100" height="100" src="data:image/jpg;base64,' . base64_encode($row['userImg']) . '" alt="ImgUsuario">';                    
                echo "<h2>Nombre de usuario:</h2>";
                echo  $row['nombre'];           
                echo "<h2>Gmail:</h2>";
                echo $row['gmail'];                                    
            }            
        ?>   
        <br><br>
        
        <a href="editUser.php"><button>Editar perfil</button></a>
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