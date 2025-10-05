<?php
    session_start();
    include "../connection.php";

    if (!isset($_SESSION['gmail'])) {
        die("Acceso no autorizado.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);

            $stmt = $conn->prepare("SELECT * FROM games WHERE ID = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                if ($row['userID'] !== $_SESSION['gmail']) {
                    die("No tienes permiso para editar este juego.");
                }
                ?>
                <html>
                    <head>
                        <title>Edicion de <?php echo htmlspecialchars($row['Título'])?></title>
                        <link rel="stylesheet" href="../../CSS/mainStyle.css">
                    </head>
                    <body>
                        <h1>Editar Juego</h1>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">

                            <p>Título:</p>
                            <br>
                            <input type="text" name="titulo" value="<?php echo htmlspecialchars($row['Título']); ?>" required><br><br>

                            <p>Descripción:</p>
                            <br>
                            <textarea name="desc" required><?php echo htmlspecialchars($row['Descripción']); ?></textarea><br><br>

                            <p>Compañía:</p>
                            <br>
                            <input type="text" name="comp" value="<?php echo htmlspecialchars($row['Compañia']); ?>" required><br><br>

                            <p>Año:</p>
                            <br>
                            <input type="date" name="year" value="<?php echo $row['año']; ?>" required><br><br>

                            <p>Nueva imagen (opcional):</p>
                            <br>
                            <input type="file" name="image"><br><br>

                            <button type="submit">Guardar cambios</button>
                        </form>
                        <br>
                        <a href="gameDetails.php?id=<?php echo $row['ID']; ?>"><button>Cancelar</button></a>                    
                    </body>
                </html>

                <?php
            } else {
                echo "Juego no encontrado.";
            }
        } else {
            echo "ID inválido.";
        }

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (
            isset($_POST['id'], $_POST['titulo'], $_POST['desc'], $_POST['comp'], $_POST['year']) &&
            is_numeric($_POST['id'])
        ) {
            $id = intval($_POST['id']);

            $stmt = $conn->prepare("SELECT userID FROM games WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($row = $res->fetch_assoc()) {
                if ($row['userID'] !== $_SESSION['gmail']) {
                    die("No tienes permiso para editar este juego.");
                }

                $titulo = $_POST['titulo'];
                $desc = $_POST['desc'];
                $comp = $_POST['comp'];
                $year = $_POST['year'];

                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $image = file_get_contents($_FILES['image']['tmp_name']);

                    $update = $conn->prepare("UPDATE games SET Título=?, Descripción=?, Compañia=?, año=?, Caratula=? WHERE ID=?");
                    $update->bind_param("ssssss", $titulo, $desc, $comp, $year, $image, $id);
                } else {
                    $update = $conn->prepare("UPDATE games SET Título=?, Descripción=?, Compañia=?, año=? WHERE ID=?");
                    $update->bind_param("sssss", $titulo, $desc, $comp, $year, $id);
                }

                $update->execute();
                $update->close();

                header("Location: gameDetails.php?id=" . $id);
                exit();

            } else {
                echo "Juego no encontrado.";
            }
        } else {
            echo "Datos incompletos.";
        }
    }
?>