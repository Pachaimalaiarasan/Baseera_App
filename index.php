<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In / Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary-color: #007BFF;
            --primary-hover: #0056b3;
            --background-color: #F4F7FC;
            --surface-color: #FFFFFF;
            --text-dark: #333;
            --text-light: #777;
            --border-color: #E0E0E0;
            --error-color: #D32F2F;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            height: 100%;
            overflow-x: hidden;
        }

        .auth-container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        /* --- Image Section --- */
        .auth-image-section {
            flex-basis: 50%;
            background: url('https://images.pexels.com/photos/3184423/pexels-photo-3184423.jpeg') no-repeat center center;
            background-size: cover;
            position: relative;
        }

        /* --- Form Section --- */
        .auth-form-section {
            flex-basis: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            box-sizing: border-box;
            background: var(--surface-color);
        }

        .form-wrapper {
            max-width: 400px;
            width: 100%;
            position: relative;
        }

        form {
            width: 100%;
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        form.hidden {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            pointer-events: none;
            transform: translateY(10px);
        }

        .form-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .form-subtitle {
            text-align: center;
            color: var(--text-light);
            margin-bottom: 2rem;
        }

        .input-group {
            margin-bottom: 1.25rem;
        }

        .input-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .input-field {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        }

        .input-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        
        .forgot-link, .toggle-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s;
        }
        .forgot-link:hover, .toggle-link:hover {
            color: var(--primary-hover);
        }

        .submit-btn {
            width: 100%;
            padding: 14px 0;
            margin-top: 1.5rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .submit-btn:hover {
            background: var(--primary-hover);
        }

        .toggle-row {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-light);
        }

        /* --- Responsive Design --- */
        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
            }

            .auth-image-section {
                flex-basis: auto;
                height: 250px;
                min-height: 30vh;
            }

            .auth-form-section {
                flex-basis: auto;
                padding: 2rem 1.5rem;
            }

            .form-wrapper {
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-image-section"></div>

        <div class="auth-form-section">
            <div class="form-wrapper">
                <form id="login-form" autocomplete="off">
                    <h1 class="form-title">Welcome Back!</h1>
                    <p class="form-subtitle">Sign in to continue to your account.</p>

                    <div class="input-group">
                        <label class="input-label" for="login-username">Phone / Email / Username</label>
                        <input class="input-field" id="login-username" type="text" placeholder="Enter your credentials" required>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="login-password">Password</label>
                        <input class="input-field" id="login-password" type="password" placeholder="Enter your password" required>
                    </div>

                    <div class="input-row">
                        <label class="remember-me">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>
                    <button type="submit" class="submit-btn">Log In</button>
                    <div class="toggle-row">
                        Don't have an account? <a class="toggle-link" onclick="toggleForm('signup')">Sign Up</a>
                    </div>
                </form>

                <form id="signup-form" class="hidden" autocomplete="off">
                    <h1 class="form-title">Create Account</h1>
                    <p class="form-subtitle">Get started with a new account.</p>
                    
                    <div class="input-group">
                        <label class="input-label" for="signup-username">Username</label>
                        <input class="input-field" id="signup-username" type="text" placeholder="Choose a username" required>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="signup-email">Email</label>
                        <input class="input-field" id="signup-email" type="email" placeholder="Enter your email" required>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="signup-phone">Phone</label>
                        <input class="input-field" id="signup-phone" type="tel" placeholder="Enter your phone number" required>
                    </div>
                    
                    <div class="input-group">
                        <label class="input-label" for="signup-password">Password</label>
                        <input class="input-field" id="signup-password" type="password" placeholder="Create a password" required>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="signup-confirm-password">Confirm Password</label>
                        <input class="input-field" id="signup-confirm-password" type="password" placeholder="Re-enter your password" required>
                    </div>

                    <button type="submit" class="submit-btn">Sign Up</button>
                    <div class="toggle-row">
                        Already have an account? <a class="toggle-link" onclick="toggleForm('login')">Log In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Toggle between forms
    function toggleForm(form) {
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('signup-form');

        if (form === 'signup') {
            loginForm.classList.add('hidden');
            signupForm.classList.remove('hidden');
        } else {
            loginForm.classList.remove('hidden');
            signupForm.classList.add('hidden');
        }
    }

    // LOGIN HANDLER
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        let loginValue = document.getElementById('login-username').value.trim();
        let payload = {
            action: 'login',
            password: document.getElementById('login-password').value
        };

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
            if (res.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful!',
                    text: res.message || 'Redirecting...',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    sessionStorage.setItem("auth_token", res.token);
                    sessionStorage.setItem("role", res.role);
                    const roleRedirects = {
                        customer: "customer_dashboard.html",
                        employee: "employee_dashboard.html",
                        manager: "manager_dashboard.html",
                        admin: "admin_dashboard.html"
                    };
                    window.location.href = roleRedirects[res.role] || "index.html";
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: res.message || 'Invalid credentials. Please check and try again.'
                });
            }
        })
        .catch(err => {
            console.error("Login error:", err);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong! Please try again later.'
            });
        });
    });

    // REGISTRATION HANDLER
    document.getElementById('signup-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const pwd = document.getElementById('signup-password').value;
        const confirm = document.getElementById('signup-confirm-password').value;
        if (pwd !== confirm) {
            Swal.fire({
                icon: 'warning',
                title: 'Passwords Do Not Match',
                text: 'Please make sure your passwords match before submitting.'
            });
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
            if (res.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    text: res.message || 'You can now log in with your new account.'
                }).then(() => {
                    toggleForm('login');
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: res.message || 'An error occurred. Please try again.'
                });
            }
        })
        .catch(err => {
            console.error("Signup error:", err);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong! Please try again later.'
            });
        });
    });
</script>
</body>
</html>