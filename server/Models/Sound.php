<?php
// this header needs to set according to where your frontend is running
header("Access-Control-Allow-Origin: http://localhost:8888/project/frontend/pages");

header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: plain/text');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods,Access-Control-Allow-Origin, Access-Control-Allow-Credentials, Authorization, X-Requested-With");

include_once "../db.inc.php";
include_once "../refactors.inc.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST')
    addSound($conn);
/*
else if ($_SERVER['REQUEST_METHOD'] == 'DELETE');
//removeSound($conn);*/

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'getAllSounds')
    getAllSounds($conn);
/*
else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'getSound');
//getSound($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_GET['crud_req'] == 'updateSound');
// updateSound($conn);*/


function addSound($conn)
{
    // Check if the request contains a file
    if (!isset($_FILES['soundFile'])) {
        http_response_code(400); // Bad Request
        echo "No file uploaded.";
        return;
    }

    $name = $_POST['soundName'];
    $file = $_FILES['soundFile'];

    // Check if the file upload was successful
    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(500); // Internal Server Error
        echo "Error uploading file.";
        return;
    }

    // Move the uploaded file to a desired location
    $targetDirectory = "../../sounds/";
    $targetFile = $targetDirectory . basename($file['name']);
    $normal = basename($file['name']);
    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        http_response_code(500); // Internal Server Error
        echo "Error moving file.";
        return;
    }

    // Prepare the statement to insert the sound information into the database
    $stmt = $conn->prepare("INSERT INTO sounds (name, file) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $normal);

    if ($stmt->execute()) {
        http_response_code(200); // Success
        echo "Sound added successfully.";
    } else {
        http_response_code(500); // Internal Server Error
        echo "Error adding sound: " . $stmt->error;
    }

    $stmt->close();
}





function getAllSounds($conn)
{
    // Seleciona todos os sons da tabela sounds
    $sql = "SELECT * FROM sounds";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Verifica se existem sons na base de dados
        if (mysqli_num_rows($result) > 0) {
            $sounds = array();

            // Itera sobre os resultados e adiciona cada som ao array
            while ($row = mysqli_fetch_assoc($result)) {
                $sounds[] = array(
                    "id" => $row["id"],
                    "name" => $row["name"],
                    "file" => $row["file"]
                );
            }

            http_response_code(200); // Código de resposta para sucesso
            echo json_encode($sounds); // Retorna os sons como resposta JSON
        } else {
            http_response_code(404); // Código de resposta para não encontrado
            echo "No sounds found.";
        }
    } else {
        http_response_code(500); // Código de resposta para erro do servidor
        echo "Error getting sounds: " . mysqli_error($conn);
    }

    mysqli_free_result($result);
}
