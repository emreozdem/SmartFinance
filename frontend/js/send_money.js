fetch('http://localhost/SmartFinance/backend/users/session_status.php')
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            window.location.href = '../pages/login.html';
        } else {
            return fetch('http://localhost/SmartFinance/backend/users/send_moneyselectSender.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'userId=' + data.userId,
            });
        }
    })
    .then(response => response.json())
    .then(bankData => {
        updateSenderIbans(bankData);
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('An error occurred: Error fetching bank accounts');
    });

$("#sendMoneyButton").on("click", function () {
    sendMoney();
});

function formatBalance(balance) {
    return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(balance);
}

function updateSenderIbans(bankData) {
    var senderIbanSelect = document.getElementById('senderIban');
    senderIbanSelect.innerHTML = '';

    bankData.senderIbans.forEach(function (account) {
        var option = document.createElement('option');
        option.value = account.iban;
        option.text = "IBAN: " + account.iban + " Balance: " + formatBalance(account.balance);
        senderIbanSelect.appendChild(option);
    });
}

function sendMoney() {
    var senderIban = document.getElementById('senderIban').value;
    var receiverIban = document.getElementById('receiverIban').value;
    var amount = parseFloat(document.getElementById('amount').value);

    fetch('http://localhost/SmartFinance/backend/users/check_receiver_iban.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'receiverIban=' + receiverIban,
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.log(data);
                alert("Error: " + data.error);
            } else {
                initiateMoneyTransfer(senderIban, receiverIban, amount);
            }
        })
        .catch(error => {
            console.error('Error: An error occurred while checking the receiver IBAN.', error);
            alert('An error occurred: Error checking receiver IBAN.');
        });
}

function saveTransferLog(senderIban, receiverIban, amount) {
    fetch('http://localhost/SmartFinance/backend/users/save_transfer.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify({
            senderIban: senderIban,
            receiverIban: receiverIban,
            amount: amount,
        }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert("Error: " + data.error);
            } else {
                window.location.href = '../pages/myportal.html';
            }
        })
        .then(data => {
            console.log('PUT request successfully completed:', data);
        })
        .catch(error => {
            console.error('An error occurred during the PUT request:', error);
        });
}

function initiateMoneyTransfer(senderIban, receiverIban, amount) {
    fetch('http://localhost/SmartFinance/backend/users/send_money.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify({
            senderIban: senderIban,
            receiverIban: receiverIban,
            amount: amount,
        }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Success: " + data.success);
                saveTransferLog(senderIban, receiverIban, amount);
                console.log(data.transfer);
            } else {
                console.log(data);
                alert("Error: " + data.error);
            }
        })
        .catch(error => {
            console.error('Error: An error occurred during the money transfer.', error);
            alert('An error occurred: Error during money transfer.');
        });
}
