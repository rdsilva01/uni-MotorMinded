<?php
header("Access-Control-Allow-Origin: http://localhost:8888/project/frontend/pages");
header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: plain/text');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods,Access-Control-Allow-Origin, Access-Control-Allow-Credentials, Authorization, X-Requested-With");


include_once "../db.inc.php";
include_once "../refactors.inc.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'register')
    registerUser($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'login')
    login($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud_req'] == 'update') //alterar para PUT
    updateUser($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_POST['crud_req'] == 'patch')
    patchUser($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    deleteUser($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'profile')
    getUserProfile($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'all')
    getAllUsers($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'mostCountry')
    getMostCountry($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'countries')
    getUsersCountries($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET')
    logout($conn);


function getUsersCountries($conn)
{
    $sql = "SELECT country, COUNT(*) AS total_users FROM users GROUP BY country ORDER BY total_users DESC;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $allCountries = array();

    while ($row = $result->fetch_assoc()) {
        $country = array(
            'country' => $row['country'],
            'total_users' => $row['total_users']
        );
        $allCountries[] = $country;
    }

    if (empty($allCountries)) {
        http_response_code(404);
        echo "No post found";
        exit();
    }

    http_response_code(200);
    echo json_encode($allCountries);
    exit();
}


function getMostCountry($conn)
{
    $sqlCountry = "SELECT country, COUNT(*) AS total_users FROM users GROUP BY country ORDER BY total_users DESC LIMIT 1;";
    $stmtCountry = $conn->prepare($sqlCountry);
    $stmtCountry->execute();
    $resultCountry = $stmtCountry->get_result();
    $dataCountry = $resultCountry->fetch_assoc();
    $mostCountry = $dataCountry['country'];

    if (empty($mostCountry)) {
        http_response_code(404);
        echo json_encode("No country found");
        exit();
    }

    http_response_code(200);
    echo json_encode($mostCountry);
    exit();
}

// *****************Login function **********************
function login($conn)
{
    $username = $_POST['userName'];
    $pwd = $_POST['pwd'];

    $sql = "SELECT id, pwd, first_name, user_type FROM users WHERE user_name=?;";

    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql))
        httpReply(400, "Something went wrong!");

    $stmt->bind_param('s', $username);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $isValid = password_verify($pwd, $data['pwd']);
        if ($isValid) {
            $key = password_hash($username, PASSWORD_DEFAULT);
            $_SESSION[$key] = $username;
            setcookie('user', $key, time() + 3600, "/"); // 1 hour
            http_response_code(200);
            $response = array(
                'username' => $username,
                'first_name' => $data['first_name']
            );
            echo json_encode($response);
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['user_type'] = $data['user_type'];
        } else {
            http_response_code(401);
            echo "Username or Password is incorrect!";
        }
    }
    exit();
}

// ************Delete User *****************
function deleteUser($conn)
{
    if (!isset($_COOKIE['user'])) {
        http_response_code(403);
        echo "You are not authorized to perform this operation";
        exit();
    }

    $user = $_SESSION[$_COOKIE['user']];
    $userId = $_SESSION['user_id'];

    // Delete user's posts
    $deletePostsSQL = "DELETE FROM posts WHERE user_id=?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($deletePostsSQL)) {
        echo 'Something went wrong';
        exit();
    }
    $stmt->bind_param('s', $userId);
    $stmt->execute();

    // Delete user
    $deleteUserSQL = "DELETE FROM users WHERE user_name=?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($deleteUserSQL)) {
        echo 'Something went wrong';
        exit();
    }
    $stmt->bind_param('s', $user);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo $user . " is no longer a registered member!!! ";
        http_response_code(200);
        unset($_SESSION['user']);
        session_destroy();
        setcookie('user', false);
        logout();
    } else {
        echo 'Row not affected';
    }

    exit();
}

// **************** Update User ****************
function updateUser($conn)
{
    // Verifica se o usuário está logado
    if (!isset($_COOKIE['user'])) {
        http_response_code(401);
        echo "Você não está logado!";
        exit();
    }

    // Obtém as informações do usuário logado
    $username = $_SESSION[$_COOKIE['user']];

    // Recupera os novos dados do usuário da requisição
    $fName = $_POST['firstName'];
    $lName = $_POST['lastName'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $aboutMe = $_POST['about'];

    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, country = ?, about_me = ?";
    $parameters = array($fName, $lName, $email, $country, $aboutMe);

    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        // Obtém informações do arquivo
        $fileTmpPath = $_FILES['profileImage']['tmp_name'];
        $fileName = $_FILES['profileImage']['name'];
        $fileSize = $_FILES['profileImage']['size'];
        $fileType = $_FILES['profileImage']['type'];

        // Verifica se é uma imagem
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            http_response_code(400);
            echo "Invalid file type, only JPG, JPEG, PNG and GIF are allowed!";
            exit();
        }

        // Define o diretório onde a imagem será armazenada
        $uploadDirectory = '../../storage/profile_img/';

        // Define o novo nome do arquivo
        $newFileName = uniqid() . '.' . $fileExtension;

        // Move o arquivo para o diretório de armazenamento
        $destination = $uploadDirectory . $newFileName;
        if (!move_uploaded_file($fileTmpPath, $destination)) {
            http_response_code(500);
            echo "Failed to upload image!";
            exit();
        }

        $sql .= ", image_path = ?";
        $parameters[] = $destination;
    }

    $sql .= " WHERE user_name = ?";
    $parameters[] = $username;

    // Prepara a instrução SQL
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(400);
        echo "Algo deu errado!";
        exit();
    }

    // Vincula os parâmetros e executa a instrução
    $stmt->bind_param(str_repeat('s', count($parameters)), ...$parameters);
    if ($stmt->execute()) {
        http_response_code(200);
        echo "User updated successfully!";
    } else {
        http_response_code(500);
        echo "Failed to update user!";
    }

    exit();
}

