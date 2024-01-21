<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    session_start();
    session_unset();
    session_destroy();

    echo json_encode(["success" => true, "message" => "Logout successful"]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Unsupported request method"]);
}
?>
