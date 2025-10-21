<?php
    session_start();
    include "../connection.php";

    // Validar que el usuario está logueado
    if (!isset($_SESSION['gmail'])) {
        header("Location: login.php");
        exit();
    }

    // Preparar la consulta para evitar inyecciones SQL
    $gmail = $_SESSION['gmail'];
    $stmt = $conn->prepare("SELECT nombre, gmail, password, userImg FROM users WHERE gmail = ?");
    $stmt->bind_param("s", $gmail);
    $stmt->execute();
    $result = $stmt->get_result();

    // Obtener los datos del usuario
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Editor de usuario</title>
        <link rel="stylesheet" href="../../CSS/mainStyle.css">
    </head>
    <body>
        <?php if ($user): ?>
            <form action="updateUser.php" method="POST" enctype="multipart/form-data">
                <h2>Nombre del usuario:</h2>
                <input type="text" name="newName" placeholder="Introduzca su nuevo nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>

                <h2>Introduzca su contraseña actual</h2>
                <input type="password" name="currentPass" placeholder="Introduzca su contraseña antigua" required>

                <h2>Introduzca su contraseña nueva</h2>
                <input type="password" name="newPasswd" placeholder="Introduzca su nueva contraseña">

                <h2>Introduzca su contraseña nueva</h2>
                <input type="password" name="newPasswd2" placeholder="Introduzca su nueva contraseña otra vez">

                <h2>Nueva imagen de perfil</h2>
                <input class="img" name="image" type="file" accept="image/*" placeholder="Introduzca su imagen de usuario">

                <button type="submit">Actualizar</button>
            </form>
            <br>
            <a href="userInfo.php"><button>Volver</button></a>

        <?php else: ?>

            <p>No se encontró la información del usuario.</p>
        
        <?php endif; ?>

        <?php
            if(isset($_SESSION["error"]) && $_SESSION["error"] != "") {
                echo "<br>";
                echo "<p class='error'>Error: " . $_SESSION["error"] . "</p>";
                $_SESSION["error"] = "";
            }
            if(isset( $_SESSION["exito"]) && $_SESSION["exito"] != "") {
                echo "<br>";
                echo "<p class='exito'>Esta usted iniciado como: ".$_SESSION["user"] . "</p>";
                $_SESSION["exito"] = "";
            }
        ?>      
    </body>
</html>