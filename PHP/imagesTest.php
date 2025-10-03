<?php
    session_start();

    $db = new mysqli("localhost", "root", "", "actev1");
    $sql = "SELECT * FROM games";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    echo '<img src="data:image/jpeg;base64,'.base64_encode($row['Caratula']).'"/>';
?>