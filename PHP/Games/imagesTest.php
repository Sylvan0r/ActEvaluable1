<?php
    session_start();
    include "../connection.php";

    $result = $conn->query("SELECT Caratula FROM games");
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $imageData = $row["Caratula"];
        
    }else{
        echo 'No image uploaded yet.';
    }  
    
    if($result->num_rows > 0){ 
        while($row = $result->fetch_assoc()){
            printf('<img src="data:image/jpg;charset=utf8;base64,%s" />', base64_encode($row['Caratula']));
        } 
    }else{ 
        echo 'no va';
    }
?>