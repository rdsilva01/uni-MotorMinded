<?php
session_start();

// Verificar se a variável de sessão está definida
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  if ($_SESSION['user_type'] === 'admin') {
  }
} else {
}

if ($_SESSION['user_type'] === 'admin') {
  $user_type = $_SESSION['user_type'];
  echo "<script> var usertype = " . $user_type . " ; </script>";
}

// Verificar se a cookie não está presente
if (!isset($_COOKIE['user'])) {
  // Redirecionar o usuário de volta para o index
  header('Location: /project/frontend/pages/index.php');
  exit();
}


// Imprime o valor do ID no JavaScript
echo "<script> var userID = " . $user_id . "; </script>";


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
  <link rel="stylesheet" href="../styles/post.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
  <script src="https://use.fontawesome.com/c868b99d72.js"></script>
  <style>
    /* Add this style block within the <head> tag or in your CSS file */
    input[type="range"]::-webkit-slider-thumb {
      background-color: green;
    }

    input[type="range"]::-moz-range-thumb {
      background-color: green;
    }

    input[type="range"]::-ms-thumb {
      background-color: green;
    }

    input[type="range"]::-webkit-slider-thumb:active {
      background-color: green;
    }

    input[type="range"]::-moz-range-thumb:active {
      background-color: green;
    }

    input[type="range"]::-ms-thumb:active {
      background-color: green;
    }

    /* .profile {
      background-color: #6748B4;
      /* Replace with your desired color */
      /* Add any other styles you want for the button */
   /* }*/

 /*   .profile:hover,
    .profile:focus {
      background-color: #3F2560;
      color: white;
      /* Replace with the darker shade you want */
      /* Add any other styles you want for the hovered or focused state 
    }*/
  </style>
</head>

<!-- <body style="background-color: #1c2433;"> -->

