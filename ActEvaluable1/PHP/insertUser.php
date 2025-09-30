<?php
    session_start();
    $_SESSION["user"] = null;
    $_SESSION["gmail"] = null;
    $_SESSION["passwd"] = null;
    $_SESSION["error"] = null;
    $_SESSION["exito"] = null;

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
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "actev1";
        
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmtcr = $conn->prepare("INSERT INTO users(Nombre,Gmail,Password) VALUES (?,?,?)");
        $stmtcr->bind_param("sss", $_SESSION["user"], $_SESSION["gmail"], $_SESSION["passwd"]);        
        $stmtcr->execute();
        $stmtcr->close();  
        $_SESSION["exito"] = "Cuenta creada con exito";
        header("Location: registerUser.php");                    
    }
?>