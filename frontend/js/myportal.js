$(document).ready(function () {
    fetch('http://localhost/SmartFinance/backend/users/session_status.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                window.location.href = '../pages/login.html';
            } else {

                
                var salutation = data.gender === "1" ? "Mr." : "Ms.";
                var greetingText = "Welcome to My Portal Page " + salutation + " " + data.name + " " + data.surname;


                $("#userFullName").html(greetingText);

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
                            const accountInfoElement = document.getElementById('userAccountInfo');

                            accountData.accounts.forEach(account => {
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.name = 'selectedAccounts';
                                checkbox.value = account.iban;
                                checkbox.id = account.iban;

                                const label = document.createElement('label');
                                label.htmlFor = account.iban;
                                label.appendChild(document.createTextNode(`${account.iban} - ${account.balance} TRY`));

                                const br = document.createElement('br');

                                accountInfoElement.appendChild(checkbox);
                                accountInfoElement.appendChild(label);
                                accountInfoElement.appendChild(br);
                            });

                        } else {
                            alert('Error: '+ accountData.error);
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
