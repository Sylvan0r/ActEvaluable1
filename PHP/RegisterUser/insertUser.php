<!-- Quitamos todo lo importante de sesion para que no tengan conflictos a futuro por si entran de otro lado -->
<?php
    session_start();
    $_SESSION["user"] = null;
    $_SESSION["gmail"] = null;
    $_SESSION["passwd"] = null;

    validarName();

    /* Funcion de comprobación del input del nombre */
    function validarName(){
        if(isset($_POST["user"]) && $_POST["user"]!=null){
            $_SESSION["user"] = $_POST["user"];
            validarGmail();
        }else{
            $_SESSION["error"] = "Usuario invalido";
            header("Location: registerUser.php");        
        }
    }

    /* Funcion comprobante de si gmail es valido o esta dentro de la BD */
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
        if(isset($_POST["passwd"]) && $_POST["passwd"] != null && $_POST["passwd"] == $_POST["passwd2"]){
            $password = $_POST["passwd"];

            // Verificar longitud mínima
            if(strlen($password) < 8){
                $_SESSION["error"] = "La contraseña tiene menos de 8 caracteres";
                header("Location: registerUser.php");
                exit();
            }

            // Verificar que tenga al menos un número
            if(!preg_match("/[0-9]/", $password)){
                $_SESSION["error"] = "La contraseña debe tener al menos un número";
                header("Location: registerUser.php");
                exit();
            }

            // Verificar que tenga al menos una letra minúscula
            if(!preg_match("/[a-z]/", $password)){
                $_SESSION["error"] = "La contraseña debe tener al menos una letra minúscula";
                header("Location: registerUser.php");
                exit();
            }

            // Verificar que tenga al menos una letra mayúscula
            if(!preg_match("/[A-Z]/", $password)){
                $_SESSION["error"] = "La contraseña debe tener al menos una letra mayúscula";
                header("Location: registerUser.php");
                exit();
            }

            // Verificar que tenga al menos un carácter especial
            if(!preg_match("/[\W_]/", $password)){ // \W = cualquier caracter no alfanumérico, _ incluido
                $_SESSION["error"] = "La contraseña debe tener al menos un carácter especial";
                header("Location: registerUser.php");
                exit();
            }

            // Si pasa todas las validaciones, se hace hash
            $passwdHash = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION["passwd"] = $passwdHash;
            insert();
        } else {
            $_SESSION["error"] = "La contraseña es obligatoria y debe coincidir";
            header("Location: registerUser.php");
            exit();
        }
    }

    /* Insercion de usuario en la BD */
    function insert(){
        include "../connection.php";
        
        $check = $conn->prepare("SELECT Gmail FROM users WHERE Gmail = ?");
        $check->bind_param("s", $_SESSION["gmail"]);
        $check->execute();
        $result = $check->get_result();

        /* Comprobante de si es que ya existe el Gmail dentro de la BD */
        if ($result->num_rows > 0) {
            $_SESSION["error"] = "Ya existe una cuenta registrada con ese Gmail.";
            header("Location: registerUser.php");
            exit();
        }
        $check->close();

        /* Inserción dentro de la BD */
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES["image"]["tmp_name"];
            $imgContent = file_get_contents($image);
            $imageHash = hash('sha256', $imgContent);
        } else {
            $defaultImagePath = '../../IMG/1053244.png'; 
            $imgContent = file_get_contents($defaultImagePath);
        }

        $stmt = $conn->prepare("INSERT INTO users(Nombre,Gmail,Password,userImg) VALUES (?,?,?,?)");
        $stmt ->bind_param("ssss", $_SESSION["user"], $_SESSION["gmail"], $_SESSION["passwd"],$imgContent) ;
        $stmt->execute();

        $_SESSION["exito"] = "Cuenta creada con exito";
        header("Location: ../../index.php");                    
    }
?>