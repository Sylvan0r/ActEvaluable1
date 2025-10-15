<?php
session_start();
include "../connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    validator();
}

function validator(){
    global $conn;

    if (!isset($_SESSION['gmail'])) {
        $_SESSION["error"] = "Sesión no iniciada";
        header("Location: login.php");
        exit();
    }

    $gmail = $_SESSION['gmail'];

    // Consulta segura
    $stmt = $conn->prepare("SELECT Password FROM users WHERE Gmail = ?");
    $stmt->bind_param("s", $gmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row && password_verify($_POST["currentPass"], $row["Password"])) {
        if ($_POST["newPasswd"] === $_POST["newPasswd2"]) {
            update();
        } else {
            $_SESSION["error"] = "Las nuevas contraseñas no coinciden";
            header("Location: editUser.php");
            exit();
        }
    } else {
        $_SESSION["error"] = "Contraseña actual incorrecta";
        header("Location: editUser.php");
        exit();
    }
}

function update(){
    global $conn;

    $gmail = $_SESSION['gmail'];
    $newName = $_POST["newName"];
    $newPasswordHash = password_hash($_POST["newPasswd"], PASSWORD_DEFAULT);
    $imgContent = null;

    // Procesar imagen
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES["image"]["tmp_name"];
        $imgContent = file_get_contents($image);
    } else {
        $stmt = $conn->prepare("SELECT userImg FROM users WHERE Gmail = ?");
        $stmt->bind_param("s", $gmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $imgContent = $row["userImg"];
        $stmt->close();
    }

    // Actualizar usuario
    $stmt = $conn->prepare("UPDATE users SET nombre = ?, Password = ?, userImg = ? WHERE Gmail = ?");
    $stmt->bind_param("ssss", $newName, $newPasswordHash, $imgContent, $gmail);
    $stmt->execute();
    $stmt->close();

    $_SESSION["exito"] = "Usuario actualizado exitosamente";
    header("Location: editUser.php");
    exit();
}
?>