<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once("../db/db_server.php");

    $data = json_decode(file_get_contents("php://input"), true);

    $userId= $data['userId'];

    $sqlUsers = "SELECT * FROM bank_accounts WHERE user_id='$userId' AND status=1";
    $result = $DBconn->query($sqlUsers);

    if ($result->num_rows > 0) {
        $accounts = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $accounts[] = [
                "iban" => $row["iban"],
                "balance" => $row["balance"]
            ];
        }

        echo json_encode(["success" => true, "accounts" => $accounts]);
    } else {
        echo json_encode(["success" => false, "error" => "No accounts found for the user"]);
    }

    $DBconn->close();
} else {
    http_response_code(405);
    echo json_encode(["error" => "Unsupported request method"]);
}
?>
