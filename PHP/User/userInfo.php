<?php
    session_start();

    // Si no hay usuario logueado, redirigir o mostrar mensaje
    if (!isset($_SESSION["user"])) {
        header("Location: ../../index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Información del usuario</title>
        <link rel="stylesheet" href="../../CSS/mainStyle.css">
    </head>
    <body>
    <div class="user-info">
        <h1>Información del usuario</h1>

        <?php
            include "../connection.php";

            // Obtener datos del usuario actual
            $gmail = $_SESSION["gmail"];
            $stmt = $conn->prepare("SELECT nombre, gmail, userImg FROM users WHERE gmail = ?");
            $stmt->bind_param("s", $gmail);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Imagen del usuario
                echo '<img src="data:image/jpg;base64,' . base64_encode($row['userImg']) . '" alt="ImgUsuario">';

                // Nombre
                echo "<h2>Nombre de usuario:</h2>";
                echo "<p>" . htmlspecialchars($row['nombre']) . "</p>";

                // Gmail
                echo "<h2>Gmail:</h2>";
                echo "<p>" . htmlspecialchars($row['gmail']) . "</p>";
            }
        ?>

        <!-- Botones de acción -->
        <a href="editUser.php"><button>Editar perfil</button></a>
        <a href="../../index.php"><button>Volver</button></a>

        <!-- Mensajes de estado -->
        <?php
            if (isset($_SESSION["error"]) && $_SESSION["error"] != "") {
                echo "<p class='error'>Error: " . htmlspecialchars($_SESSION["error"]) . "</p>";
                $_SESSION["error"] = "";
            }

            if (isset($_SESSION["exito"]) && $_SESSION["exito"] != "") {
                echo "<p class='exito'>Éxito: " . htmlspecialchars($_SESSION["exito"]) . "</p>";
                $_SESSION["exito"] = "";
            }
        ?>
    </div>
    </body>
</html>