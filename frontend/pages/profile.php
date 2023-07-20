<?php
session_start();

// Verificar se a variável de sessão está definida
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

// Verificar se a cookie não está presente
if (!isset($_COOKIE['user'])) {
    // Redirecionar o usuário de volta para o index
    header('Location: /project/frontend/pages/index.php');
    exit();
}

$id = $_GET['id'];
if (empty($id)) {
    header('Location: /project/frontend/pages/home.php');
    exit();
} // ID do usuário (substitua pelo valor adequado)

if ($id != $user_id) {
    echo "<script> var isOwner = false; </script>";
}

// Imprime o valor do ID no JavaScript
echo "<script> var userID = " . $id . "; </script>";
echo "<script> var userLogged = " . $user_id . "; </script>";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <link rel="stylesheet" href="../styles/profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style>
        body {
            display: none;
        }
    </style>
</head>

<body style="background-color: #1c2433;">

    <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: #1c2433;">
        <div class="container-fluid">
            <a id="homebutton" class="navbar-brand" href="./home.php">
                <img src="../images/motorminded5.png" alt="Logo" width="240" height="auto" class="d-inline-block align-text-top ms-3">
            </a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">

                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"></a>
                </li>
            </ul>
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
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="alertContainer" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z" />
                </svg>
                <strong class="me-auto"> Notification</strong>
                <small>Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <span id="alertMessage"></span>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="unsubscribeModal" tabindex="-1" aria-labelledby="unsubscribeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="unsubscribeModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete your account? This action cannot be undone.
                    Every post you made will be deleted.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmUnsubscribe">Delete</button>
                </div>
            </div>
        </div>
    </div>



    <div class="container">
        <div id="profileContainer">
            <section class="h-100 gradient-custom-2">
                <div class="container py-5 h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col col-lg-9 col-xl-7">
                            <div class="card pb-5">
                                <div class="rounded-top text-white d-flex flex-row" style="background-color: #32405B; height:200px;">
                                    <div style="position: relative; display: inline-block;">
                                        <img id="profileImg" src="" alt="Generic placeholder image" class="img-fluid border p-1 shadow-2 ms-3 mt-3 " style="z-index: 1;  width: 150px; height: 150px; border-radius: 10%; object-fit: cover;">
                                    </div>

                                    <div class="ms-3" style="margin-top: 80px;">
                                        <h5 id="name"></h5>
                                        <p id="flagContainer" class="d-inline-block"></p>
                                        <p id="country" class="d-inline-block"> </p>
                                    </div>
                                </div>
                                <div class="p-3 text-black" style="background-color: #f8f9fa;">
                                    <div class="row">
                                        <div class="col-lg-6" id="updUnsunButton">
                                            <div id="userbutton" class="ms-3 d-flex" style="width: 150px;">
                                                <button id="update" type="button" class="update btn btn-dark ps-3 pe-3 me-1" data-mdb-ripple-color="dark" style="z-index: 1;" href="./update.html"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                    </svg></button>
                                                <button id="unsubscribe" type="button" class="unsubscribe btn btn-danger fw-bold ps-3 pe-3" data-mdb-ripple-color="dark" style="z-index: 1;" data-bs-toggle="modal" data-bs-target="#unsubscribeModal"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                    </svg></button>
                                            </div>
                                        </div>
                                        <div class="col-lg-6" id="numPostsDiv">
                                            <div class="d-flex justify-content-center text-center">
                                                <div>
                                                    <p id="numPosts" class="mb-1 h5">0</p>
                                                    <p class="small text-muted mb-0">Photos</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body p-4 text-black">
                                    <div class="mb-5">
                                        <p class="lead fw-normal mb-1">About</p>
                                        <div class="p-4" style="background-color: #f8f9fa;">
                                            <p id="aboutMe" class="font-italic mb-1"></p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0" id="btnParagraph"><a type="button" id="showPostsButton" class="text-muted">Edit</a></p>
                                    </div>

                                </div>

                                <div id="posts-container">

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
    <!-- footer -->

    <script src="../scripts/profile.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>




</body>

</html>