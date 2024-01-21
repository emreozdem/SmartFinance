<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();

if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : "";
    $userName = isset($_SESSION["userName"]) ? $_SESSION["userName"] : "";
    $name = isset($_SESSION["name"]) ? $_SESSION["name"] : "";
    $surname = isset($_SESSION["surname"]) ? $_SESSION["surname"] : "";
    $gender = isset($_SESSION["gender"]) ? $_SESSION["gender"] : "";
    echo json_encode(["success" => true, "message" => "User is logged in", "userId" => $userId, "userName" => $userName, "name" => $name, "surname" => $surname, "gender" => $gender]);
} else {
    echo json_encode(["success" => false, "message" => "User is not logged in"]);
}
?>
