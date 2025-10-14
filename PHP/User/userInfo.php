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
            $result = $conn->query("SELECT userImg FROM users where Gmail like '$_SESSION[gmail]'");
            while ($row = $result->fetch_assoc()) {
                echo '<img width="100" height="100" src="data:image/jpg;base64,' . base64_encode($row['userImg']) . '" alt="ImgUsuario">';                    
            }
        ?> 
        <h2>Nombre de usuario:</h2>
        <?php
            include "../connection.php";
            $result = $conn->query("SELECT nombre FROM users where nombre like '$_SESSION[user]'");
            while ($row = $result->fetch_assoc()) {
                echo  $row['nombre'];                    
            }            
        ?>
        <h2>Gmail:</h2>
        <?php
            include "../connection.php";
            $result = $conn->query("SELECT gmail FROM users where gmail like '$_SESSION[gmail]'");
            while ($row = $result->fetch_assoc()) {
                echo $row['gmail'];                    
            }            
        ?>        
        <br>
        <?php
            include "../connection.php";
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("SELECT id FROM users WHERE ID = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();        
            if(!isset($_SESSION["gmail"])){
                echo "";
            }else{
                if ($row['userID'] === $_SESSION["gmail"]) {
                    echo '<div class="game-actions">';
                    echo '<a href="editGame.php?id=' . urlencode($_GET['nombre']) . '" class="btn edit">Editar</a>';
                    echo '<a href="deleteGame.php?id=' . urlencode($_GET['nombre']) . '" class="btn delete" onclick="return confirm(\'¿Estás seguro de que quieres borrar este juego?\');">Borrar</a>';
                    echo '</div>';
                }
            }        
        ?>
        
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