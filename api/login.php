<?php
include_once "./api_const.php";
include_once "../config/db.php";
include_once "../config/token.php";

if (isset($api_data['payload'])) {
    $payload = json_decode(base64_decode($api_data['payload']), true);

    if (
        empty($payload['userName']) ||
        empty($payload['password'])
    ) {
        send_response("Required valued not passed", $payload, 400);
    }

    $userName = $payload['userName'];
    $password = $payload['password'];
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $currentTime = time();
    $earliestTime = $currentTime - (60 * 5);
    $maxAttempts = 5;

    $check_existance = "SELECT * FROM `users` WHERE `email` = '$email' OR `userName` = '$userName'";
    $result = $conn->query($check_existance);

    // die($check_existance);
    // die($result);

    if ($result) {
        // die("working");

        if ($result->num_rows === 0) {
            send_response("It seems like user doesn't exist, please register", null, 401);
        }

        $user_data = $result->fetch_assoc();
        $user_id = $user_data['id'];
        $db_passsword = $user_data['password'];

        // if diabled return user 
        if ($user_data['status'] === "DISABLED") {
            send_response("It seems your account is blocked due to multiple attempts, please contact admin to unblock your account.");
        }

        // echo ("USER PW: " . $password);
        // echo "\n";
        // echo ("DB PW: " . $db_passsword);
        // print_r($user_data);
        // die();

        // check login attempt
        $check_attempts = "SELECT COUNT(*) AS `attempt_count` FROM `login` WHERE `user_id` = '$user_id' AND `ip_address` = '$ipAddress' AND `attempted_on` >= FROM_UNIXTIME($earliestTime)";

        $check_attempts_result = $conn->query($check_attempts);

        if ($check_attempts_result) {
            $check_attempts_data = $check_attempts_result->fetch_assoc();
            $attempt_count = $check_attempts_data['attempt_count'];

            // die($attempt_count);

            if ($attempt_count >= ($maxAttempts - 1)) {
                $block_user = "UPDATE `users` SET `status` = 'DISABLED' WHERE `id` = '$user_id'";
                $block_user_result = $conn->query($block_user);

                send_response("You have taken multiple attempts your account is blocked for safety reasons, please contact admin to unblock your account.");
            } else {

                // check password 
                if (!password_verify($password, $db_passsword)) {
                    $log_login_attempt = "INSERT INTO `login`(`user_id`, `ip_address`) VALUES ('$user_id', '$ipAddress')";
                    $insert_result = $conn->query($log_login_attempt);

                    $remaining_attempts = $maxAttempts - ($attempt_count + 1);

                    send_response("Please enter a valid password, {$remaining_attempts} attempts remaining", null, 401);
                }

                $token = generate_token($user_id);

                $response = [
                    "token" => $token,
                    "firstName" => $user_data['f_name'],
                    "lastName" => $user_data['l_name'],
                ];

                send_response("Success", $response, 200);
            }
        } else {
            send_response("Something went wrong, please try again later", null, 500);
        }
    } else {
        send_response("Something went wrong, please try again later", null, 500);
    }
} else {
    send_response("Required values not passed", null, 400);
}

$conn->close();