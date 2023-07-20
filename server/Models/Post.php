<?php
header("Access-Control-Allow-Origin: http://localhost:8888/project/frontend/pages");

header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: plain/text');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods,Access-Control-Allow-Origin, Access-Control-Allow-Credentials, Authorization, X-Requested-With");


include_once "../db.inc.php";
include_once "../refactors.inc.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'create')
    createPost($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'PUT')
    updatePost($conn);

else if ($_SERVER['REQUEST_METHOD'] === 'DELETE')
    deletePost($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'all')
    getAllPosts($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'user')
    getUserPosts($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'numPosts')
    countUserPosts($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'mostBrand')
    getMostBrand($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'numPostsAll')
    countAllPosts($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'numBrands')
    getBrands($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'mostPosts')
    UsersWithMostPosts($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'onePost')
    getIdPost($conn);


function getMostBrand($conn)
{
    $sqlCarBrand = "SELECT maker, COUNT(*) AS total_posts FROM posts GROUP BY maker ORDER BY total_posts DESC LIMIT 1;";
    $stmtCarBrand = $conn->prepare($sqlCarBrand);
    $stmtCarBrand->execute();
    $resultCarBrand = $stmtCarBrand->get_result();
    $dataCarBrand = $resultCarBrand->fetch_assoc();
    $mostPostedCarBrand = $dataCarBrand['maker'];

    if (empty($mostPostedCarBrand)) {
        http_response_code(404);
        echo json_encode("Nenhuma marca encontrada");
        exit();
    }

    http_response_code(200);
    echo json_encode($mostPostedCarBrand);
    exit();
}
/*
else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'search')
    searchPosts($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'logout')
    logout($conn);*/


// Função para obter o número de posts totais
function countAllPosts($conn)
{
    $sqlNumPosts = "SELECT COUNT(*) AS total_posts FROM posts;";
    $stmtNumPosts = $conn->prepare($sqlNumPosts);

    if (!$stmtNumPosts) {
        http_response_code(500);
        echo json_encode("Erro ao preparar a consulta");
        exit();
    }
    if (!$stmtNumPosts->execute()) {
        http_response_code(500);
        echo json_encode("Erro ao executar a consulta");
        exit();
    }

    $resultNumPosts = $stmtNumPosts->get_result();
    $dataNumPosts = $resultNumPosts->fetch_assoc();
    $totalPosts = $dataNumPosts['total_posts'];

    if (empty($totalPosts)) {
        http_response_code(404);
        echo json_encode("Nenhum post encontrado");
        exit();
    }

    http_response_code(200);
    echo json_encode($totalPosts); // Enviar a resposta como JSON
    exit();
}


// Função para criar um post
function createPost($conn)
{
    // Verifique se o usuário está autenticado
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo "Usuário não autenticado.";
        return;
    }

    $post_title = $_POST['post_title'];
    $post_descrip  = $_POST['post_descrip'];
    $post_maker = $_POST['post_maker'];
    $post_model = $_POST['post_model'];
    $post_user_id = $_SESSION['user_id'];


    // Verifique se o arquivo de imagem foi enviado corretamente
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $temp = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];

        // Defina o diretório de destino onde a imagem será armazenada
        $diretorio_destino = "../../storage/uploads/";

        // Gere um nome de arquivo único para evitar conflitos
        $nome_arquivo = uniqid() . '_' . $image_name;

        // Mova o arquivo temporário para o diretório de destino com o nome gerado
        if (move_uploaded_file($temp, $diretorio_destino . $nome_arquivo)) {
            $image_path = $diretorio_destino . $nome_arquivo;
            echo "Imagem enviada com sucesso. Caminho: " . $diretorio_destino . $nome_arquivo;

            $sql = "Insert into posts (user_id, image_path, descrip, title, maker, model) values (?, ?, ?, ?, ?, ?);";
            $stmt = $conn->stmt_init();
            if (!$stmt->prepare($sql)) {
                echo "something went wrong!!!";
                exit();
            }
            $stmt->bind_param('ssssss', $post_user_id, $image_path, $post_descrip, $post_title, $post_maker, $post_model);
            $stmt->execute();
            if ($stmt->affected_rows) {
                http_response_code(200);
                echo "Congratulation!!\n Post sent successfully\n";
            }
            exit();
        } else {
            echo "Falha ao enviar a imagem.";
        }
    } else {
        echo "Erro ao receber a imagem.";
    }
}

// Função para atualizar um post
function updatePost($conn)
{
    // Verifique se o usuário está autenticado
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo "Usuário não autenticado.";
        return;
    }

    // Recupere os dados do post do corpo da requisição
    $data = json_decode(file_get_contents('php://input'), true);

    // Execute as ações necessárias para atualizar o post
    // ...

    // Retorne a resposta adequada
    http_response_code(200); // OK
    echo "Post atualizado com sucesso.";
}

