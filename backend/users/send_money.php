<?php

include_once("../db/db_server.php");

if ($DBconn->connect_error) {
    die("Connection failed: " . $DBconn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    $senderIban = $data['senderIban'];
    $receiverIban = $data['receiverIban'];
    $amount = $data['amount'];

    $checkBalanceSQL = "SELECT balance FROM bank_accounts WHERE iban = '$senderIban'";
    $result = $DBconn->query($checkBalanceSQL);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $balance = $row['balance'];

        if ($amount > 0) {
            if ($balance >= $amount) {
                $updateSenderBalanceSQL = "UPDATE bank_accounts SET balance = balance - '$amount' WHERE iban = '$senderIban'";
                $updateReceiverBalanceSQL = "UPDATE bank_accounts SET balance = balance + '$amount' WHERE iban = '$receiverIban'";
    
                if ($DBconn->query($updateSenderBalanceSQL) === TRUE && $DBconn->query($updateReceiverBalanceSQL) === TRUE) {
                    echo json_encode(array("success" => "Money transferred successfully."));
                    
                } else {
                    echo json_encode(array("error" => "An error occurred during the transfer."));
                }
            } else {
                echo json_encode(array("error" => "Insufficient balance in the sender's account."));
            }
        } else {
            echo json_encode(array("error" => "Invalid amount."));
        }
    } else {
        echo json_encode(array("error" => "Sender account not found."));
    }

    }
$DBconn->close();
?>
