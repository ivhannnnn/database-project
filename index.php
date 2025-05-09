<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Flipping Login & Register Form</title>
  <link rel="stylesheet" href="style.css?v=6" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <style>
    .fade-out {
      animation: fadeOut 0.8s forwards;
    }

    @keyframes fadeOut {
      0% {
        opacity: 1;
        transform: scale(1);
      }
      100% {
        opacity: 0;
        transform: scale(0.95);
      }
    }

    .form-box {
      transition: all 0.5s ease;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="wrapper">
     
      <div class="form-box login">
        <form id="loginForm">
          <h1>Login</h1>
          <div class="input-box">
            <i class="bx bxs-user"></i>
            <input type="text" id="loginUsername" placeholder="Username" required />
          </div>
          <div class="input-box">
            <i class="bx bxs-lock-alt"></i>
            <input type="password" id="loginPassword" placeholder="Password" required />
            <i class="bx bx-hide" id="toggleLoginPassword"></i>
          </div>
          <div class="remember-forgot">
            <label><input type="checkbox" /> Remember me</label>
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
            <i class="bx bxs-user"></i>
            <input type="text" id="username" placeholder="Username" required />
          </div>
          <div class="input-box">
            <i class="bx bxs-envelope"></i>
            <input type="email" id="email" placeholder="Email Address" required />
          </div>
          <div class="input-box">
            <i class="bx bxs-lock-alt"></i>
            <input type="password" id="registerPassword" placeholder="Password" required />
            <i class="bx bx-hide" id="toggleRegisterPassword"></i>
          </div>
          <div class="input-box">
            <i class="bx bxs-phone"></i>
            <input type="tel" id="contact" placeholder="Contact Number" required />
          </div>
          <div class="input-box">
            <i class="bx bxs-calendar"></i>
            <input type="date" id="birth_date" required />
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
            <i class="bx bxs-envelope"></i>
            <input type="email" id="forgotEmail" placeholder="Enter your email" required />
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
      toggle.addEventListener("click", () => {
        const show = input.type === "password";
        input.type = show ? "text" : "password";
        toggle.classList.replace(show ? "bx-hide" : "bx-show", show ? "bx-show" : "bx-hide");
      });
    }

    togglePasswordVisibility("toggleLoginPassword", "loginPassword");
    togglePasswordVisibility("toggleRegisterPassword", "registerPassword");

    const wrapper = document.querySelector(".wrapper");

    document.getElementById("showRegister").addEventListener("click", () => {
      wrapper.classList.add("active");
      document.querySelector(".login").style.visibility = "hidden";
      document.querySelector(".login").style.opacity = "0";
      document.querySelector(".register").style.visibility = "visible";
      document.querySelector(".register").style.opacity = "1";
      document.querySelector(".forgot-password").style.display = "none";
    });

    document.getElementById("showLogin").addEventListener("click", () => {
      wrapper.classList.remove("active");
      document.querySelector(".login").style.visibility = "visible";
      document.querySelector(".login").style.opacity = "1";
      document.querySelector(".register").style.visibility = "hidden";
      document.querySelector(".register").style.opacity = "0";
      document.querySelector(".forgot-password").style.display = "none";
    });

    document.getElementById("showForgotPassword").addEventListener("click", () => {
      document.querySelector(".login").style.visibility = "hidden";
      document.querySelector(".login").style.opacity = "0";
      document.querySelector(".register").style.visibility = "hidden";
      document.querySelector(".register").style.opacity = "0";
      document.querySelector(".forgot-password").style.display = "block";
    });

    document.getElementById("backToLogin").addEventListener("click", () => {
      document.querySelector(".forgot-password").style.display = "none";
      document.querySelector(".login").style.visibility = "visible";
      document.querySelector(".login").style.opacity = "1";
    });

    document.getElementById("loginForm").addEventListener("submit", function (e) {
      e.preventDefault();
      let formData = new FormData();
      formData.append("username", document.getElementById("loginUsername").value);
      formData.append("password", document.getElementById("loginPassword").value);

      fetch("login.php", {
        method: "POST",
        body: formData
      })
        .then((res) => res.text())
        .then((data) => {
          if (data.trim() === "success") {
            alert("Login successful!");
            document.querySelector(".login").classList.add("fade-out");
            setTimeout(() => {
              window.location.href = "dashboard.php";
            }, 800);
          } else {
            alert("Login failed: " + data);
          }
        })
        .catch((err) => console.error("Login Error:", err));
    });

    document.getElementById("registerForm").addEventListener("submit", function (e) {
      e.preventDefault();
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
        .then((res) => res.text())
        .then((data) => {
          if (data.trim() === "success") {
            alert("Registration successful! You can now log in.");
            wrapper.classList.remove("active");
            document.getElementById("registerForm").reset();
            document.querySelector(".register").style.visibility = "hidden";
            document.querySelector(".register").style.opacity = "0";
            document.querySelector(".login").style.visibility = "visible";
            document.querySelector(".login").style.opacity = "1";
          } else {
            alert("Registration failed: " + data);
          }
        })
        .catch((err) => console.error("Register Error:", err));
    });

    document.getElementById("forgotPasswordForm").addEventListener("submit", function (e) {
      e.preventDefault();
      let formData = new FormData();
      formData.append("email", document.getElementById("forgotEmail").value);

      fetch("send_verification.php", {
        method: "POST",
        body: formData
      })
        .then((res) => res.text())
        .then((data) => alert(data))
        .catch((err) => {
          console.error("Forgot Password Error:", err);
          alert("Something went wrong.");
        });
    });
  </script>
</body>
</html>
