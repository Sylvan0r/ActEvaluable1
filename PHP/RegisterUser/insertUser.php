<?php
    session_start();
    $_SESSION["user"] = null;
    $_SESSION["gmail"] = null;
    $_SESSION["passwd"] = null;


    if(isset($_POST["user"]) && $_POST["user"]!=null){
        $_SESSION["user"] = $_POST["user"];
        validarGmail();
    }else{
        $_SESSION["error"] = "Usuario invalido";
        header("Location: registerUser.php");        
    }

    function validarGmail(){
        if(isset($_POST["gmail"]) && filter_var($_POST["gmail"], FILTER_VALIDATE_EMAIL)){
            if($_POST["gmail"] != null){
                $_SESSION["gmail"] = $_POST["gmail"];
                validarPasswd();
            }else{
                $_SESSION["error"] = "Gmail incorrecto";
                header("Location: registerUser.php");                    
            }            
        }else{
            $_SESSION["error"] = "Gmail invalido";
            header("Location: registerUser.php");        
        }
    }

    function validarPasswd(){
        if((isset($_POST["passwd"]) && $_POST["passwd"]!=null)){
            $_SESSION["passwd"] = $_POST["passwd"];
            insert();
        }else{
            $_SESSION["error"] = "La contraseña es obligatoria";
            header("Location: registerUser.php");                    
        }
    }


    function insert(){
        include "../connection.php";
        
        $check = $conn->prepare("SELECT Gmail FROM users WHERE Gmail = ?");
        $check->bind_param("s", $_SESSION["gmail"]);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $_SESSION["error"] = "Ya existe una cuenta registrada con ese Gmail.";
            header("Location: registerUser.php");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("INSERT INTO users(Nombre,Gmail,Password) VALUES (?,?,?)");
        $stmt ->bind_param("sss", $_SESSION["user"], $_SESSION["gmail"], $_SESSION["passwd"]) ;
        $stmt->execute();

        $_SESSION["exito"] = "Cuenta creada con exito";
        header("Location: ../../index.php");                    
    }
?>