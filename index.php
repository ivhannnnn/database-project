<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flipping Login & Register Form</title>
  <link rel="stylesheet" href="style.css?v=5">
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
            <a href="#" id="showForgotPassword">Forgot Password?</a>
          </div>
          <button type="submit" class="btn">Login</button>
          <div class="register-link">
            <p>Don't have an account? <a href="#" id="showRegister">Register</a></p>
          </div>
        </form>
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
      </div>

      <div class="form-box forgot-password" style="display: none;">
        <form id="forgotPasswordForm">
          <h1>Reset Password</h1>
          <div class="input-box">
            <i class='bx bxs-envelope'></i>
            <input type="email" id="forgotEmail" placeholder="Enter your email" required>
          </div>
          <button type="submit" class="btn">Send Verification Code</button>
          <div class="register-link">
            <p>Remembered your password? <a href="#" id="backToLogin">Login</a></p>
          </div>
        </form>
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
    document.getElementById("showRegister").addEventListener("click", () => {
  wrapper.classList.add("active");
  document.querySelector(".login").style.visibility = "hidden"; // Hide login form
  document.querySelector(".login").style.opacity = "0"; // Hide login form
  document.querySelector(".register").style.visibility = "visible"; // Show register form
  document.querySelector(".register").style.opacity = "1"; // Show register form
  document.querySelector(".forgot-password").style.visibility = "hidden"; // Hide forgot password form
  document.querySelector(".forgot-password").style.opacity = "0"; // Hide forgot password form
});

document.getElementById("showLogin").addEventListener("click", () => {
  wrapper.classList.remove("active");
  document.querySelector(".login").style.visibility = "visible"; // Show login form
  document.querySelector(".login").style.opacity = "1"; // Show login form
  document.querySelector(".register").style.visibility = "hidden"; // Hide register form
  document.querySelector(".register").style.opacity = "0"; // Hide register form
  document.querySelector(".forgot-password").style.visibility = "hidden"; // Hide forgot password form
  document.querySelector(".forgot-password").style.opacity = "0"; // Hide forgot password form
});

document.getElementById("showForgotPassword").addEventListener("click", () => {
  document.querySelector(".login").style.visibility = "hidden"; // Hide login form
  document.querySelector(".login").style.opacity = "0"; // Hide login form
  document.querySelector(".register").style.visibility = "hidden"; // Hide register form
  document.querySelector(".register").style.opacity = "0"; // Hide register form
  document.querySelector(".forgot-password").style.visibility = "visible"; // Show forgot password form
  document.querySelector(".forgot-password").style.opacity = "1"; // Show forgot password form
});

document.getElementById("backToLogin").addEventListener("click", () => {
  document.querySelector(".forgot-password").style.visibility = "hidden"; // Hide forgot password form
  document.querySelector(".forgot-password").style.opacity = "0"; // Hide forgot password form
  document.querySelector(".login").style.visibility = "visible"; // Show login form
  document.querySelector(".login").style.opacity = "1"; // Show login form
});
    // Login Form Submission
    document.getElementById("loginForm").addEventListener("submit", function (event) {
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
          window.location.href = "dashboard.php";
        } else {
          alert("Login failed: " + data);
        }
      })
      .catch(error => console.error("Fetch Error:", error));
    });

    // Register Form Submission
    document.getElementById("registerForm").addEventListener("submit", function (event) {
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
          wrapper.classList.remove("active");
          document.getElementById("registerForm").reset();
        } else {
          alert("Registration failed: " + data);
        }
      })
      .catch(error => console.error("Fetch Error:", error));
    });

    // Forgot Password Form Submission
    document.getElementById("forgotPasswordForm").addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData();
      formData.append("email", document.getElementById("forgotEmail").value);

      fetch("send_verification.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        alert(data); 
      })
      .catch(error => {
        console.error("Error:", error);
        alert("Something went wrong.");
      });
    });
  </script>
</body>
</html>
