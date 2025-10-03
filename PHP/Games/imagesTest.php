<?php
    session_start();
    include "../connection.php";

    $query = "SELECT * FROM games";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows( $result ) > 0) {
        while( $row = mysqli_fetch_assoc($result) ) {
            ?>
                <img src="<?php echo $row['Caratula']; ?>" height="200px" width="200px" />
            <?php
        }
    }
?>