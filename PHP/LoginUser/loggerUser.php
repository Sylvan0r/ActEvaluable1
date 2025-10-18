<?php    
    session_start();
    include "../connection.php";

    // Limpiar sesión previa
    $_SESSION["user"] = null;
    $_SESSION["gmail"] = null;
    $_SESSION["passwd"] = null;

    // Obtener datos del formulario
    $gmail = $_POST["gmail"] ?? "";
    $password = $_POST["passwd"] ?? "";
    $remember = isset($_POST["remember"]); // checkbox

    // Consultar usuario
    $stmt = $conn->prepare("SELECT Nombre, Gmail, Password FROM users WHERE Gmail = ?");
    $stmt->bind_param("s", $gmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar contraseña
        if (password_verify($password, $user["Password"])) {
            $_SESSION["exito"] = "Sesión iniciada como " . $user["Nombre"];
            $_SESSION["user"] = $user["Nombre"];
            $_SESSION["gmail"] = $user["Gmail"];

            // ✅ Si el usuario marcó “Recordar sesión”
            if ($remember) {
                // Generar token seguro
                $token = bin2hex(random_bytes(32));

                // Guardar token en la BD
                $update = $conn->prepare("UPDATE users SET remember_token = ? WHERE Gmail = ?");
                $update->bind_param("ss", $token, $gmail);
                $update->execute();

                // Crear cookie válida por 7 días
                setcookie(
                    "remember_token",
                    $token,
                    time() + (86400 * 7), // 7 días
                    "/", 
                    "", // dominio actual
                    true, // Secure: solo HTTPS (puedes poner false si estás en local)
                    true  // HttpOnly: no accesible desde JS
                );
            }

            header("Location: ../../index.php");
            exit;
        } else {
            $_SESSION["error"] = "Gmail o contraseña ingresados incorrectos";
            header("Location: loginUser.php");
        }
    } else {
        $_SESSION["error"] = "Gmail o contraseña ingresados incorrectos";
        header("Location: loginUser.php");
    }
?>