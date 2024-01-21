<?php
include_once '../db/db_server.php';

if ($DBconn->connect_error) {
    die("Connection failed: " . $DBconn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

$senderIban = $data['senderIban'];
$receiverIban = $data['receiverIban'];
$amount = $data['amount'];

$sql = "INSERT INTO transfers (sender_iban, receiver_iban, amount) VALUES ('$senderIban', '$receiverIban', $amount)";

if ($DBconn->query($sql) === TRUE) {
    $transferId = $DBconn->insert_id;
    $response = array('success' => 'Transfer successfully saved.', 'transferId' => $transferId);
} else {
    $response = array('error' => 'Error saving transfer: ' . $DBconn->error);
}

header('Content-Type: application/json');
echo json_encode($response);

$DBconn->close();
?>
