$(document).ready(function() {
    fetch('http://localhost/SmartFinance/backend/users/session_status.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.userName) {
                
                console.log(data);

                $("#username_transaction_button").html('<a class="nav-link" href="../pages/myportal.html">' + data.userName + '</a>');

                $("#logout_button").html(`
                    <li class="nav-item">
                        <a class="nav-link" id="logoutButton" href="#">Logout</a>
                    </li>
                `);


            } else {
                $("#login_button").html('<a class="nav-link" href="./login.html">Login</a>');
                $("#register_button").html('<a class="nav-link" href="./register.html">Register</a>');
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Fetch request failed');
        });

    $(document).on("click", "#logoutButton", function() {
        fetch('http://localhost/SmartFinance/backend/users/logout.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    alert(data.message);
                    window.location.href = './logout.html';
                } else {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('Fetch request failed');
            });
    });
});
