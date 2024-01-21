$(document).ready(function () {
    $("#registrationForm").submit(function (event) {
        event.preventDefault();

        var formData = {
            userName: $("#userName").val(),
            name: $("#name").val(),
            surname: $("#surname").val(),
            gender: $("#gender").val(),
            phoneNumber: $("#phoneNumber").val(),
            password: $("#password").val(),
            confirmPassword: $("#confirmPassword").val()
        };

        fetch("http://localhost/SmartFinance/backend/users/register.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(formData)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = "./login.html";
                } else {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
                alert("Fetch request failed");
            });
    });



    $("#navbarContainer").load("../assets/navbar.html");
    $("#footerContainer").load("../assets/footer.html");
});