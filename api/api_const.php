<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== "POST") {
    // header('HTTP/1.1 405 Method Not Allowed');
    http_response_code(405);

    $response['status'] = 405;
    $response['message'] = "Method Not Allowed";
    $response['data'] = null;

    print_r(json_encode($response));
    die();
}

global $api_data;
$api_data = json_decode(file_get_contents("php://input"), true);

if (!empty($_POST)) {
    $api_data = $_POST;
}

if (!empty($_FILES)) {
    foreach ($_FILES as $key => $files) {
        $api_data[$key] = $files;
    }
}

function send_response($message = "Success", $data = null, $status = 200)
{
    // header('HTTP/1.1 405 Method Not Allowed');
    http_response_code($status);

    $response['status'] = $status;
    $response['message'] = $message;
    $response['data'] = $data;

    print_r(json_encode($response));
    die();
}