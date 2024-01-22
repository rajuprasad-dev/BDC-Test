<?php
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

define('JWT_SECRET_KEY', 'a_secret_json_web_token');

function generate_token($user_id)
{
    $payload = array(
        "iss" => 'Raju Prasad',
        "iat" => time(),
        "exp" => time() + (60 * 60 * 24),
        "sub" => 'Authencation',
        "user_id" => $user_id
    );

    $encoded_token = JWT::encode($payload, JWT_SECRET_KEY, "HS256");

    return $encoded_token;
}

function verify_token()
{
    try {
        $headers = getallheaders();

        if (!array_key_exists("Authorization", $headers) && !array_key_exists("authorization", $headers)) {
            send_response("Authorization Required", null, 403);
        }

        $token = $headers["Authorization"] ?? $headers["authorization"];
    } catch (Exception $e) {
        send_response("Unauthorized access not allowed", null, 403);
    }

    if (!empty($token)) {
        try {
            $decode_token = JWT::decode($token, new Key(JWT_SECRET_KEY, 'HS256'));

            // print_r($decode_token);
            // die();

            if (isset($decode_token->iss) && $decode_token->iss == 'Raju Prasad') {
                return $decode_token->user_id;
            } else {
                send_response("Unauthorized access not allowed", null, 403);
            }
        } catch (ExpiredException $e) {
            send_response("Authorization timeout, please re-login", null, 403);
        } catch (\Throwable $e) {
            print_r($e);
            send_response($e->getMessage(), null, 403);
        }
    } else {
        send_response("Authorization token is required to authenticate", null, 403);
    }
}