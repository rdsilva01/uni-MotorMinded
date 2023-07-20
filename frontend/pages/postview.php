<?php
session_start();
// Verificar se a variável de sessão está definida
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: /project/frontend/pages/index.php');
    exit();
}


// Verificar se a cookie não está presente
if (!isset($_COOKIE['user'])) {
    // Redirecionar o usuário de volta para o index
    header('Location: /project/frontend/pages/index.php');
    exit();
}

$postid = $_GET['post'] ?? null;
if (empty($postid)) {
    header('Location: /project/frontend/pages/home.php');
    exit();
}

echo "<script> var postID = " . $postid . "; </script>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Post</title>
</head>

<body style="background-color: #1c2433;">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1c2433;">
        <div class="container-fluid">
            <a class="navbar-brand" href="../pages/home.php">
                <img src="../images/motorminded5.png" alt="Logo" width="240" height="auto" class="d-inline-block align-text-top">
            </a>
            <div class="d-flex">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                    <li class="nav-item me-2">
                        <a class="btn btn-warning" href="./home.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z" />
                            </svg></a>
                    </li>
                    <li class="nav-item me-4">
                        <div class="btn-group" role="group" aria-label="user">
                            <button class="logout btn btn-danger"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                                </svg></button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-2 bg-white rounded-4 p-5 w-100 mb-5">

        <div class="container post-container text-center">

        </div>
    </div>

    <script src="../scripts/postview.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>