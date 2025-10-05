<?php    
    session_start();
    $_SESSION["user"] = null;
    $_SESSION["gmail"] = null;
    $_SESSION["passwd"] = null;

    comprobante();

    function comprobante(){
        include "../connection.php";

        try{                        
            $stmt = $conn->prepare("SELECT Nombre, Gmail, Password FROM users WHERE Gmail='$_POST[gmail]'");
            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows > 0){
                while($row = mysqli_fetch_assoc( $result )){
                    $array = $row;
                }
                if(password_verify($_POST["passwd"], $array["Password"])){
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
        }catch(PDOException $e){
            $_SESSION["error"] = $e->getMessage();
        }
    }
?>