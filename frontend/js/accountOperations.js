function createAccount() {
    fetch("http://localhost/SmartFinance/backend/users/create_account.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },

    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '../pages/myportal.html';
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.log(error.message);
            console.error("Fetch Error:", error);
            alert("Error creating new account");
        });

        
}

function deleteSelectedAccounts() {
    const selectedAccounts = Array.from(document.querySelectorAll('input[name="selectedAccounts"]:checked')).map(checkbox => checkbox.value);
    const ibanList = selectedAccounts;
    console.log(ibanList)
    fetch("http://localhost/SmartFinance/backend/users/delete_accounts.php", {
        method: "DELETE",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        
        body: JSON.stringify({ ibanList: ibanList})
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '../pages/myportal.html';
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            alert("Error deleting selected accounts");
        });


}
