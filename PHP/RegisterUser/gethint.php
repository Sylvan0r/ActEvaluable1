<?php
    $q = $_REQUEST["q"];

    $hint = "<ul>";
                   
    if($q != ""){
        if(strlen($q) <= 8){
            $hint .= "<li style='color:red'>Contraseña tiene menos de 8 caracteres</li>";
        }else{
            $hint .= "<li style='color:green'>Contraseña tiene menos de 8 caracteres</li>";
        }
        if(preg_match('/[0-9]/', $q) == 0) { 
            $hint .= "<li style='color:red'>La contraseña debe tener un numero</li>";
        }else{
            $hint .= "<li style='color:green'>La contraseña debe tener un numero</li>";
        }
        if(preg_match('/[a-z]/', $q) == 0) { 
            $hint .= "<li style='color:red'>La contraseña debe tener minusculas</li>";
        }else{
            $hint .= "<li style='color:green'>La contraseña debe tener minusculas</li>";
        }
        if(preg_match('/[A-Z]/', $q) == 0) { 
            $hint .= "<li style='color:red'>La contraseña debe tener mayusculas</li>";
        }else{
            $hint .= "<li style='color:green'>La contraseña debe tener mayusculas</li>";
        }
        if(preg_match('/[^a-zA-Z0-9]/', $q) == 0) { 
            $hint .= "<li style='color:red'>La contraseña debe tener caracteres especiales</li>";
        }else{
            $hint .= "<li style='color:green'>La contraseña debe tener caracteres especiales</li>";
        }
    } 

    $hint .= "</ul><br>";

    echo $hint === "" ? "Contraseña cumple los parametros" : $hint;
?>