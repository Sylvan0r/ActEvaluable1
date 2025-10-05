<!-- Quitamos todo lo importante de sesion para que no tengan conflictos a futuro por si entran de otro lado -->
<?php    
    session_start();
    $_SESSION["user"] = null;
    $_SESSION["gmail"] = null;
    $_SESSION["passwd"] = null;

    comprobante();

    function comprobante(){
        include "../connection.php";
        /* Query que da todos los usuarios que tengan ese gmail introducido en la BD */
        $stmt = $conn->prepare("SELECT Nombre, Gmail, Password FROM users WHERE Gmail='$_POST[gmail]'");
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){
            /* Meter toda la info dentro del valor $array */
            while($row = mysqli_fetch_assoc( $result )){
                $array = $row;
            }
            /* Cuando todo sea correcto se iniciara sesion */
            if(password_verify($_POST["passwd"], $array["Password"]) && $_POST["passwd"] = $_POST["passwd2"]){
                $_SESSION["exito"] = "Sesión iniciada como ". $array["Nombre"];
                $_SESSION["user"] = $array["Nombre"];
                $_SESSION["gmail"] = $array["Gmail"];
                header("Location: ../../index.php");
            }else{
                $_SESSION["error"] = "Gmail o contraseña ingresados incorrectos";
                header("Location: loginUser.php");                    
            }
        }else{
            $_SESSION["error"] = "Gmail o contraseña ingresados incorrectos";
            header("Location: loginUser.php");
        }
    }
?>