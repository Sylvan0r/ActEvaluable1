<?php
    session_start();
    include "../connection.php";

    // Si el usuario tiene sesión iniciada, borramos su token de recordatorio
    if (isset($_SESSION["gmail"])) {
        $gmail = $_SESSION["gmail"];

        // Borrar el token de la base de datos para invalidar la cookie
        $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE Gmail = ?");
        $stmt->bind_param("s", $gmail);
        $stmt->execute();
    }

    // Destruir la sesión completamente
    session_unset();
    session_destroy();

    // Borrar la cookie de "Recordar sesión" del navegador
    if (isset($_COOKIE["remember_token"])) {
        setcookie("remember_token", "", time() - 3600, "/", "", true, true);
    }

    // Redirigir a la página principal
    header("Location: ../../index.php");
    exit();
?>