$(document).ready(function () {
    fetch('http://localhost/SmartFinance/backend/users/session_status.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                window.location.href = '../pages/login.html';
            } else {
                fetch('http://localhost/SmartFinance/backend/users/transactions_history.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ userId: data.userId })
                })
                .then(response => response.json())
                .then(transactionsData => {
                    if (transactionsData.success) {
                        console.log('Transfer Bilgileri:', transactionsData);
                        displayTransactions(transactionsData.transfers);
                    } else {
                        console.log('Transfer Bilgileri:', transactionsData);
                        alert('No money transfers have been made so far.');
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    alert('Fetch request for transactions failed');
                });

                fetch('http://localhost/SmartFinance/backend/users/transactions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ userId: data.userId })
                })
                .then(response => response.json())
                .then(transactionsData => {
                    if (transactionsData.success) {
                        console.log('Total Transfers:', transactionsData);
                        displayTotalTransfers(transactionsData);
                    } else {
                        console.error('Error fetching transactions:', transactionsData.error);
                        alert('Error fetching transactions');
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    alert('Fetch request for total sent and received amounts failed');
                });


            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Fetch request failed');
        });

        

});


function formatCurrency(amount) {
    return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(amount);
}

function displayTotalTransfers(totalTransferAmounts) {
    const totalTransferAmountsContainer = $('#totalTransferAmountsContainer');

    if (totalTransferAmounts != null) {
        const table = $('<table>').addClass('table table-bordered');
        const thead = $('<thead>').append('<tr><th>Total Money Sent</th><th>Total Money Received</th><th>Total of All Transfers</th></tr>');
        table.append(thead);

        const tbody = $('<tbody>');

        const row = $('<tr>');
        row.append(`<td>${formatCurrency(totalTransferAmounts.sentAmount)}</td>`);
        row.append(`<td>${formatCurrency(totalTransferAmounts.receivedAmount)}</td>`);

        if (totalTransferAmounts.totalTransferAmount < 0) {
            row.append(`<td style="color: red; font-weight: bold;">${"" + formatCurrency(totalTransferAmounts.totalTransferAmount)}</td>`);
        } else {
            row.append(`<td style="color: green; font-weight: bold;">${"+" +formatCurrency(totalTransferAmounts.totalTransferAmount)}</td>`);
        }

        tbody.append(row);

        table.append(tbody);
        totalTransferAmountsContainer.append(table);
    } else {
        totalTransferAmountsContainer.append('<p>No transactions found.</p>');
    }
}



function displayTransactions(transfers) {

    const transactionsContainer = $('#transactionsContainer');
    if (transfers.length > 0) {
        const table = $('<table>').addClass('table table-bordered');
        const thead = $('<thead>').append('<tr><th>Transfer Type</th><th>Sender IBAN</th><th>Receiver IBAN</th><th>Amount</th><th>Transfer Date</th></tr>');
        const tbody = $('<tbody>');

        transfers.forEach(transfer => {
            const row = $('<tr>');
            row.append(`<td>${transfer.transfer_type}</td>`);
            
            row.append(`<td>${transfer.sender_iban}</td>`);
            row.append(`<td>${transfer.receiver_iban}</td>`);
            row.append(`<td>${transfer.amount}</td>`);
            row.append(`<td>${transfer.transfer_date}</td>`);
            tbody.append(row);
        });

        table.append(thead);
        table.append(tbody);

        transactionsContainer.append(table);
    } else {
        transactionsContainer.append('<p>No transactions found.</p>');
    }
}