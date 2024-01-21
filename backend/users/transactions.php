<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_start();
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

include_once("../db/db_server.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_SESSION["userId"];

    $stmt1 = $DBconn->prepare("CALL GetTotalSentAmount(?)");
    $stmt1->bind_param('i', $userId);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $sentAmountResult = $result1->fetch_assoc();
    
    $stmt1->close();

    $stmt2 = $DBconn->prepare("CALL GetTotalReceivedAmount(?)");
    $stmt2->bind_param('i', $userId);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $receivedAmountResult = $result2->fetch_assoc();

    $stmt2->close();

    $totalTransferAmount = $receivedAmountResult['total_received_amount'] - $sentAmountResult['total_sent_amount'];

    echo json_encode([
        "success" => true,
        "totalTransferAmount" => $totalTransferAmount,
        "sentAmount" => $sentAmountResult['total_sent_amount'],
        "receivedAmount" => $receivedAmountResult['total_received_amount']
    ]);

    $DBconn->close();
} else {
    http_response_code(405);
    echo json_encode(["error" => "Unsupported request method"]);
}
?>
