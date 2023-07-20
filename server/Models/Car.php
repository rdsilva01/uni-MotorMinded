<?php
header("Access-Control-Allow-Origin: http://localhost:8888/project/frontend/pages");

header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: plain/text');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods,Access-Control-Allow-Origin, Access-Control-Allow-Credentials, Authorization, X-Requested-With");


include_once "../db.inc.php";
include_once "../refactors.inc.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'allMakes')
    getAllMakes($conn);
/*
else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'allmodels')
    getAllModels($conn);
*/
else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['crud_req'] == 'models')
    getModels($conn);

/* FUNCTIONS */

/* get all Makes */
function getAllMakes($conn)
{
    $sql = "SELECT * FROM makes";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $makes = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $makes[] = array(
                "id" => $row["id"],
                "name" => $row["name"]
            );
        }

        if (count($makes) > 0) {
            http_response_code(200); // Success response code
            echo json_encode($makes);
        } else {
            http_response_code(404); // Not found response code
            echo json_encode(array("message" => "No makes found."));
        }
    } else {
        http_response_code(500); // Server error response code
        echo json_encode(array("message" => "Error getting makes: " . mysqli_error($conn)));
    }
    exit();
}

/* get all Models */
function getAllModels($conn)
{
    $sql = "SELECT * FROM models;";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "something went wrong!!!";
        exit();
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $models = array();
    while ($row = $result->fetch_assoc()) {
        $models[] = $row;
    }
    echo json_encode($models);
    exit();
}

/* get all Models by MakeId */
function getModels($conn)
{
    $makeId = $_GET['makeId']; // Assuming makeId is passed as a parameter
    $sql = "SELECT * FROM models WHERE make_id = ?;";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "something went wrong!!!";
        exit();
    }
    $stmt->bind_param("i", $makeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $models = array();
    while ($row = $result->fetch_assoc()) {
        $models[] = $row;
    }
    echo json_encode($models);
    exit();
}
