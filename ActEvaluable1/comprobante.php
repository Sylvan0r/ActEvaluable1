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
        header("Location: index.php");        
    }

    function validarGmail(){
        if(isset($_POST["gmail"]) && filter_var($_POST["gmail"], FILTER_VALIDATE_EMAIL)){
            if($_POST["gmail"] != null){
                $_SESSION["gmail"] = $_POST["gmail"];
                validarPasswd();
            }else{
                $_SESSION["error"] = "Gmail incorrecto";
                header("Location: index.php");                    
            }            
        }else{
            $_SESSION["error"] = "Gmail invalido";
            header("Location: index.php");        
        }
    }

    function validarPasswd(){
        if(isset($_POST["passwd"])){
                $_SESSION["passwd"] = $_POST["passwd"];
                send();
                $_SESSION["error"] = "Confirmacion de contraseña incorrecta";
                header("Location: index.php");                        
        }else{
            $_SESSION["error"] = "Contraseña incorrecta";
            header("Location: index.php");                    
        }
    }

    function send(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "test";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);     

        $stmt = $conn->prepare("INSERT INTO MyGuests (firstname, email, passwd) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $firstname, $email, $password);

        $firstname = $_SESSION["user"];
        $email = $_SESSION["gmail"];
        $password = $_SESSION["passwd"];
        $stmt->execute(); 
        
        $_SESSION["exito"] = "El usuario ha sido añadido a la BD";
        header("Location: index.php");        
    }
?>