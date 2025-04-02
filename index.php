<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flipping Login & Register Form</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="wrapper">
            <div class="form-box login">
            <form id="loginForm">
                    <h1>Login</h1>
                    <div class="input-box">
                        <i class='bx bxs-user'></i>
                        <input type="text" id="loginUsername" placeholder="Username" required>
                    </div>
                    <div class="input-box">
                        <i class='bx bxs-lock-alt'></i>
                        <input type="password" id="loginPassword" placeholder="Password" required>
                        <i class='bx bx-hide' id="toggleLoginPassword"></i>
                    </div>
                    <div class="remember-forgot">
                        <label><input type="checkbox"> Remember me</label>
                        <a href="#">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn">Login</button>
                    <div class="register-link">
                        <p>Don't have an account? <a href="#" id="showRegister">Register</a></p>
                        
                    </div>

                    
                </form>
                <script>
document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData();
    formData.append("username", document.getElementById("loginUsername").value);
    formData.append("password", document.getElementById("loginPassword").value);

    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Server Response:", data);
        if (data.trim() === "success") {
            alert("Login successful!");
            window.location.href = "dashboard.php"; // Redirect to dashboard
        } else {
            alert("Login failed: " + data);
        }
    })
    .catch(error => console.error("Fetch Error:", error));
});
</script>
            </div>

            <div class="form-box register">
            <form id="registerForm">
                    <h1>Register</h1>
                    <div class="input-box">
                        <i class='bx bxs-user'></i>
                        <input type="text" id="username" placeholder="Username" required>
                    </div>
                    <div class="input-box">
                        <i class='bx bxs-envelope'></i>
                        <input type="email" id="email" placeholder="Email Address" required>
                    </div>
                    <div class="input-box">
                        <i class='bx bxs-lock-alt'></i>
                        <input type="password" id="registerPassword" placeholder="Password" required>
                        <i class='bx bx-hide' id="toggleRegisterPassword"></i>
                    </div>
                    <div class="input-box">
                        <i class='bx bxs-phone'></i>
                        <input type="tel" id="contact" placeholder="Contact Number" required>
                    </div>
                    <div class="input-box">
                        <i class='bx bxs-calendar'></i>
                        <input type="date" id="birth_date" required>
                    </div>
                    <button type="submit" class="btn">Register</button>
                    <div class="register-link">
                        <p>Already have an account? <a href="#" id="showLogin">Login</a></p>
                    </div>
                </form>
                <script>
document.getElementById("registerForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData();
    formData.append("username", document.getElementById("username").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("password", document.getElementById("registerPassword").value);
    formData.append("contact", document.getElementById("contact").value);
    formData.append("birth_date", document.getElementById("birth_date").value);

    fetch("register.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Server Response:", data);
        if (data.trim() === "success") {
            alert("Registration successful! You can now log in.");

         
            const wrapper = document.querySelector(".wrapper");
            if (wrapper) {
                wrapper.classList.remove("active");
            }

           
            document.getElementById("registerForm").reset();
        } else {
            alert("Registration failed user name or email taken already: " + data);
        }
    })
    .catch(error => console.error("Fetch Error:", error));
});
</script>
            </div>
        </div>
    </div>

    <script>
    
        function togglePasswordVisibility(toggleId, inputId) {
            const toggle = document.getElementById(toggleId);
            const input = document.getElementById(inputId);

            toggle.addEventListener('click', function () {
                if (input.type === "password") {
                    input.type = "text";
                    this.classList.replace('bx-hide', 'bx-show');
                } else {
                    input.type = "password";
                    this.classList.replace('bx-show', 'bx-hide');
                }
            });
        }

        togglePasswordVisibility('toggleLoginPassword', 'loginPassword');
        togglePasswordVisibility('toggleRegisterPassword', 'registerPassword');

     
        const wrapper = document.querySelector(".wrapper");
        const showRegister = document.getElementById("showRegister");
        const showLogin = document.getElementById("showLogin");

        showRegister.addEventListener("click", () => {
            wrapper.classList.add("active");
        });

        showLogin.addEventListener("click", () => {
            wrapper.classList.remove("active");
        });
        </script>

</body>
</html>
