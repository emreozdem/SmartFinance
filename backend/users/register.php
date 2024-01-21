<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../db/db_server.php';

$data = json_decode(file_get_contents("php://input"));

$userName = $data->userName;
$name = $data->name;
$surname = $data->surname;
$gender = $data->gender;
$phoneNumber = $data->phoneNumber;
$password = $data->password;
$confirmPassword = $data->confirmPassword;

$sqlCheckUsername = "SELECT * FROM users WHERE username = '$userName'";
$resultUsername = $DBconn->query($sqlCheckUsername);

$sqlCheckPhoneNumber = "SELECT * FROM users WHERE phone_number = '$phoneNumber'";
$resultPhoneNumber = $DBconn->query($sqlCheckPhoneNumber);

$phonePattern = '/^05\d{9}$/';
$passwordPatern = '/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/';
if (!preg_match($phonePattern, $phoneNumber)) {
    echo json_encode(["success" => false, "error" => "Invalid phone number format"]);
} elseif (!preg_match($passwordPatern, $password)) {
    echo json_encode(["success" => false, "error" => "Invalid password format"]);
} elseif ($resultUsername->num_rows > 0) {
    echo json_encode(["success" => false, "error" => "Username already exists"]);
} elseif ($resultPhoneNumber->num_rows > 0) {
    echo json_encode(["success" => false, "error" => "Phone number already registered"]);
} elseif ($password !== $confirmPassword) {
    echo json_encode(["success" => false, "error" => "Passwords do not match"]);
} else {

    $hashedPassword = md5($password);
    $sqlInsertUser = "INSERT INTO users (username, name, surname, gender, phone_number, password) 
                      VALUES ('$userName', '$name', '$surname', '$gender', '$phoneNumber', '$hashedPassword')";

    if ($DBconn->query($sqlInsertUser) === TRUE) {
        $userId = $DBconn->insert_id;

        $sqlInsertAccount = "INSERT INTO bank_accounts (user_id, iban, status) 
                     VALUES ('$userId', 'IBAN" . rand(100000000, 999999999) . "', 1)";

        if ($DBconn->query($sqlInsertAccount) === TRUE) {
            echo json_encode(["success" => true, "message" => "Registration successful"]);
        } else {
            echo json_encode(["success" => false, "error" => "Error creating bank account"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Error creating user"]);
    }
}

$DBconn->close();
?>
