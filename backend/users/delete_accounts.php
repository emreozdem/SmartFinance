<?php

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

include_once("../db/db_server.php");

if ($DBconn->connect_error) {
    die("Connection failed: " . $DBconn->connect_error);
}



    $ibanList = $data['ibanList'];
    $ibanString = "'" . implode("','", $ibanList) . "'";

    $sqlCheckBalances = "SELECT iban FROM bank_accounts WHERE iban IN ($ibanString) AND balance != 0";
    $resultCheckBalances = $DBconn->query($sqlCheckBalances);

    if ($resultCheckBalances->num_rows > 0) {
        $accounts = [];
        while ($row = $resultCheckBalances->fetch_assoc()) {
            $accounts[] = $row['iban'];
        }

        echo json_encode(['success' => false, 'error' => 'Cannot delete accounts with non-zero balances', 'accounts' => $accounts]);
    } else {
        $sqlDeleteAccounts = "UPDATE bank_accounts SET status=-1 WHERE iban IN ($ibanString)";
        if ($DBconn->query($sqlDeleteAccounts) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Selected accounts deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error deleting selected accounts']);
        }
    }
} 

    else {
        http_response_code(405);
        echo json_encode(["error" => "Unsupported request method"]);
}

$DBconn->close();
?>
