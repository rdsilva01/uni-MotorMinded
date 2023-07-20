<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

</head>

<body class="overflow-y-hidden" style="background-color: #1C2331;">

    <div class="container my-5 text-center">

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
        <div class="row align-items-center rounded-4 border shadow-lg bg-white">

            <div class="col-lg-6 p-3 p-lg-5 pt-lg-3">

                <div class="mb-4">
                    <img src="../images/motorminded3.png" alt="" width="450px">
                </div>
                <form onsubmit="return false;" class="needs-validation ms-5 me-5" novalidate>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-car-front-fill" viewBox="0 0 16 16">
                                <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679c.033.161.049.325.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.807.807 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2ZM6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2H6ZM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17 1.247 0 3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z" />
                            </svg></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup1" name="userName" placeholder="Username" required>
                            <label for="floatingInputGroup1">Username</label>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">#</span>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="floatingPassword" name="pwd" placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <!-- <label for=""></label> -->
                        <input type="hidden" name="crud_req" value="login">
                    </div>
                    <input type="hidden" name="submit" value="submit">
                    <div class="mb-3 text-center mb-4">
                        <button type="submit" class="btn btn-success ps-5 pe-5" name="submit"><!--<img class="pb-2"
                        src="../images/steeringwheel.png" width="40px">--><strong class="fs-4">SIGN IN</strong></button>
                    </div>
                    <div>
                        <span class="fs-6">Don't have an account? <a href="register.php" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Register</a></span>
                    </div>
                </form>
            </div>

            <div class="col-lg-6 p-0 overflow-hidden shadow-lg rounded-end-4">
                <div class="text-center">
                    <img src="../images/cars/698467.jpg" alt="" width="1020px">
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer class="p-4 pb-4" style="background-color: #1C2331; margin-top: 215px;">
        <div class="">
            <span class="ms-3 text-light">© 2023 MotorMinded, Inc</span>
        </div>
    </footer>

    <script src="../scripts/login.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>