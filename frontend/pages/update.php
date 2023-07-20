<?php
session_start();

// Verificar se a variável de sessão está definida
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
}


// Verificar se a cookie não está presente
if (!isset($_COOKIE['user'])) {
    // Redirecionar o usuário de volta para o index
    header('Location: /project/frontend/pages/index.php');
    exit();
}



echo "<script> var userID = " . $user_id . "; </script>";



// Imprime o valor do ID no JavaScript

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit your Profile</title>
    <link rel="stylesheet" href="../styles/update.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body style="background-color: #1c2433;">
    <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: #1c2433;">
        <div class="container-fluid">
            <a class="navbar-brand" href="./home.php">
                <img src="../images/motorminded5.png" alt="Logo" width="240" height="auto" class="d-inline-block align-text-top ms-3">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="./home.php"><i class="bi bi-house"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"></a>
                    </li>
                </ul>
                <div class="d-flex">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                        <li class="nav-item me-4">
                            <div class="btn-group" role="group" aria-label="user">
                                <button class="logout btn btn-outline-warning">Logout</button>
                            </div>
                        </li>
                    </ul>
                </div>
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
            </div>
            <div class="toast-body">
                <span id="alertMessage"></span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="rounded-4 ms-5 me-5" style="background-color: #29344A;">
            <div id="profileContainer">
                <div class="container py-5 h-100">
                    <form enctype="multipart/form-data">
                        <div class="rounded-3 text-black pt-2" style="background-color: #1c2433; height:520px;">
                            <div class="ms-3 me-3 text-center" style="margin-top: 10px;">
                                <div class="image-upload mb-3">
                                    <label for="profileImage">
                                        <img src="" class="border p-2 mx-auto d-block" alt="Profile Image" id="profileImagePreview">
                                    </label>
                                    <input type="file" id="profileImage" name="profileImage" accept="image/*" style="display: none;">
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name">
                                            <label for="floatingInput">First Name</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="lastName" id="LastName" placeholder="Last Name">
                                            <label for="floatingInput">Last Name</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- select com o país -->
                                <div class="input-group mb-3 sm">
                                    <span class="input-group-text" id="flagContainer">flag</span>
                                    <div class="form-floating">
                                        <select class="form-select" id="country" name="country" required>
                                        </select>
                                        <label for="floatingCountry">Country</label>
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">@</span>
                                    <div class="form-floating">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                                        <label for="floatingInput">Email address</label>
                                    </div>
                                </div>

                                <!--
                                <div class="input-group mb-3">
                                    <span class="input-group-text">#</span>
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="floatingPassword" name="pwd" placeholder="Password">
                                        <label for="floatingPassword">Password</label>
                                    </div>
                                </div>
-->
                                <div class="">
                                    <div class="">
                                        <div class="form-floating">
                                            <textarea class="form-control" name="about" placeholder="Leave a comment here" id="about" style="height: 100px"></textarea>
                                            <label for="floatingTextarea2">About me</label>
                                        </div>
                                        <p id="charCounter" class="text-white mb-2"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <!-- <label for=""></label> -->
                            <input type="hidden" id="crud_req" name="crud_req" value="update">
                        </div>
                        <input type="hidden" name="submit" value="submit">
                        <div class="d-grid gap-2 mt-2">
                            <input type="submit" class="btn btn-primary ps-5 pe-5" name="submit" value="Save">
                        </div>
                    </form>


                </div>
            </div>
        </div>

    </div>
    </div>
    </div>
    <!-- footer -->
    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <div class="col-md-4 d-flex align-items-center">
                <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
                    <svg class="bi" width="30" height="24">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                </a>
                <span class="mb-3 mb-md-0 text-light">© 2023 MotorMinded, Inc</span>
            </div>
        </footer>
    </div>

    <script src="../scripts/update.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


</body>

</html>