<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once("../db/db_server.php");
    $userId = $_POST['userId'];

    $getSenderIbansSQL = "SELECT iban, balance FROM bank_accounts WHERE user_id = '$userId' AND status = 1";

    $result = $DBconn->query($getSenderIbansSQL);

    if ($result->num_rows > 0) {
        $senderIbans = array();

        while ($row = $result->fetch_assoc()) {
            $senderIbans[] = $row;
        }

        echo json_encode(array("senderIbans" => $senderIbans));
    } else {
        echo json_encode(array("error" => "The user's bank account was not found."));
    }
} else {
    echo json_encode(array("error" => "Invalid request type."));
}

$DBconn->close();
?>
