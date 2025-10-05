<?php    
    session_start();
    $_SESSION["user"] = null;
    $_SESSION["gmail"] = null;
    $_SESSION["passwd"] = null;

    comprobante();

    function comprobante(){
        include "../connection.php";

        try{            
            $email = $_POST["gmail"];
            $password = $_POST["passwd"];

            $stmt = $conn->prepare("SELECT Nombre, Gmail, Password FROM users WHERE Gmail='$email' AND Password='$password'");
            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows > 0){
                while($row = mysqli_fetch_assoc( $result )){
                    $array = $row;
                }
                if($_POST["passwd"] == $array["Password"] && $_POST["passwd2"] == $array["Password"]){
                    $_SESSION["exito"] = "funciona";
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