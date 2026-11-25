<?php

/* require_once 'logger.php';

logMessage("Hello World"); */

$allowedOrigin = "https://www.uia.no";

# Lenke til PowerAutomate api-endepunktet
$list_url = "";

header("Access-Control-Allow-Origin: $allowedOrigin");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


/* logMessage($_SERVER['REQUEST_METHOD']); */

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header("Content-Type: application/json");
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    http_response_code(403);
    echo json_encode(array(
        "msg" => "Access forbidden",
        "timestamp" => time(),
    ));
} else {
    if ($_SERVER['HTTP_ORIGIN'] === $allowedOrigin) {
        $body = json_decode(file_get_contents("php://input"));
        $email = $body->email;
        $message = $body->message;

        $ch = curl_init($list_url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'email' => $email,
                'message' => $message
            ])
        ]);

        $response = json_decode(curl_exec($ch));


        if (isset($response->error)) {
/*             logMessage('cURL Error: ' . curl_error($ch));
 */            http_response_code(500);
            echo json_encode(array(
                "msg" => "cURL error",
                "timestamp" => time(),
            ));
        } else {
/*             logMessage($response);
 */            http_response_code(200);
            echo json_encode(array(
                "msg" => "Message processed",
                "timestamp" => time(),
            ));
        }
        
    } else {
        http_response_code(403);
        echo json_encode(array(
            "msg" => "Access forbidden",
            "timestamp" => time(),
        ));
    }
}
