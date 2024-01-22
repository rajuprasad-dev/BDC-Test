<?php
include_once "./api_const.php";
include_once "../config/db.php";

if (isset($api_data['payload'])) {

    $payload = json_decode(base64_decode($api_data['payload']), true);

    if (
        empty($payload['firstName']) ||
        empty($payload['lastName']) ||
        empty($payload['userName']) ||
        empty($payload['email']) ||
        empty($payload['phone']) ||
        empty($payload['password']) ||
        empty($payload['confirmPassword'])
    ) {
        send_response("Required valued not passed", $payload, 400);
    }

    $firstName = $payload['firstName'];
    $lastName = $payload['lastName'];
    $userName = $payload['userName'];
    $email = $payload['email'];
    $phone = $payload['phone'];
    $password = $payload['password'];
    $confirmPassword = $payload['confirmPassword'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($password !== $confirmPassword) {
        send_response("Password and confirm password should be same");
    }

    $check_existance = "SELECT * FROM `users` WHERE `email` = '$email' OR `phone` = '$phone' OR `userName` = '$userName'";
    $result = $conn->query($check_existance);

    // die($check_existance);
    // die($result);

    if ($result) {
        // die("working");

        if ($result->num_rows > 0) {
            send_response("User already exist, please login", null, 200);
        }

        $insert_user = "INSERT INTO `users`(`f_name`, `l_name`, `username`, `email`, `phone`, `password`) VALUES ('$firstName', '$lastName', '$userName', '$email', '$phone', '$hashedPassword')";
        $insert_result = $conn->query($insert_user);

        if ($insert_result) {
            send_response("Success", null, 200);
        } else {
            send_response("Failed to register user, please try again later", null, 500);
        }
    } else {
        send_response("Something went wrong, please try again later", null, 500);
    }
} else {
    send_response("Required values not passed", null, 400);
}

$conn->close();