<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once("../db/db_server.php");

    $data = json_decode(file_get_contents("php://input"), true);

    $selectedAccount = $data['selectedAccount'];
    $amount = $data['amount'];
    $transactionType = $data['transactionType'];

    if ($amount <= 0) {
        echo json_encode(["success" => false, "error" => "Invalid amount"]);
        exit;
    }

    $balanceCheckQuery = "SELECT balance FROM bank_accounts WHERE iban = '$selectedAccount'";
    $balanceCheckResult = $DBconn->query($balanceCheckQuery);

    if ($balanceCheckResult->num_rows > 0) {
        $row = $balanceCheckResult->fetch_assoc();
        $currentBalance = $row["balance"];

        if ($transactionType === "withdraw" && $amount > $currentBalance) {
            echo json_encode(["success" => false, "error" => "Insufficient funds"]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "error" => "Account not found"]);
        exit;
    }
    $sqlTransaction = "";

    if ($transactionType === "deposit") {
        $sqlTransaction = "UPDATE bank_accounts SET balance = balance + $amount WHERE iban = '$selectedAccount'";
    } elseif ($transactionType === "withdraw") {
        $sqlTransaction = "UPDATE bank_accounts SET balance = balance - $amount WHERE iban = '$selectedAccount'";
    } else {
        echo json_encode(["success" => false, "error" => "Invalid transaction type"]);
        exit;
    }

    if ($DBconn->query($sqlTransaction) === TRUE) {
        echo json_encode(["success" => true, "message" => "Transaction successful"]);
    } else {
        echo json_encode(["success" => false, "error" => "Error performing transaction"]);
    }

    $DBconn->close();
} else {
    http_response_code(405);
    echo json_encode(["error" => "Unsupported request method"]);
}
?>
