<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once("../db/db_server.php");

    $receiverIban = $_POST['receiverIban'];
   
   
    $ibanPattern = '/^IBAN\d{9}$/';

    if (!preg_match($ibanPattern, $receiverIban)) {
        echo json_encode(array("error" => "Receiver IBAN is invalid."));

    }

    else{

    $checkReceiverIbanSQL = "SELECT * FROM bank_accounts WHERE iban='$receiverIban' AND status=1";
    $result = $DBconn->query($checkReceiverIbanSQL);

    if ($result->num_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Receiver IBAN not found."]);
    }

    $DBconn->close();

    } 

    }

    else {
        http_response_code(405);
        echo json_encode(["error" => "Unsupported request method"]);
    }

    


    
?>
