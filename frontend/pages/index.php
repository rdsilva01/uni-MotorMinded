<?php
session_start();

// Verificar se a variável de sessão está definida
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
}

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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="../styles/general.css">
</head>

<body class="overflow-y-hidden" style="background-color: #1C2331;">
  <div class="container my-5 text-center">
    <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-4 border shadow-lg bg-white text-center">
      <div class="col-lg-7 p-3 p-lg-5 pt-lg-3">
        <img class="" src="../images/motorminded4.png" alt="" width="490" height="auto">
        <p class="lead fs-6 text-muted opacity-50 mb-5"><small>Embark on a spellbinding voyage through Motor Minded, where cars transform into breathtaking works of art and engines set your heart ablaze. Surrender to the captivating allure of power, elegance, and unbridled passion as we whisk you away into a realm of automotive ecstasy.</small></p> 
        <div class="">
          <button class="login btn btn-success btn-lg px-4" href="./login.html"><strong>LOGIN</strong></button>
          <button class="signup btn btn-outline-secondary btn-lg px-4 me-sm-3" href="./register.html"><strong>SIGN UP</strong></button>
        </div>
      </div>
      <div class="col-lg-4 offset-lg-1 p-0 overflow-hidden shadow-lg rounded-4">
        <img class="rounded-4 lg-3" src="../images/cars/1995-ferrari-f50-2.jpeg" alt="" width="820">
      </div>
    </div>
  </div>

  <script src="../scripts/script.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>




</body>

</html>