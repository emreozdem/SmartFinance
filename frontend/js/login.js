document.addEventListener('DOMContentLoaded', function () {
    var loginButton = document.getElementById('loginButton');
    var loginForm = document.getElementById('loginForm');

    function handleLogin() {
        var formData = new FormData(loginForm);

        var jsonData = {};
        formData.forEach((value, key) => {
            jsonData[key] = value;
        });

        var jsonString = JSON.stringify(jsonData);
        fetch('http://localhost/SmartFinance/backend/users/login.php', {
            method: 'POST',
            body: jsonString
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                console.log(data.error);
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Fetch request failed or there are not any users');
        });
    }

    loginButton.addEventListener('click', handleLogin);

    loginForm.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            handleLogin();
        }
    });
});
