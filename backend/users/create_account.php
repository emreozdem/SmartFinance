<?php
include_once("../db/db_server.php");

if ($DBconn->connect_error) {
    die("Connection failed: " . $DBconn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    session_start();
    $userId = $_SESSION['userId'];

    $sqlCreateAccount = "INSERT INTO bank_accounts (user_id, balance, iban, status) VALUES ('$userId', 0, 'IBAN" . rand(100000000, 999999999) . "',1)";
    if ($DBconn->query($sqlCreateAccount) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'New account created successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error creating new account']);
    }
}

$DBconn->close();
?>
