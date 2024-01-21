document.addEventListener('DOMContentLoaded', function () {
    fetch('http://localhost/SmartFinance/backend/users/session_status.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                window.location.href = '../pages/login.html';
            } else {
                fetch('http://localhost/SmartFinance/backend/users/get_accounts_info.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ userId: data.userId })
                })
                    .then(response => response.json())
                    .then(accountData => {
                        if (accountData.success) {
                            const accountDropdown = document.getElementById('bankAccount');

                            accountDropdown.innerHTML = "";
                            accountData.accounts.forEach(account => {
                                const option = document.createElement('option');
                                option.value = account.iban;
                                option.text = `${account.iban} - ${account.balance} TRY`;

                                accountDropdown.add(option);
                            });

                            document.getElementById('transactionForm').addEventListener('submit', function (event) {
                                event.preventDefault();

                                const selectedAccount = document.getElementById('bankAccount').value;
                                const transactionType = document.querySelector('input[name="transactionType"]:checked').value;
                                const amount = document.getElementById('amount').value;

                                if (!selectedAccount || !transactionType || !amount) {
                                    alert('Please fill in all fields');
                                    return;
                                }

                                if (isNaN(amount) || amount <= 0) {
                                    alert('Invalid amount. Please enter a valid amount.');
                                    return;
                                }

                                const transactionData = {
                                    userId: data.userId,
                                    selectedAccount: selectedAccount,
                                    transactionType: transactionType,
                                    amount: amount
                                };

                                fetch('http://localhost/SmartFinance/backend/users/deposit_withdraw.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(transactionData)
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Transaction successful');
                                            window.location.href = '../pages/myportal.html';
                                        } else {
                                            if (data.error === "Insufficient funds") {
                                                alert('Insufficient funds. Please check your account balance.');
                                            } else if (data.error === "Invalid amount") {
                                                alert('Invalid amount. Please enter a valid amount.');
                                            } else {
                                                console.error('Error processing transaction:', data.error);
                                                alert('Error processing transaction');
                                            }
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Fetch Error:', error);
                                        alert('Fetch request failed');
                                    });
                            });

                        } else {
                            console.error('Error fetching account information:', accountData.error);
                            alert('Error fetching account information');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        alert('Fetch request failed');
                    });
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Fetch request failed');
        });
});