// ************ Patch User *****************
function patchUser($conn)
{
    // Verifies if the user is logged in as an admin
    if (!isset($_COOKIE['user']) /*|| $_SESSION[$_COOKIE['user']]['type'] !== 'admin'*/) {
        http_response_code(401);
        echo "You are not authorized to perform this action!";
        exit();
    }

    // Retrieves the user's information from the request
    $userId = $_GET['id'];
    $fName = $_POST['updateFirstName'];
    $lName = $_POST['updateLastName'];
    $email = $_POST['updateEmail'];
    $country = $_POST['updateCountry'];
    $about = $_POST['updateAbout'];
    $type = $_POST['updateUserType'];

    // Constructs the SQL query
    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, country = ?, user_type = ?, about_me = ? WHERE id = ?";
    $parameters = array($fName, $lName, $email, $country, $type, $about, $userId);

    // Prepares the SQL statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(400);
        echo "Something went wrong!";
        exit();
    }

    // Binds the parameters and executes the statement
    $stmt->bind_param(str_repeat('s', count($parameters)), ...$parameters);
    if ($stmt->execute()) {
        http_response_code(200);
        echo "User updated successfully!";
    } else {
        http_response_code(500);
        echo "Failed to update user!";
    }

    exit();
}



// ************ Register User *****************
function registerUser($conn)
{
    $fName = $_POST['fName'];
    $lName  = $_POST['lName'];
    $userName = $_POST['userName'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $rPwd = $_POST['rPwd'];
    $country = $_POST['country'];

    // Por padrão
    $aboutMe = "Hey, I'm in MotorMinded!";
    $image = 'steeringwheel.png';
    $userType = 'user';

    if (empty($fName) || empty($lName) || empty($userName) || empty($pwd) || empty($rPwd)) {
        http_response_code(401);
        echo "All the fields are required!";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Not a valid email address!";
        exit();
    }

    if ($pwd != $rPwd) {
        http_response_code(400);
        echo "Passwords do not match!";
        exit();
    }

    $pwd = password_hash($pwd, PASSWORD_DEFAULT);

    // Verifica se o nome de usuário já existe
    $checkUserSql = "SELECT * FROM users WHERE user_name = ?";
    $checkUserStmt = $conn->stmt_init();
    if (!$checkUserStmt->prepare($checkUserSql)) {
        echo "Error checking username!";
        exit();
    }
    $checkUserStmt->bind_param('s', $userName);
    $checkUserStmt->execute();
    $checkUserResult = $checkUserStmt->get_result();

    if ($checkUserResult->num_rows > 0) {
        http_response_code(400);
        echo "Username already exists!";
        exit();
    }

    // Verifica se o email já está registrado
    $checkEmailSql = "SELECT * FROM users WHERE email = ?";
    $checkEmailStmt = $conn->stmt_init();
    if (!$checkEmailStmt->prepare($checkEmailSql)) {
        echo "Error checking email!";
        exit();
    }
    $checkEmailStmt->bind_param('s', $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        http_response_code(400);
        echo "Email already registered!";
        exit();
    }

    // Insere o novo usuário no banco de dados
    $insertSql = "INSERT INTO users (first_name, last_name, user_name, email, pwd, country, about_me, user_type, image_path) VALUES (?,?,?,?,?,?,?,?,?);";
    $insertStmt = $conn->stmt_init();
    if (!$insertStmt->prepare($insertSql)) {
        echo "Error inserting user!";
        exit();
    }
    $insertStmt->bind_param('sssssssss', $fName, $lName, $userName, $email, $pwd, $country, $aboutMe, $userType, $image);
    $insertStmt->execute();

    if ($insertStmt->affected_rows) {
        http_response_code(200);
        echo "User registered successfully!";
    }
    exit();
}


/* ***************************************** */
/* *********** Get User Profile ************ */
/* ***************************************** */
function getUserProfile($conn)
{
    if (!isset($_COOKIE['user'])) {
        http_response_code(401);
        echo "Not authorized!";
        exit();
    }

    $id = $_GET['id'];
    $userProfile = getUser($conn, $id);

    http_response_code(200);
    echo json_encode($userProfile);
    exit();
}

function getUser($conn, $id)
{
    $sql = "SELECT * FROM users WHERE id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userProfile = $result->fetch_assoc();

    if (!$userProfile) {
        http_response_code(404);
        echo "user not found!";
        exit();
    }

    return $userProfile;
}

function getAllUsers($conn)
{
    $sql = "SELECT * FROM users;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $allUsers = array();

    while ($row = $result->fetch_assoc()) {
        $allUsers[] = $row;
    }

    if (empty($allUsers)) {
        http_response_code(404);
        echo "No user found";
        exit();
    }

    http_response_code(200);
    echo json_encode($allUsers);
    exit();
}

// ************ Logout*****************
function logout()
{
    if (!isset($_COOKIE['user'])) {
        echo "You are not logged in!!!";
        exit();
    }

    echo "Logging out! See you around!";
    unset($_SESSION[$_COOKIE['user']]);
    setcookie('user', '', time() - 3600, '/'); // Define um tempo de expiração passado no passado
    session_destroy();
    exit();
}
