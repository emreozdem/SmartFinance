<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once("../db/db_server.php");

    $data = json_decode(file_get_contents("php://input"), true);

    $userId = $data['userId'];
    $sqlUsers = $DBconn->prepare("SELECT * FROM bank_accounts WHERE user_id=?");
    $sqlUsers->bind_param("s", $userId);
    $sqlUsers->execute();
    $result = $sqlUsers->get_result();

    if ($result->num_rows > 0) {
        $ibanList = [];
        while ($row = $result->fetch_assoc()) {
            $ibanList[] = $row["iban"];
        }

        $ibanListString = "'" . implode("','", $ibanList) . "'";
        $selectTransfersSQL = "SELECT * FROM transfers WHERE sender_iban IN ($ibanListString) OR receiver_iban IN ($ibanListString)";

        $resultTransfers = $DBconn->query($selectTransfersSQL);

        if ($resultTransfers->num_rows > 0) {
            $transfers = [];
            while ($rowTransfers = $resultTransfers->fetch_assoc()) {
                $transferType = getTransferType($rowTransfers['sender_iban'], $rowTransfers['receiver_iban'], $ibanList);

                $rowTransfers['transfer_type'] = $transferType;
                $transfers[] = $rowTransfers;
            }
            echo json_encode(["success" => true, "transfers" => $transfers]);
        } else {
            echo json_encode(["success" => false, "error" => "No transfers found for the given IBAN list"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "No accounts found for the user"]);
    }

    $sqlUsers->close();
    $DBconn->close();
} else {
    http_response_code(405);
    echo json_encode(["error" => "Unsupported request method"]);
}

function getTransferType($senderIban, $receiverIban, $ibanList)
{
    if (in_array($senderIban, $ibanList) && in_array($receiverIban, $ibanList)) {
        return 'Transfer Between Accounts';
    } elseif (in_array($senderIban, $ibanList)) {
        return 'Money Out';
    } elseif (in_array($receiverIban, $ibanList)) {
        return 'Money In';
    }

    return 'Other';
}
?>
