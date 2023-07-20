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
    createRace($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_POST['crud_req'] == 'update')
    updateRace($conn);

else if ($_SERVER['REQUEST_METHOD'] === 'DELETE')
    deleteRace($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'all')
    getAllRaces($conn);

else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'one')
    getOneRace($conn);


function createRace($conn)
{
    // Verifique se o usuário está autenticado
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo "Usuário não autenticado.";
        return;
    }

    $post_name = $_POST['race_name_create'];
    $post_date  = $_POST['race_date_create'];
    $post_location = $_POST['race_location_create'];

    $sql = "INSERT into races (date, location, name_event) values (?, ?, ?);";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "something went wrong!!!";
        exit();
    }
    $stmt->bind_param('sss', $post_date, $post_location, $post_name);
    $stmt->execute();
    if ($stmt->affected_rows) {
        http_response_code(200);
        echo "Congratulation!!\n Post sent successfully\n";
    }
    exit();
}

function updateRace($conn)
{
    // Check if the user is authenticated
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo "User not authenticated.";
        return;
    }

    // Get the race ID from the request parameters
    $raceId = $_GET['id'];

    // Get the updated race data from the request body
    $updatedRaceData = json_decode(file_get_contents('php://input'), true);

    // Extract the updated values
    $updatedName = $updatedRaceData['race_name'];
    $updatedDate = $updatedRaceData['race_date'];
    $updatedLocation = $updatedRaceData['race_location'];

    // Prepare the SQL statement
    $sql = "UPDATE races SET name_event = ?, date = ?, location = ? WHERE id = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        http_response_code(500); // Internal Server Error
        echo "Error preparing statement.";
        return;
    }
    $stmt->bind_param('sssi', $updatedName, $updatedDate, $updatedLocation, $raceId);
    $stmt->execute();
    if ($stmt->affected_rows) {
        http_response_code(200);
        echo "Race updated successfully.";
    } else {
        http_response_code(404); // Not Found
        echo "Race not found or no changes were made.";
    }
    exit();
}


function deleteRace()
{
}

function getAllRaces($conn)
{
    $sql = "SELECT * FROM races;";
    $result = $conn->query($sql);
    if ($result) {
        $races = array();
        while ($row = $result->fetch_assoc()) {
            $races[] = $row;
        }
        http_response_code(200);
        echo json_encode($races);
    } else {
        http_response_code(500); // Internal Server Error
        echo "Error fetching races.";
    }
    exit();
}

function getOneRace($conn)
{
    // Check if the user is authenticated
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo "User not authenticated.";
        return;
    }

    // Get the race ID from the request parameters
    $raceId = $_GET['id'];

    $sql = "SELECT * FROM races WHERE id = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        http_response_code(500); // Internal Server Error
        echo "Error preparing statement.";
        return;
    }
    $stmt->bind_param('i', $raceId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $race = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode($race);
    } else {
        http_response_code(404); // Not Found
        echo "Race not found.";
    }
    exit();
}