<body class="overflow-x-hidden overflow-y-auto" style="background-color:#efefef ">
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1c2433;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="../images/motorminded5.png" alt="Logo" width="240" height="auto" class="d-inline-block align-text-top">
      </a>
    </div>
  </nav>

  <div id="raceContainerWrapper" class="mt-2">
    <div id="racesContainer"></div>
  </div>

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

  <div class="row">
    <div class="col-lg-3 mt-3 ms-3">
      <div class="card mb-3 shadow">
        <div class="card-body p-4">
          <div class="text-black">
            <div class="text-center">
              <div class="flex-grow-1">
                <div id="imgContainer">

                </div>
                <span id="flagContainer"></span>
                <p id="nome" class="lead" style="color: #2b2a2a;"> </p>
              </div>
            </div>
            <div class="d-flex text-center rounded-3 mb-2 p-2 items-align-center" style="background-color: #efefef; display: flex; justify-content: center;">
              <div>
                <p class="small text-muted mb-1">Photos</p>
                <p class="mb-0" id="numPosts">0</p>
              </div>
            </div>
            <div class="d-flex pt-1">
              <button type="button" class=" btn-success profile btn me-1 flex-grow-1 fw-bold text-uppercase"> My Profile</button>
              <div id="admin" class="me-1"></div>
              <button type="button" class="logout btn btn-danger flex-grow-1 pt-1"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                  <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                </svg></button>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow" style="color: #4B515D; border-radius: 35px;">
        <div class="card-header text-white " style="background-color: #1c2433;">
          Check the weather for a ride!
        </div>
        <div class="card-body p-4">
          <div class="d-flex">
            <h6 id="country" class="flex-grow-1"></h6>
            <h6 id="city" class="flex-grow-1"></h6>
            <h6 id="time" style="display: none">00:00</h6>
          </div>

          <div class="d-flex flex-column text-center mt-3 mb-4 ">
            <h6 id="temperatura" class="display-4 mb-0 fw-bold" style="color: #1C2331;"></h6>
            <span id="condicoes" class="small" style="color: #868B94"></span>
          </div>

          <div class="d-flex align-items-center">
            <div class="flex-grow-1" style="font-size: 1rem;">
              <div><i class="fas fa-wind fa-fw" style="color: #868B94;"></i> <span class="ms-1" id="vento">
                </span></div>
              <div><i class="fas fa-tint fa-fw" style="color: #868B94;"></i> <span class="ms-1" id="humidity">
                </span></div>
            </div>
            <div class="ms-1 border rounded-5"><span class="opacity-100" id="wicon">
              </span></div>
          </div>
        </div>
      </div>

      <div class="card mt-3 shadow">
        <div class="card-header text-white" style="background-color: #1c2433;">
          Quote of the Day
        </div>
        <div class="card-body">
          <blockquote class="blockquote mb-0">
            <p>Il rumore del motore è la musica che amo.</p>
            <footer class="blockquote-footer"><cite title="Source Title">Enzo Ferrari</cite> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16.05 30.104" id="ferrari">
                <path fill="#f6d33c" d="M16.05.803v3.089H0v25.406c0 .442.363.805.806.805h14.438a.808.808 0 0 0 .806-.805V.806.803z"></path>
                <path fill="#2f9c5c" d="M15.244 0H.806C.363 0 0 .387 0 .86v.428h16.05V.857C16.048.386 15.687 0 15.244 0z"></path>
                <path fill="#fff" d="M0 1.321v1.22zM0 1.321h16.05v1.22H0zM0 1.206v.115z"></path>
                <path fill="#cf4037" d="M0 2.558h16.05v1.334H0z"></path>
                <path fill="#d35b31" d="M0 2.541v.017zM0 2.541h16.05v.017H0z"></path>
                <path d="M.723 28.856v-.305h.169c.085 0 .254-.018.254-.221v-1.829c0-.11-.008-.373-.22-.373H.731l.221-.389H13.98v.389H1.994c-.11 0-.254.025-.254.246v.576c.025.067.127.093.178.093s.229-.009.229-.288H2.4v.94h-.254c0-.085-.094-.227-.22-.237-.102-.008-.186.026-.186.162v.711c0 .076.093.221.313.221h.152v.305H.723v-.001z"></path>
                <path d="M8.242 26.569c0 .068.084.136.135.152.06-.042.373-.22.618-.22s.373.127.373.322v.932h-.593v-.721c0-.093-.025-.16-.11-.169s-.305.17-.364.255v1.287c0 .119.119.203.22.203v.246H7.488v-.254c.102 0 .212-.034.22-.187v-1.432c0-.042-.068-.16-.22-.16v-.255h.754v.001zM15.332 26.569h-1.033v.255c.152 0 .22.118.22.16v1.432c-.008.152-.119.187-.22.187v.254h1.033v-.247c-.102 0-.22-.084-.22-.203v-1.405c0-.102.11-.178.22-.178v-.255zM5.956 26.569c0 .068.084.136.135.152.06-.042.373-.22.618-.22.246 0 .373.127.373.322v.932h-.593v-.721c0-.093-.025-.16-.11-.169s-.305.169-.364.254v1.287c0 .119.119.203.22.203v.246H5.202v-.254c.102 0 .212-.034.22-.187v-1.432c0-.042-.068-.16-.22-.16v-.255h.754v.002zM12.825 26.569c0 .068.085.136.136.152.06-.042.373-.22.618-.22s.373.127.373.322v.932h-.593v-.721c0-.093-.025-.16-.11-.169-.085-.009-.305.169-.364.254v1.287c0 .119.118.203.22.203v.246h-1.033v-.254c.102 0 .211-.034.22-.187v-1.432c0-.042-.068-.16-.22-.16v-.255h.753v.002zM4.754 28.146h-.592v.229c0 .093-.017.288-.296.288s-.364-.187-.364-.322v-.482c0-.043.034-.102.093-.102h1.16v-.618c0-.28-.059-.365-.178-.483s-.534-.152-.72-.152c-.474 0-.627.067-.762.178-.136.11-.187.339-.187.508v.958c0 .254.051.432.212.593.161.16.652.16.754.16.102 0 .44.009.686-.169.246-.18.194-.586.194-.586zm-1.253-1.051c0-.136.042-.339.339-.339s.322.221.322.339v.305a.103.103 0 0 1-.102.102h-.457c-.05 0-.102-.042-.102-.093v-.314zM11.547 28.407v-1.27c0-.195-.009-.398-.212-.543-.203-.144-.534-.135-.661-.135s-.55 0-.746.22c-.194.221-.161.432-.161.517h.555s.038-.288.115-.364c.076-.076.279-.161.436-.025.058.05.081.114.081.187v.364c0 .034-.034.085-.077.085 0 0-.771.017-.906.118-.135.102-.254.169-.254.542s.093.517.203.593.161.161.661.161h1.186v-.247c-.102 0-.22-.084-.22-.203zm-.593.145c0 .021-.034.051-.06.051h-.292c-.208 0-.267-.178-.267-.288v-.326c0-.317.33-.31.461-.31.132 0 .157.11.157.183v.69h.001zM14.776 26.125c.297 0 .537-.09.537-.201s-.24-.201-.537-.201c-.296 0-.536.09-.536.201s.24.201.536.201zM7.336 17.237l-.031.076.092.453.124.412.339.745.329.575.261.355.005.086-.016.072-.066.026-.548-.01-.541-.082-.565-.201-.251-.015-.072.031-.33-.036-.132-.046-.869-.021-.108.045-.077.022-.026.036.005.071.211.31.082.035.278.174.16.052.436-.015.323-.088.063.031.029.062.053.015.288.005.082.026.097-.026.534.072.612.154.48.19.088.063.232.01.124-.031.137-.299-.021-.133-.204-.406-.092-.251-.098-.525.025-.514.031-.138-.037-.12-.338-.251-.072-.307-.198-.287-.478-.386-.196-.077-.082.013-.072.048zM7.52 6.753l-.014-.149-.211-.256-.065-.019-.045.064.006.077-.071.064-.007.089.201.13h.206z"></path>
                <path fill="none" d="M7.108 6.585l.005-.012"></path>
                <path d="M14.372 13.132l-.072-.033v.109l-.057.064-.103.154-.058.038-.225.297-.077.173-.084.014-.077-.046-.045-.205.012-.238.052-.22.386-.591.153-.314.02-.22-.024-.069-.046.045-.077.192-.117.193-.372.333-.296.348-.058-.006-.02-.059.013-.405.09-.295.161-.328.213-.264.264-.225.024-.053-.032-.024-.244.142-.418.366-.166.226-.162.295-.089.238-.039.559.154.625.148.455.154.963-.025.463-.11.411-.271.341-.275.251-.354.199-.34.02-.046-.166-.122-.239-.424-.565-.316-.302-.462-.373-.443-.301-.637-.533-.346-.432-.381-.623-.103-.373.026-.232.052-.045.348.193.172-.007.412.22.252.187.146.02.034-.064-.039-.007-.045.02-.072-.046-.154-.244-.174-.16-.269-.084.019-.051h.135l.155.051.328.193.039-.052-.064-.09-.174-.155-.271-.141-.327-.128-.064-.052.064-.046.146.072.271.045.443.192.271.038.193-.026.257-.142.064-.062.007-.045-.059.004-.058.06-.359.089-.142-.02-.136-.108-.123-.143-.145-.108-.431-.117-.091-.058.007-.05.084.012.668-.031.084.069.244.033.051-.038-.089-.026-.226-.18-.187-.098-.295-.038-.051-.031.103-.058H9.8l.463.191.206.007.109-.052.032-.07-.083-.038-.173-.007-.148-.051-.129-.11-.142-.071-.187-.031-.161-.058-.07-.091.012-.045.232.06.077.057.11.013.012-.058-.115-.091-.033-.082.155.024.146.077.187.021.072.135.192.115.011-.002.042-.018.05-.031-.024-.038-.072.005-.193-.288-.225-.2-.231-.104-.205-.244-.129-.069-.02-.065.058-.039.534.153.083.117.135.07h.103l.137.038.148-.026.057-.051-.083-.071-.264-.051-.084-.071-.11-.16-.026-.104-.47-.333-.191-.18-.013-.052h.044l.251.2.393.251.173.161.244.109.045-.071-.064-.024-.134-.122-.039-.136-.258-.252-.058-.146-.199-.298-.271-.237-.012-.076.096.012.354.161.225.012.167.079.18.031.153-.046-.012-.064-.175-.038-.63-.309-.087-.109.031-.038.046.031.069.025.091.008.469-.026.148.058.154.109.065.02.007-.058-.052-.052-.154-.142-.239-.122-.501-.039-.244-.257-.256-.192.058-.045.431.187.244.058h.211l.174.194.142.083.051-.052-.193-.284-.302-.218-.251-.06-.213-.223-.187-.109-.256-.065.142-.122.438-.142.044-.058-.024-.045-.203-.045-.725.014-.129-.143-.334-.179-.244-.039h-.211l-.256-.29-.239-.217-.147.244-.007.076.069.091.007.077-.05.045-.163.013-.584-.147-.354.051.026.076.123-.005.05.009-.002.068-.071.016-.093-.013-.244.063-.084.006-.026.036.03.051.063.014.059-.017.068-.03.07-.003.148.006.247.067.197.089.018.043-.02.033-.093.033-.056.026-.436.278-.531.272-.441.042-.066.018-.038.08-.03.087-.033.06-.165.264-.054.068-.005.079.005.227.072.064.063.033.075-.006.447-.149.075.003.017.048-.017.045-.063.033-.334.113-.028.047-.005.051.047.042.1.013h.08l.211-.03.158-.084.105-.021.09-.018.068.022.198.008.08.047.29.062.089.013.138-.024.175.012.077.03.042.071-.072.338-.224.621-.471.655-.494.474-.061.2L6 11.61l-.439.307-.169.086-.185.223-.077.048-.108.054-.07-.016-.769-.395-.472-.377-.192-.069-.169-.031-.216.046-.286.178-.292.81-.009.224-.169.546-.1.193-.054.192-.032.239-.14.315-.014.647-.038.14.022.122-.009.2.07.161.054.038.1-.031.225-.244.115-.186.046-.153-.185-.803.031-.293.077-.237.201-.271.099-.009.047-.029.216-.224.055-.2.029-.232.054-.068.054-.016.069.023.1.054.354.446.263.224.123.054.038.077-.038.038-.785-.138h-.44l-.255.192-.108.185-.082.21-.009.154.047.277.108.354.253 1.117.047.307.045.108.225.201.122.185.085.217.131.191.192.186.14.085.092.007.077-.022.099-.201.032-.13v-.301l-.037-.085c-.007 0-.007-.055-.007-.062l-.016-.054-.178-.208-.215-.093-.078-.024-.014-.131.007-.083-.146-.17-.093-.215-.14-.592-.106-.224-.038-.27.061-.163.108-.061h.084l.124.108.178.107.631.285.216.124v.116l.199.115.255.086h.532l-.038.1-.023.054.199.323.462.484.621.503.574.307.607.423.251.213.262.354.012.148.059.153.161.115.146.058.09-.012.175-.18.024-.148.122-.211.058-.483.041-.089.082-.04h.078l.005.059-.058.063-.089.22-.014.32-.031.11-.354.61-.075.302-.038.316.038.353.05.206.4.843.167.263.199.245.038.083v.058l-.026.072-.405.558-.431.47-.271.122-.108.104-.058.116-.455.508-.11.191-.103.354.007.129.077.082.405-.012.295-.153.027-.065h.058l.089-.039.084-.078.045-.308-.026-.064-.012-.064.045.013.05.014.053-.038.083-.053.199-.271.057-.141.013-.115.071-.053.187-.108.069-.142.426-.411.424-.259.051-.05.02-.053.045-.058-.031-.077-.035-.273-.033-.077-.248-.382-.012-.271.096-.328.072-.115.076-.045.534-.424.47-.522.301-.577.065-.22.031-.359.052-.051.058-.014.051.033.045.107.033.29-.026.56.038.25.046.064.038-.005.019-.046-.026-.103v-.175l.053-.146.05-.361.033-.069h.039l.031.031.058.239-.05.436-.227.478-.064.199v.264l.046.136h.084l.043-.06-.024-.064-.019-.115.481-.771.096-.521v-.295l-.058-.206-.115-.16-.014-.077.052-.025.064.031.188.29.07.504.064.201.007.005.089.199.058.021.026-.052-.007-.032-.044-.147v-.367l.039-.366-.014-.103-.192-.354.031-.058.122-.025.347-.173.161-.142.206-.277.213-.45.038-.668-.192-1.266.077-.431.108-.237.155-.244.058-.155.012-.077-.05-.07zM5.628 8.479l-.05.05-.072.04-.096.021-.033-.021-.012-.068.028-.096.068-.095.056-.028.051.005.061.051.028.045-.029.096zm.252-.064l-.018.009-.034.002-.029-.014-.022-.026.003-.032.026-.042.021-.014h.028l.025.006.009.026.003.045-.012.04zm.31.286l-.026.013h-.033l-.019-.011-.01-.028.003-.034.02-.021.024-.001.026.007.012.019.008.031-.005.025zm.595-.9l-.04.011-.049.012-.054.045-.121.073-.368.079-.05-.006.08-.068.438-.205.064-.031.049-.007.04.009.032.051-.021.037zm-4.493 7.232l-.052-.012-.026-.051.052-.026.045.005.012.046-.031.038zm.676-2.697l-.084.29-.084.129-.045.012-.016-.034v-.068l.042-.268.074-.127.038-.045.056-.017.021.032-.002.096zm.371-.512l-.084.032-.08.008-.049-.019-.012-.035.014-.047.042-.029.087-.011.045-.004.031.009.028.026.002.029-.024.041zm.682.92l-.018.002-.041-.026-.06-.033-.051-.042-.047-.047-.021-.026-.005-.038.01-.02.018.002.03.005.033.03.039.034.061.037.037.044.022.033.002.029-.009.016zm-1.146.916l-.031.044-.031.012-.021-.018.001-.062.053-.091.016-.026.026-.012.025.016v.052l-.016.037-.022.048zm.218 1.621l-.011.016H3.07l-.022-.02-.018-.033-.028-.061-.04-.081-.061-.244.016-.273.011-.016h.019l.02.018.019.082.002.084.115.481-.01.047zm.779 1.485l.021-.005.05.011.023.024.005.029-.005.035-.03.022-.037-.007-.026-.024-.01-.055.009-.03zm.503-2.35l-.052.003-.068-.011-.047-.021-.34-.222-.042-.031.056-.002.061.022.312.122.08.037.042.038.007.035-.009.03zm.582-1.505l-.138.403-.114.142-.057.044-.061.022-.035-.024-.003-.046.048-.052.115-.226.073-.267.068-.127.046-.065.038-.01.035.014.01.042-.025.15zM7.755 8.83l.058-.073.066-.062.059-.04.044-.005.03.02v.054l-.016.06-.145.112-.08.021-.039-.013-.004-.032.027-.042zm-.234-1.353l.056-.019.039-.018.048.017.016.035-.036.042-.05.02-.062.003-.023-.042.012-.038zm-.485.452l-.022-.062.033-.067.073-.062.117-.005.085.016.067.045.033.079.028.084-.005.072-.073.029h-.079l-.178-.084-.079-.045zm.187.918h-.089l-.016-.03.022-.031.027-.016.281-.19.064-.04h.023l.003.047-.043.034-.02.071-.055.084-.197.071zm-1.062 3.166l-.012-.019.003-.047.031-.035.338-.117.368-.028.115.024.108.055.085.084.005.047-.009.033-.037.007-.055-.007-.085-.024-.08-.024-.523.014-.083.021-.08.018-.052.004-.037-.006zm1.447 4.736l-.874-.598-.456-.42-.051-.079-.019-.094.003-.087.033-.033.058.003.188.148.406.459.718.641.022.036.016.026-.044-.002zm.08-3.989l-.037.021-.089.001-.059-.014-.033-.044-.029-.034v-.057l.043-.056.066-.052.063-.009.058.016.017.031.007.069.009.073-.016.055zm.176-2.875l-.119.349-.232.408-.195.274-.148.155-.103.04-.033-.002-.006-.026.032-.073.485-.907.155-.249.08-.076.061-.011.026.043-.003.075zm.357-.932L8.202 9l-.068.08-.189.084-.097.002h-.061l-.021-.028.023-.04.054-.024.202-.112.051-.036.077-.037.048.011v.053zm.511 2.943l-.035.01-.045-.003-.03-.03.007-.025.03-.021.045-.003.039.031.001.026-.012.015zm.053-.17h-.053l-.027-.016v-.039l.012-.026.028-.009h.037l.035.023.003.027-.035.04zm.096-.264l-.035.018-.037.005-.038-.009-.012-.026v-.031l.031-.022.039-.007h.037l.022.019.005.03-.012.023zm.049-.222l-.047.014-.053-.002-.028-.019-.002-.021.009-.031.042-.019.045-.008.039.02.014.036-.019.03zm.113-.31l-.026.035-.024.031-.049.01h-.049l-.042-.021-.004-.033.03-.031.023-.026.033-.012.054-.016h.042l.019.023-.007.04zm.07-.26l-.023.036-.04.007-.042.011-.052-.002-.023-.026.003-.038.018-.026.035-.024.054-.008.049.011.021.029v.03zm.074-.192l-.055.022-.042.015h-.052l-.021-.004-.026-.009-.014-.026.007-.033.035-.031.03-.016.033-.014.047-.002.045.016.019.037-.006.045zm.006-.298l-.019.031-.033.027-.049.018-.058.006-.047-.008-.012-.027.007-.022.019-.015.084-.043.04-.009.049.005.019.018v.019zm-.132-.707l.016-.011.007-.031.006-.025.014-.018.026-.001.031.009.028.016.003.033-.003.054-.042.036-.053.027-.023-.003-.023-.009-.023-.029-.003-.031.009-.018h.03zm-.03-.224v-.025l.023-.024.036-.013.044-.002.026.008.007.022-.014.024-.023.025-.036.008h-.039l-.024-.023zm.002.707l.031-.02.037-.009.037-.003.026.002.01.016v.025l-.012.029-.024.028-.062.026-.03-.003-.028-.009-.007-.029.005-.033.017-.02zm.156-.215l-.008.023-.038.026-.053.02-.029-.007-.028-.017-.006-.027.003-.035.021-.021.03-.021.043-.009.037-.001.025.007.008.024-.005.038zm.015-.738l-.005.03-.02.027-.032.011-.029.007-.026.004-.035-.004-.009-.02.009-.028.021-.019.022-.013.038-.026.041.002.025.029zm-.055-.256l.021.012.003.03-.001.027-.034.03-.031.012-.02-.003-.016-.008v-.029l.002-.029.023-.024.021-.013.032-.005zm-.04-.145l-.008.032-.023.016-.021.013-.028.004-.023-.015-.005-.024.011-.021.021-.028.021-.01.028-.002.022.014.005.021zm-.286-.563l.051-.003.071-.028.065-.015.054-.004.028.026-.038.058-.039.011-.065.022-.083.047-.08.042-.093.039-.031.003-.026-.008.003-.039-.014-.037v-.066l.03-.075.042-.045.026-.035.028-.034h.042l.03.029L8.816 8l.007.039zm-.03-.695l.019.013-.006.018-.028.033-.039.004-.045-.007-.026-.012-.02-.023.023-.019.033-.014.05-.002.039.009zm-.24-.271l.087-.009.047.016.037.02.002.031-.032.015-.048.002-.048-.002-.036-.008-.032-.023.023-.042zm.675 9.062l-.042.038-.039.044-.038.08-.012.056-.026.021-.05-.007-.024-.029.037-.239.014-.073.033-.093.024-.027.037-.008h.049l.021.02.037.055.007.103-.028.059zm-.136-1.377l-.307-.302-.175-.22-.154-.25-.122-.309-.051-.213.024-.122.069-.02.072.033.154.508.426.643.282.366-.218-.114zm.186 4.004l-.024.066-.081.224-.01.035-.012.022h-.033l-.026-.079-.002-.089.063-.289.136-.184.058-.007.016.052-.085.249zm.304 1.689l-.012.002-.022-.011-.042-.062-.063-.103-.03-.067-.018-.094v-.088l.003-.052.013-.016h.028l.029.012.039.046.028.078.019.104.002.084.001.029.016.041.016.08-.007.017zm-1.29 3.017l-.026.051-.031.047-.051.033-.049.011h-.02l-.022-.018-.007-.037.009-.045.026-.053.071-.071.054-.033.039-.002.019.016.005.037-.017.064zm.479-.772l-.026.037-.033.019-.04.015-.03-.009-.018-.031.004-.032.029-.036.06-.036.043-.008.021.013.005.031-.015.037zm1.068-1.064l-.18.234-.447.429-.051.033-.033-.002-.016-.025.007-.044.582-.614.045-.049.068-.044.024.006.021.026-.02.05zm.445-.344l.03-.01.038.001.018.009.002.02-.016.024-.035.031-.031.035h-.021l-.024-.014-.005-.03.01-.029.034-.037zm1.287-3.512l-.007.058-.018.054-.011.049-.035.021-.033-.009-.018-.032-.011-.109-.073-.102-.007-.083.042-.053.043-.014.042.003.035.031.04.051.011.058v.077zm2.172-1.994l-.003.082-.131.467-.168.272-.288.304-.079.02-.042-.02-.007-.056.033-.075.382-.576.1-.265.045-.479-.04-.436-.009-.082.045-.046.029.016.023.071.107.474v.329z"></path>
              </svg></footer>
          </blockquote>
        </div>
      </div>
      <!--
      <div class="card mt-3 rounded-4">
        <div class="card-title lead m-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note-list mb-1 ms-1" viewBox="0 0 16 16">
            <path d="M12 13c0 1.105-1.12 2-2.5 2S7 14.105 7 13s1.12-2 2.5-2 2.5.895 2.5 2z" />
            <path fill-rule="evenodd" d="M12 3v10h-1V3h1z" />
            <path d="M11 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 16 2.22V4l-5 1V2.82z" />
            <path fill-rule="evenodd" d="M0 11.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 .5 7H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 .5 3H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5z" />
          </svg> Check our Music </div>
        <iframe style="border-radius:12px;" src="https://open.spotify.com/embed/playlist/1Oo3MhgpXpaCcq9PBttWg1?utm_source=generator&theme=0" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
      </div>
        -->
      <div class="card mt-3 shadow">
        <div class="card-header text-white" style="background-color: #1c2433;">
          Check this awesome places
        </div>
        <div class="card-body">
          <blockquote class="blockquote mb-0">
            <p><a href="https://www.24h-lemans.com/en" class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" target="_blank">24h of Le Mans 2023</a></p>
            <footer class="blockquote-footer">Race of the Century (since 1923), 10-11 June <img src="https://flagsapi.com/FR/shiny/16.png"></footer>
          </blockquote>
          <blockquote class="blockquote mb-0">
            <p><a href="https://museudocaramulo.pt/" class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" target="_blank">Museu do Caramulo</a></p>
            <footer class="blockquote-footer">Visit Caramulo's Museum, in Caramulo, Viseu, PT <img src="https://flagsapi.com/PT/shiny/16.png"></footer>
          </blockquote>
          <blockquote class="blockquote mb-0">
            <p><a href="https://leiriasobrerodas.com/" class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" target="_blank">Leiria sobre Rodas</a></p>
            <footer class="blockquote-footer">One of the best motorsport festivals in Portugal <img src="https://flagsapi.com/PT/shiny/16.png"></footer>
          </blockquote>
          <blockquote class="blockquote mb-0">
            <p><a href="https://www.goodwood.com/motorsport/goodwood-revival/" class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" target="_blank">Goodwood Revival</a></p>
            <footer class="blockquote-footer">8 - 10 September (2023) Revive & Thrive <img src="https://flagsapi.com/GB/shiny/16.png"></footer>
          </blockquote>
        </div>
      </div>

      <div class="card mt-3 shadow">
        <div class="card-header text-white" style="background-color: #1c2433;">
          Check out your favourite car sound!
        </div>
        <div class="ms-3 custom-audio-player">
          <audio id="audioPlayer">
            <source id="audioSource" src="../sounds/720s.mp3" type="audio/mp3">
            browser doesn't support it
          </audio>



          <div class="audio-controls">
            <div class="reset-button">
              <i id="resetButton" class="fas fa-undo me-1"></i>
            </div>
            <div class="play-pause-button">
              <i id="playPauseIcon" class="fas fa-play"></i>
            </div>
            <div class="progress-bar">
              <div id="progressBar" class="progress-bar-fill progress-bar-striped progress-bar-animated bg-success "></div>
            </div>
          </div>
        </div>
        <div class="volume-control mt-3 ms-3">
          <div class="row">
            <div class="col">
              <input type="range" id="volumeSlider" min="0" max="1" step="0.01" value="1" class="form-range">
            </div>
            <div class="col">
              <span class="text-center fw-bold fst-italic" id="volumeText"><i class="fa-sharp fa-solid fa-volume-high mt-1"></i></span>
            </div>
          </div>
        </div>



        <div class="card-body">
          <div class="container border p-2 rounded mb-2"> <canvas id="audioVisualizer" width="100" height="100" class=""></canvas></div>
          <div class="scroll-list">
            <ul id="soundList" class="list-group"></ul>
          </div>
        </div>
      </div>

    </div>



    <div id="col_centro" class="col-lg-8">
      <div class="p-3 mt-2 bg-white rounded-3 border border-subtle-dark shadow" id="formulario" style="display: none;">
        <div class="row">
          <div class="col-lg-6">
            <div class="card mb-2" style="background-color: #efefef;">
              <form onsubmit="return false;" enctype="multipart/form-data">
                <div class="input-group mb-2 text-center">
                  <div class="form-floating text-center">
                    <img id="preview" class="fluid preview-image rounded-4 border shadow" src="../images/default-img.gif" alt="Visualização da Imagem" style="display: visible; z-index: 1;  width: 150px; height: 150px; border-radius: 10%; object-fit: cover;">
                    <input class="form-control" type="file" id="image" name="image" placeholder="Image" style="display: none;" required>
                  </div>
                </div>
            </div>

            <div class="mb-2 d-grid gap-2">
              <!-- <label for=""></label> -->
              <input type="hidden" id="crud_req" name="crud_req" value="create">
            </div>
            <input type="hidden" name="submit" value="submit">
            <div class="d-grid mb-3 gap-2 text-center">
              <input type="submit" class="btn btn-success ps-5 pe-5" name="submit" value="Post It">
              <button class="btn btn-outline-danger" type="button" id="cancel">Cancel</button>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card-body">
              <div class="input-group mb-3">
                <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-car-front-fill" viewBox="0 0 16 16">
                    <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679c.033.161.049.325.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.807.807 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2ZM6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2H6ZM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17 1.247 0 3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z" />
                  </svg></span>
                <div class="form-floating">
                  <input type="text" class="form-control" id="post_title" name="post_title" placeholder="Title">
                  <label for="title" class="form-label">Title</label>
                </div>
              </div>

              <div class="input-group mb-3">
                <span class="input-group-text" id="carIcon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-front-fill" viewBox="0 0 16 16">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z" />
                  </svg></span>
                <div class="form-floating">
                  <select class="form-select" id="post_maker" name="post_maker" required>

                  </select>
                  <label for="floatingCountry">Maker</label>
                  <div class="valid-feedback">Looks good!</div>
                </div>
              </div>

              <div class="input-group mb-3">
                <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-car-front-fill" viewBox="0 0 16 16">
                    <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679c.033.161.049.325.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.807.807 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2ZM6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2H6ZM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17 1.247 0 3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z" />
                  </svg></span>
                <div class="form-floating">
                  <select class="form-select" id="post_model" name="post_model" required>

                  </select>
                  <label for="post_model">Model</label>
                </div>
              </div>
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-text" viewBox="0 0 16 16">
                  <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                  <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z" />
                </svg></span>
              <div class="form-floating">
                <textarea class="form-control" id="post_descrip" name="post_descrip" rows="4" placeholder="Description" style="height: 100px;" oninput="charLimit(this, 20)"></textarea>
                <label for="description" class="form-label">Describe your car</label>
              </div>
            </div>
            <p id="charCounter">0/128 characters</p>
            </form>

          </div>
        </div>
      </div>

      <div class="mt-3 mb-4">
        <button id="newpost" class="btn btn-success ps-5 pe-5 shadow" type="button" style="height: 70px; width: 800px;"><span class=""><img src="../images/newspot.png" width="200px"></span></button>
      </div>

      <div id="posts_container">

      </div>
    </div>
  </div>

  <!-- footer -->
  <footer class="border-top p-3" style="background-color: #1C2331;">
    <div class="d-flex justify-content-between">
      <div>
        <span class="ms-3 text-light">© 2023 MotorMinded, Inc</span>
      </div>
      <div>
        <a href="#" class="me-3 link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
          </svg>
        </a>
      </div>
    </div>
  </footer>



  <script src="../scripts/home_scripts.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>