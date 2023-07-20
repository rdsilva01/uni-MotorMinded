<?php
session_start();


// Verificar se a cookie não está presente
if (!isset($_COOKIE['user'])) {
    // Redirecionar o usuário de volta para o index
    header('Location: /project/frontend/pages/index.php');
    exit();
}

// Verificar se a variável de sessão está definida
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    if ($_SESSION['user_type'] === 'admin') {
    }
} else {
    // Redirecionar o usuário de volta para o index
    header('Location: /project/frontend/pages/home.php');
    exit();
}



?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Panel</title>
    <link rel="stylesheet" href="../styles/post.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script> <!-- Adicionado script do Chart.js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/js/all.min.js" integrity="sha512-rpLlll167T5LJHwp0waJCh3ZRf7pO6IT1+LZOhAyP6phAirwchClbTZV3iqL3BMrVxIYRbzGTpli4rfxsCK6Vw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.3/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.3/vfs_fonts.js"></script>

    <link rel="stylesheet" href="../styles/profile.css">

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1c2433;">
        <div class="container-fluid">
            <a class="navbar-brand" href="./home.php">
                <img src="../images/motorminded5.png" alt="Logo" width="240" height="auto" class="d-inline-block align-text-top">
            </a>

        </div>
    </nav>
    <!-- Conteúdo do painel de administração -->
    <div class="container mt-4" id="admindash">
        <div class="row">
            <div class="col-lg-5">
                <img src="../images/admindashboard-sm.png" style="width: 600px; height:auto;">
            </div>
        </div>

        <div class="row" id="statsRow">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3">
                        <!-- Menu lateral -->
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action active">Dashboard</a>
                            <a href="#usersview" id="btnusers" class="list-group-item list-group-item-action">Users</a>
                            <a href="#postsview" id="btnposts" class="list-group-item list-group-item-action">Posts</a>
                            <a href="#racesview" id="btnraces" class="list-group-item list-group-item-action">Races</a>
                            <a href="#soundsview" id="btnsounds" class="list-group-item list-group-item-action">Sounds</a>
                            <!-- <a href="#" class="list-group-item list-group-item-action">Pedidos</a> -->
                        </div>

                    </div>
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Number of posts</h5>
                                <p id="numPosts" class="card-text"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Most posted car brand</h5>
                                <p id="mostPostedBrand" class="card-text "></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Country with most users</h5>
                                <p class="card-text" id="mostCountryUsers"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="graphsStat">
            <div class="col-lg-4">
                <div class="text-center border rounded-4 mt-3 pb-3 pt-3">
                    <canvas id="chartcars"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center border rounded-4 mt-3 pb-3 pt-3">
                    <canvas id="chartcountries"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center border rounded-4 mt-3 pb-3 pt-3">
                    <canvas id="chartusers"></canvas>
                </div>
            </div>
        </div>


        <div class="row">
            <div id="timelineview" class="mt-4 border rounded-4 p-2" style="display: none;">
                <h5>Posts <button id="downloadPostsPDF" class="btn btn-primary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z" />
                        </svg></button></h5>
                <div id="timeline"></div>
            </div>

            <!-- USERS -->
            <div id="usersview" class="mt-4 border rounded-4 p-2" style="display: none;">
                <h5>Users <button id="downloadUsersPDF" class="btn btn-primary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z" />
                        </svg></button></h5>
                <div id="users"></div>
            </div>

            <div id="updateUsersForm" class="p-2 border rounded-4 mt-4" style="display: none;">
                <h5>Update User <span id="userIdSpan"></span></h5>
                <form id="userUpdateForm">

                    <div class="form-group">
                        <label for="updateFirstName">First:</label>
                        <input type="text" class="form-control" id="updateUserFirstName" name="user_first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="updateLastName">Last Name:</label>
                        <input type="text" class="form-control" id="updateUserLastName" name="user_last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="updateLocation">E-mail:</label>
                        <input type="text" class="form-control" id="updateEmail" name="user_email" required>
                    </div>
                    <div class="form-group">
                        <label for="updateAbout">About me:</label>
                        <input type="text" class="form-control" id="updateAbout" name="user_about" required>
                    </div>
                    <div class="form-group">
                        <label for="updateUserType">User Type:</label>
                        <input type="text" class="form-control" id="updateUserType" name="user_type" required>
                    </div>
                    <div class="mb-3">
                        <!-- <label for=""></label> -->
                        <input type="hidden" id="crud_req" name="crud_req" value="patch">
                    </div>
                    <input type="hidden" name="submit" value="submit">
                    <div class="mb-3 text-center">
                        <input type="hidden" id="" value="patch">
                        <input type="submit" class="btn btn-primary ps-5 pe-5" name="submit" id="updateUser" value="Update">
                        <button id="cancelUser" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>


            <!-- SOUNDS -->
            <div id="soundsview" class="mt-4 border rounded-4 p-2" style="display: none;">
                <h5>Sounds <button class="btn btn-success" id="addnewsound">Add New</button></h5>
                <div id="sounds"></div>
            </div>
            <div id="addSoundForm" style="display: none;">
                <h5>Add Sound</h5>
                <form id="soundAddForm">
                    <div class="form-group">
                        <label for="addSoundName">Name of the sound:</label>
                        <input type="text" class="form-control" id="addSoundName" name="soundName" required>
                    </div>
                    <div class="form-group">
                        <label for="addSoundFile">MP3 File:</label>
                        <input type="file" class="form-control" id="addSoundFile" name="soundFile" accept=".mp3" required>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" id="crud_req" name="crud_req" value="createSound">
                    </div>
                    <input type="hidden" name="submit" value="submit">
                    <div class="mb-3 text-center">
                        <input id="addSound" type="submit" class="btn btn-primary ps-5 pe-5" name="submit" value="Add">
                        <button id="cancelAddSound" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>


            <!-- RACES -->
            <div id="racesview" class="mt-4 border rounded-4 p-2" style="display: none;">
                <h5>Races <button class="btn btn-success" id="addnew">Add New</button></h5>
                <div id="races"></div>
            </div>

            <div id="updateForm" class="p-2 border rounded-4 mt-4" style="display: none;">
                <h5>Update Race</h5>
                <form id="raceUpdateForm">
                    <div class="form-group">
                        <label for="updateName">Event:</label>
                        <input type="text" class="form-control" id="updateName" name="race_name" required>
                    </div>
                    <div class="form-group">
                        <label for="updateLocation">Location:</label>
                        <input type="text" class="form-control" id="updateLocation" name="race_location" required>
                    </div>
                    <div class="form-group">
                        <label for="updateDate">Date:</label>
                        <input type="text" class="form-control" id="updateDate" name="race_date" required>
                    </div>
                    <div class="mb-3">
                        <!-- <label for=""></label> -->
                        <input type="hidden" id="crud_req" name="crud_req" value="update">
                    </div>
                    <input type="hidden" name="submit" value="submit">
                    <div class="mb-3 text-center">
                        <input type="hidden" id="input-race-id" value="">
                        <input type="submit" class="btn btn-primary ps-5 pe-5" name="submit" id="update" value="Update">
                        <button id="cancel" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>

            <div id="createForm" style="display: none;">
                <h5>Create Race</h5>
                <form id="raceCreateForm">
                    <div class="form-group">
                        <label for="updateName">Event:</label>
                        <input type="text" class="form-control" id="createName" name="race_name_create" required>
                    </div>
                    <div class="form-group">
                        <label for="updateLocation">Location:</label>
                        <input type="text" class="form-control" id="createLocation" name="race_location_create" required>
                    </div>
                    <div class="form-group">
                        <label for="updateDate">Date:</label>
                        <input type="text" class="form-control" id="createDate" name="race_date_create" required>
                    </div>
                    <div class="mb-3">
                        <!-- <label for=""></label> -->
                        <input type="hidden" id="crud_req" name="crud_req" value="create">
                    </div>
                    <input type="hidden" name="submit" value="submit">
                    <div class="mb-3 text-center">

                        <input id="create" type="submit" class="btn btn-primary ps-5 pe-5" name="submit" value="Create">
                        <button id="cancel_create" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class=" text-center text-white mt-5" style="background-color: #1c2433;">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: #1c2433;">
            © 2023 Copyright:
            <a class="text-white" href="https://github.com/tintadaraiz" target="_blank">Rodrigo Silva</a>
        </div>
        <!-- Copyright -->
    </footer>
    <script src="../scripts/admin.js"></script>
</body>

</html>