function deletePost($conn)
{
    $sql = "DELETE FROM posts WHERE id = ?;";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo "Erro ao preparar a consulta SQL";
        exit();
    }

    $post_id = $_GET['id'];

    $stmt->bind_param('s', $post_id);

    if (!$stmt->execute()) {
        http_response_code(500); // Internal Server Error
        echo "Erro ao executar a consulta SQL";
        exit();
    }

    if ($stmt->affected_rows > 0) {
        http_response_code(200); // OK
        echo "Post excluído com sucesso.";
        exit();
    } else {
        http_response_code(404); // Not Found
        echo "Post não encontrado.";
        exit();
    }
}

// Função para obter todos os posts
function getAllPosts($conn)
{
    $sql = "SELECT posts.*, users.user_name FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY posts.id DESC;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $allPosts = array();

    while ($row = $result->fetch_assoc()) {
        $allPosts[] = $row;
    }

    if (empty($allPosts)) {
        http_response_code(404);
        echo "Nenhum post encontrado";
        exit();
    }

    http_response_code(200);
    echo json_encode($allPosts);
    exit();
}


function getUserPosts($conn)
{
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo "ID do usuário não fornecido";
        exit();
    }

    $id = $_GET['id'];
    $sql = "SELECT posts.*, users.user_name FROM posts INNER JOIN users ON posts.user_id = users.id WHERE users.id = ? ORDER BY posts.id DESC;";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo "Erro ao preparar a consulta SQL";
        exit();
    }

    $stmt->bind_param('s', $id);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo "Erro ao executar a consulta SQL";
        exit();
    }

    $result = $stmt->get_result();

    $allPosts = array();

    while ($row = $result->fetch_assoc()) {
        $allPosts[] = $row;
    }

    if (empty($allPosts)) {
        http_response_code(404);
        echo "Nenhum post encontrado para o usuário com o ID: " . $id;
        exit();
    }

    http_response_code(200);
    echo json_encode($allPosts);
    exit();
}

function countUserPosts($conn)
{
    $sql = "SELECT COUNT(*) AS post_count FROM posts WHERE user_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo "Erro ao preparar a consulta SQL";
        exit();
    }

    $userId = $_GET['id'];

    $stmt->bind_param('s', $userId);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo "Erro ao executar a consulta SQL";
        exit();
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $postCount = $row['post_count'];

    http_response_code(200);
    echo json_encode($postCount);
    exit();
}

function getBrands($conn)
{
    $sql = "SELECT maker, COUNT(*) AS post_count FROM posts GROUP BY maker;";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo "Erro ao preparar a consulta SQL";
        exit();
    }

    if (!$stmt->execute()) {
        http_response_code(500);
        echo "Erro ao executar a consulta SQL";
        exit();
    }

    $result = $stmt->get_result();
    $brands = array();

    while ($row = $result->fetch_assoc()) {
        $brand = array(
            'maker' => $row['maker'],
            'post_count' => $row['post_count']
        );
        $brands[] = $brand;
    }

    if (empty($brands)) {
        http_response_code(404);
        echo "Nenhuma marca encontrada";
        exit();
    }

    http_response_code(200);
    echo json_encode($brands);
    exit();
}

function UsersWithMostPosts($conn)
{
    $sql = "SELECT users.user_name, COUNT(posts.id) AS total_posts FROM users INNER JOIN posts ON users.id = posts.user_id GROUP BY users.id ORDER BY total_posts DESC LIMIT 10;";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo "Erro ao preparar a consulta SQL";
        exit();
    }

    if (!$stmt->execute()) {
        http_response_code(500);
        echo "Erro ao executar a consulta SQL";
        exit();
    }

    $result = $stmt->get_result();
    $topUsers = array();

    while ($row = $result->fetch_assoc()) {
        $user = array(
            'user_name' => $row['user_name'],
            'total_posts' => $row['total_posts']
        );
        $topUsers[] = $user;
    }

    if (empty($topUsers)) {
        http_response_code(404);
        echo "Nenhum usuário encontrado";
        exit();
    }

    http_response_code(200);
    echo json_encode($topUsers);
    exit();
}

function getIdPost($conn)
{
    $sql = "SELECT posts.*, users.user_name FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.id = ?;";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo "Erro ao preparar a consulta SQL";
        exit();
    }

    $post_id = $_GET['id'];

    $stmt->bind_param('s', $post_id);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo "Erro ao executar a consulta SQL";
        exit();
    }

    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    if (empty($post)) {
        http_response_code(404);
        echo "Nenhum post encontrado";
        exit();
    }

    http_response_code(200);
    echo json_encode($post);
    exit();
}

