<?php    
    session_start();
    $_SESSION["user"] = null;
    $_SESSION["gmail"] = null;
    $_SESSION["passwd"] = null;
    $_SESSION["error"] = null;
    $_SESSION["exito"] = null;

    comprobante();

    function comprobante(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "actev1";

        $conn = new mysqli($servername, $username, $password, $dbname);   
        
        $email = $_POST["email"];
        $password = $_POST["passwd"];

        $stmt = $conn->prepare("SELECT Gmail, Password FROM users WHERE Gmail='$email' AND Password='$password'");
        $stmt->execute();
        
        echo $stmt->get_result();
    }
?>