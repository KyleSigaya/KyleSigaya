<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}
require_once 'db.php';

// Fetch user info
$user_id = $_SESSION['user_id'];
$user_result = $conn->query("SELECT name, phone FROM aquaguard WHERE id = $user_id");
$user = $user_result ? $user_result->fetch_assoc() : null;

// Fetch alerts (example, adjust table/fields as needed)
$alerts_result = $conn->query("SELECT * FROM alerts WHERE user_id = $user_id ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - AquaGuard</title>
    <link rel="stylesheet" href="style.css">
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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php" class="btn-nav">Logout</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">Welcome back, <?php echo htmlspecialchars($user['name'] ?? 'User'); ?>!</h1>
                        <p class="hero-subtitle">Monitor your water usage and stay informed about outages.</p>
                        <div class="hero-buttons">
                            <a href="#alerts" class="btn btn-primary">View Alerts</a>
                            <a href="#analytics" class="btn btn-secondary">Analytics</a>
                        </div>
                    </div>
                    <div class="hero-visual" aria-hidden="true">
                        <div class="water-wave">
                            <svg viewBox="0 0 1200 800" preserveAspectRatio="xMidYMid slice">
                                <defs>
                                    <linearGradient id="waterGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" style="stop-color:#0066cc;stop-opacity:0.8"/>
                                        <stop offset="100%" style="stop-color:#004499;stop-opacity:1"/>
                                    </linearGradient>
                                </defs>
                                <path class="wave" d="M0,400 Q300,350 600,400 T1200,400 L1200,800 L0,800 Z" fill="url(#waterGradient)"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="alerts" class="features">
            <div class="container">
                <h2 class="section-title">Your Recent Alerts</h2>
                <div class="features-grid">
                    <?php if ($alerts_result && $alerts_result->num_rows > 0) {
                        while ($row = $alerts_result->fetch_assoc()) { ?>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </div>
                                <h3><?php echo htmlspecialchars($row['type']); ?></h3>
                                <p>Date: <?php echo htmlspecialchars($row['date']); ?></p>
                                <p>Status: <?php echo htmlspecialchars($row['status']); ?></p>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3>No Alerts</h3>
                            <p>You have no recent alerts. Your water system is running smoothly!</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>

        <section id="analytics" class="benefits">
            <div class="container">
                <div class="benefits-content">
                    <div class="benefits-text">
                        <h2 class="section-title">Your Profile Information</h2>
                        <ul class="benefits-list">
                            <li><span><strong>Name:</strong> <?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></span></li>
                            <li><span><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></span></li>
                            <li><span><strong>Account Status:</strong> Active</span></li>
                        </ul>
                    </div>
                    <div class="benefits-visual">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo $alerts_result ? $alerts_result->num_rows : 0; ?></div>
                            <div class="stat-label">Total Alerts</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">99.9%</div>
                            <div class="stat-label">System Uptime</div>
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
                        <h4>Dashboard</h4>
                        <ul>
                            <li><a href="#alerts">Alerts</a></li>
                            <li><a href="#analytics">Analytics</a></li>
                            <li><a href="profile.php">Profile</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Account</h4>
                        <ul>
                            <li><a href="logout.php">Logout</a></li>
                            <li><a href="#">Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 AquaGuard. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
