<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once("../db/db_server.php");

    $data = json_decode(file_get_contents("php://input"), true);

    $userName = $data['userName'];
    $password = $data['password'];
    $name = $data['name'];
    $surname = $data['surname'];
    $gender = $data['gender'];

    $sqlUsers = "SELECT * FROM users A WHERE A.username='$userName' AND A.password=MD5('$password')";
    $result = $DBconn->query($sqlUsers);
    $row = mysqli_fetch_assoc($result);



    if (isset($row)) {
        session_start();
        $_SESSION["login"] = true;
        $_SESSION["userName"] = $userName;
        $_SESSION["userId"] = $row["user_id"];
        $_SESSION["password"] = md5($password);
        $_SESSION["name"] = $row["name"];
        $_SESSION["surname"] = $row["surname"];
        $_SESSION["gender"] = $row["gender"];

        echo json_encode(["success" => true, "message" => "Login successful", "redirect" => "myportal.html"]);
    } else {
        
        

        echo json_encode(["success" => false, "error" => "Invalid userName or password"]);
    }

    $DBconn->close();
} else {
    http_response_code(405);
    echo json_encode(["error" => "Unsupported request method"]);
}
?>
