<?php
include_once "./api_const.php";
include_once "../config/db.php";
include_once "../config/token.php";

if (isset($api_data['payload'])) {
    $payload = json_decode(base64_decode($api_data['payload']), true);

    if (
        empty($payload['verifyToken']) ||
        empty($payload['token'])
    ) {
        send_response("Required valued not passed", $payload, 400);
    }

    $token = $payload['token'];

    $id = verify_token();

    if ($id) {
        $sql = "SELECT * FROM `users` WHERE `id` = '$id'";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows == 0) {
                send_response("It seems like user doesn't exist, please contact administrator", null, 403);
            }

            $data = $result->fetch_assoc();

            $response = [
                "firstName" => $data['f_name'],
                "lastName" => $data['l_name'],
                "username" => $data['username'],
                "email" => $data['email'],
                "phone" => $data['phone'],
            ];

            send_response("Success", $response, 200);
        }
    } else {
        send_response("It seems like user doesn't exist, please contact administrator", null, 403);
    }
}