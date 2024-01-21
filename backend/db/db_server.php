<?php
$serverName = "localhost";
$userName = "root";
$password = "";
$DBname = "smartfinance";

$DBconn = new mysqli($serverName, $userName, $password, $DBname);

if ($DBconn->connect_error) {
    die("Connection failed: " . $DBconn->connect_error);
}

$sqlUsers = "CREATE TABLE IF NOT EXISTS users (
            user_id INTEGER AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL,
            name VARCHAR(50) NOT NULL,
            surname VARCHAR(50) NOT NULL,
            gender TINYINT(1) NOT NULL,
            password VARCHAR(100) NOT NULL,
            phone_number VARCHAR(11) NOT NULL,
            PRIMARY KEY(user_id),
            UNIQUE KEY username_unique (username),
            INDEX phone_number_index (phone_number)
)";

if ($DBconn->query($sqlUsers) === TRUE) {
    
} else {
    echo "Error creating table: " . $DBconn->error;
}

$sqlAccounts = "CREATE TABLE IF NOT EXISTS bank_accounts (
            iban VARCHAR(30),
            balance DECIMAL(10, 2) DEFAULT 0,
            user_id INT,
            status INT,
            PRIMARY KEY (iban),
            FOREIGN KEY (user_id) REFERENCES users(user_id)
)";

if ($DBconn->query($sqlAccounts) === TRUE) {
    
} else {
    echo "Error creating table: " . $DBconn->error;
}


$sqlTransfers = "CREATE TABLE IF NOT EXISTS transfers (
    transfer_id INTEGER AUTO_INCREMENT,
    sender_iban VARCHAR(30),
    receiver_iban VARCHAR(30),
    amount DECIMAL(10, 2),
    transfer_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(transfer_id),
    FOREIGN KEY (sender_iban) REFERENCES bank_accounts(iban),
    FOREIGN KEY (receiver_iban) REFERENCES bank_accounts(iban)
)";

if ($DBconn->query($sqlTransfers) === TRUE) {
    
} else {
    echo "Error creating table: " . $DBconn->error;
}


$sqlBankAccountHistory = "CREATE TABLE IF NOT EXISTS bank_account_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    iban VARCHAR(30),
    balance DECIMAL(10, 2),
    user_id INT,
    status INT,
    operation_date DATETIME,
    operation CHAR(1)
)";

if ($DBconn->query($sqlBankAccountHistory) === TRUE) {

} else {
echo "Error creating table: " . $DBconn->error;
}

?>
