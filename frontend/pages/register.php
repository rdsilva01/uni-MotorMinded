<?php

// Verificar se a cookie não está presente
if (isset($_COOKIE['user'])) {
    // Redirecionar o usuário de volta para o index
    header('Location: /project/frontend/pages/home.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Register</title>
</head>

<body class="overflow-y-hidden" style="background-color: #1C2331;">
    <div class="container my-5 text-center">
        <div class="row align-items-center rounded-4 border shadow-lg bg-white">
            <div class="col-lg-6 p-3 p-lg-5 pt-lg-3">

                <div class="mb-3">
                    <img src="../images/motorminded3.png" alt="" width="450px">
                </div>

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

                <form onsubmit="return false;">
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="fName" id="floatingInput" placeholder="First Name">
                                <label for="floatingInput">First Name</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="lName" id="floatingInput" placeholder="Last Name">
                                <label for="floatingInput">Last Name</label>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-car-front-fill" viewBox="0 0 16 16">
                                <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679c.033.161.049.325.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.807.807 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2ZM6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2H6ZM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17 1.247 0 3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z" />
                            </svg></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup1" name="userName" placeholder="Username">
                            <label for="floatingInputGroup1">Username</label>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">@</span>
                        <div class="form-floating">
                            <input type="email" class="form-control" name="email" id="floatingInputEmail" placeholder="name@example.com">
                            <label for="floatingInputEmail">Email</label>
                        </div>
                    </div>


                    <div class="input-group mb-3">
                        <span class="input-group-text" id="flagContainer">
                            <img src="https://flagsapi.com/AD/shiny/16.png">
                        </span>
                        <div class="form-floating">
                            <select class="form-select" id="floatingCountry" name="country" required>
                            </select>
                            <label for="floatingCountry">Country</label>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="input-group mb-3">
                                <span class="input-group-text">#</span>
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="floatingPassword" name="pwd" placeholder="Password">
                                    <label for="floatingPassword">Password</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group mb-3">
                                <span class="input-group-text">#</span>
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="floatingRPassword" name="rPwd" placeholder="Password">
                                    <label for="floatingRPassword">Repeat your password</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <!-- <label for=""></label> -->
                        <input type="hidden" id="crud_req" name="crud_req" value="register">
                    </div>
                    <input type="hidden" name="submit" value="submit">
                    <div class="text-center">

                        <button type="submit" class="btn btn-success ps-5 pe-5" id="button" name="submit"><strong class="fs-4">SIGN UP</strong> </button>
                    </div>

                    <div class="mt-3">
                        <span class="fs-6">Already have an account? <a href="login.php" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Login</a></span>
                    </div>
                </form>

            </div>


            <div class="col-lg-6 overflow-hidden rounded-4 text-center">
                <img class="rounded-end-4 lg-3 shadow" src="../images/cars/croppedporsche.jpeg" alt="" width="680">
            </div>
        </div>
    </div>
    <div class="">
        <br>
    </div>
    <!-- footer -->
    <footer class=" p-2 pt-3 pb-4" style="background-color: #1C2331;">
        <div class="">
            <span class="ms-3 text-light">© 2023 MotorMinded, Inc</span>
        </div>
    </footer>

    <script src="../scripts/register.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>