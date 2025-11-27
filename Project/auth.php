<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup - AquaGuard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .auth-tab {
            padding: 10px 20px;
            background: #f0f0f0;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 0 5px;
        }
        .auth-tab.active {
            background: #0066cc;
            color: white;
        }
        .auth-form {
            display: none;
        }
        .auth-form.active {
            display: block;
        }
        .auth-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
        }
        .auth-section {
            width: 45%;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.548M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                <span class="logo-text">AquaGuard</span>
            </div>
            <ul class="nav-menu">
                <li><a href="index.html">Home</a></li>
                <li><a href="index.html#features">Features</a></li>
                <li><a href="index.html#how-it-works">How It Works</a></li>
                <li><a href="index.html#benefits">Benefits</a></li>
                <li><a href="index.html#contact" class="btn-nav">Get Started</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">Login or Signup</h1>
                        <p class="hero-subtitle">Access your account or create a new one to start monitoring your water system.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact">
            <div class="container">
                <div class="auth-tabs">
                    <button class="auth-tab active" onclick="showForm('login')">Login</button>
                    <button class="auth-tab" onclick="showForm('signup')">Signup</button>
                </div>
                <div class="auth-container">
                    <div class="auth-section">
                        <div id="login-form" class="auth-form active">
                            <h2 class="section-title">Login to Your Account</h2>
                            <p class="contact-subtitle">Access your dashboard and manage your water monitoring.</p>
                            <?php if (isset($_GET['login_error'])): ?>
                                <div style="color: red; margin-bottom: 10px; padding: 10px; background: #ffebee; border-radius: 5px;">
                                    <?php
                                    switch ($_GET['login_error']) {
                                        case 'missing_fields':
                                            echo 'Please fill in all fields.';
                                            break;
                                        case 'invalid_credentials':
                                            echo 'Invalid name or password.';
                                            break;
                                        case 'database_error':
                                            echo 'Database error. Please try again.';
                                            break;
                                        default:
                                            echo 'Login failed. Please try again.';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            <form class="contact-form" action="login.php" method="post">
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="Your Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" placeholder="Password" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-large">Login</button>
                            </form>
                        </div>
                    </div>
                    <div class="auth-section">
                        <div id="signup-form" class="auth-form">
                            <h2 class="section-title">Create Your Account</h2>
                            <p class="contact-subtitle">Sign up to start receiving real-time water outage alerts.</p>
                            <?php if (isset($_GET['error'])): ?>
                                <div style="color: red; margin-bottom: 10px; padding: 10px; background: #ffebee; border-radius: 5px;">
                                    <?php
                                    switch ($_GET['error']) {
                                        case 'missing_fields':
                                            echo 'Please fill in all fields.';
                                            break;
                                        case 'registration_failed':
                                            echo 'Registration failed. Please try again.';
                                            break;
                                        case 'database_error':
                                            echo 'Database error. Please contact support.';
                                            break;
                                        default:
                                            echo 'An error occurred. Please try again.';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            <form class="contact-form" action="submit_demo.php" method="post">
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="Your Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <input type="tel" name="phone" placeholder="Phone Number" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-large">Sign Up</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="logo-brand">
                        <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.548M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <span class="logo-text">AquaGuard</span>
                    </div>
                    <p>Smart water monitoring for a better tomorrow.</p>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Product</h4>
                        <ul>
                            <li><a href="index.html#features">Features</a></li>
                            <li><a href="index.html#how-it-works">How It Works</a></li>
                            <li><a href="index.html#benefits">Benefits</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Company</h4>
                        <ul>
                            <li><a href="#">About</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Support</h4>
                        <ul>
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">Privacy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 AquaGuard. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function showForm(formType) {
            document.querySelectorAll('.auth-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.auth-form').forEach(form => form.classList.remove('active'));
            document.querySelector(`button[onclick="showForm('${formType}')"]`).classList.add('active');
            document.getElementById(`${formType}-form`).classList.add('active');
        }
    </script>
</body>
</html>
