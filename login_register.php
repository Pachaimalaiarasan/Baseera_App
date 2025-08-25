<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In / Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fff6fe;
            min-height: 100vh;
        }
        .header-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }
        .container {
            max-width: 400px;
            margin: -30px auto 0 auto;
            background: #fff;
            border-radius: 30px 30px 0 0;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 30px 20px 24px 20px;
        }
        .form-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            margin-top: 0.7rem;
        }
        .input-label {
            color: #222;
            margin-top: 1.1rem;
            margin-bottom: 0.3rem;
            display: block;
            font-weight: 500;
            font-size: 1rem;
        }
        .input-field {
            width: 100%;
            padding: 13px 12px;
            border: 1px solid #bdbdbd;
            border-radius: 8px;
            margin-bottom: 6px;
            font-size: 1rem;
            background: #fafafa;
            box-sizing: border-box;
        }
        .input-row { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; }
        .remember-me { font-size: 0.97rem; }
        .forgot-link {
            color: #1894f2;
            font-size: 1rem;
            text-decoration: none;
            cursor: pointer;
        }
        .submit-btn {
            width: 100%;
            padding: 16px 0;
            color: #fff;
            background: #1894f2;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 22px;
            margin-bottom: 13px;
            transition: background 0.22s;
        }
        .submit-btn:hover, .submit-btn:active { background: #0e67b2; }
        .toggle-row {
            text-align: center;
            margin-top: 16px;
            font-size: 1rem;
        }
        .toggle-row a { color: #1894f2; text-decoration: none; margin-left: 4px; font-weight: 500; cursor: pointer; }
        .hidden { display: none; }
        @media (max-width: 500px) {
            .header-img { height: 120px; border-radius: 0 0 18px 18px; }
            .container { max-width: 100%; margin: -14vw 0 0 0; padding: 22px 8vw 18px 8vw; border-radius: 18px 18px 0 0; }
            .form-title { font-size: 1.45rem; }
            .input-label { font-size: 0.97rem; }
            .input-field { font-size: 0.99rem; padding: 12px 10px; }
            .submit-btn { font-size: 1rem; padding: 13px 0; }
            .toggle-row { font-size: 0.98rem; margin-top: 13px; }
        }
        @media (max-width: 360px) {
            .container { padding: 12px 4vw; }
            .input-label, .input-field, .toggle-row, .submit-btn { font-size: 0.95rem; }
            .form-title { font-size: 1.1rem; margin-bottom: 1rem; }
        }
    </style>
</head>
<body>
    <img class="header-img" src="https://images.pexels.com/photos/3806265/pexels-photo-3806265.jpeg" alt="Header image" loading="lazy">
    <div class="container">
        <!-- Login Form -->
        <form id="login-form" autocomplete="on">
            <div class="form-title">Sign In</div>
            <label class="input-label" for="login-username">Phone / Email / Username</label>
            <input class="input-field" id="login-username" type="text" placeholder="Enter your Phone/Email" required>

            <label class="input-label" for="login-password">Password</label>
            <input class="input-field" id="login-password" type="password" placeholder="Enter your Password" required>

            <div class="input-row">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="#" class="forgot-link">Forgot Password?</a>
            </div>
            <button type="submit" class="submit-btn">Log In</button>
            <div class="toggle-row">
                Don't have an account? <a onclick="toggleForm('signup')">Sign Up</a>
            </div>
        </form>

        <!-- Sign Up Form -->
        <form id="signup-form" class="hidden" autocomplete="on">
            <div class="form-title">Sign Up</div>
            <label class="input-label" for="signup-username">Username</label>
            <input class="input-field" id="signup-username" type="text" placeholder="Enter your username" required>

            <label class="input-label" for="signup-email">Email</label>
            <input class="input-field" id="signup-email" type="email" placeholder="Enter your email" required>

            <label class="input-label" for="signup-phone">Phone</label>
            <input class="input-field" id="signup-phone" type="tel" placeholder="Enter your phone number" required>

            <label class="input-label" for="signup-password">Password</label>
            <input class="input-field" id="signup-password" type="password" placeholder="Enter your password" required>

            <label class="input-label" for="signup-confirm-password">Confirm Password</label>
            <input class="input-field" id="signup-confirm-password" type="password" placeholder="Re-enter your password" required>

            <button type="submit" class="submit-btn">Sign Up</button>
            <div class="toggle-row">
                Already have an account? <a onclick="toggleForm('login')">Log In</a>
            </div>
        </form>
    </div>

<script>
// Toggle between forms
function toggleForm(form) {
    document.getElementById('login-form').classList.toggle('hidden', form === 'signup');
    document.getElementById('signup-form').classList.toggle('hidden', form === 'login');
    window.scrollTo({top: 0, behavior: "smooth"});
}

// LOGIN HANDLER
document.getElementById('login-form').addEventListener('submit', function(e) {
    e.preventDefault();
    let loginValue = document.getElementById('login-username').value.trim();
    let payload = { action: 'login', password: document.getElementById('login-password').value };

    // Detect type and lowercase if email/username
    if (/^[^@]+@[^@]+\.[^@]+$/.test(loginValue)) {
        payload.email = loginValue.toLowerCase();
    } else if (/^[0-9]+$/.test(loginValue)) {
        payload.phone = loginValue;
    } else {
        payload.username = loginValue.toLowerCase();
    }
    

    fetch('Backend/auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(res => {
        alert(res.message || res.status);
        if (res.status === 'success') {
            sessionStorage.setItem("auth_token", res.token);
            sessionStorage.setItem("role", res.role);
            if (res.role === 'customer') {
                window.location.href = "customer_dashboard.html";
            } else if (res.role === 'employee') {
                window.location.href = "employee_dashboard.html";
            } else if (res.role === 'manager') {
                window.location.href = "manager_dashboard.html";
            } else if (res.role === 'admin') {
                window.location.href = "admin_dashboard.html";
            } else {
                alert("Unknown role: " + res.role);
            }
        }
    })
    .catch(err => {
        console.error("Login error:", err);
        alert("Login failed due to network/server error.");
    });
});

// REGISTRATION HANDLER
document.getElementById('signup-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const pwd = document.getElementById('signup-password').value;
    const confirm = document.getElementById('signup-confirm-password').value;
    if (pwd !== confirm) {
        alert('Passwords do not match');
        return;
    }
    const data = {
        action: 'register',
        username: document.getElementById('signup-username').value.trim().toLowerCase(),
        email: document.getElementById('signup-email').value.trim().toLowerCase(),
        phone: document.getElementById('signup-phone').value.trim(),
        password: pwd
    };
    fetch('Backend/auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        alert(res.message || res.status);
        if (res.status === 'success') {
            toggleForm('login');
        }
    })
    .catch(err => {
        console.error("Signup error:", err);
        alert("Signup failed due to network/server error.");
    });
});
</script>
</body>
</html>
