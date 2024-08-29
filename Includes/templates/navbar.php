<?php
session_start();
?>

<!-- START NAVBAR SECTION -->

<header id="header" class="header-section">
    <div class="container">
        <nav class="navbar">
            <a href="index.php" class="navbar-brand">
                <img src="Design/images/restaurant-logo.png" alt="Restaurant Logo" style="width: 150px; margin-top: 28px;">
            </a>
            <div class="d-flex menu-wrap align-items-center">
                <div class="mainmenu" id="mainmenu">
                    <ul class="nav">
                        <li><a href="index.php#home">HOME</a></li>
                        <li><a href="index.php#menus">MENUS</a></li>
                        <li><a href="index.php#gallery">GALLERY</a></li>
                        <li><a href="index.php#about">ABOUT</a></li>
                        <li><a href="index.php#contact">CONTACT</a></li>
                    </ul>
                </div>
                <div class="header-btns d-flex align-items-center">
                <div class="header-btn" style="margin-left:10px">
                        <a href="table-reservation.php" target="_blank" class="menu-btn">Reserve Table</a>
                    </div>
                    
                    <div class="user-info ml-3">
                        <?php if (isset($_SESSION['customer_email'])): ?>
                            <span class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['customer_email']); ?></span>
                            <a href="logout-user.php" class="btn logout-btn">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="btn login-btn">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<div class="header-height" style="height: 120px;"></div>

<!-- END NAVBAR SECTION -->
<style>
    /* Navbar container */



/* User info section */
.user-info {
    display: flex;
    align-items: center;
}

.welcome-message {
    font-size: 16px;
    color: #333;
    margin-right: 15px;
}

.btn {
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 4px;
    font-weight: 500;
}

.login-btn {
    background-color: #28a745;
    color: #fff;
}

.login-btn:hover {
    background-color: #218838;
}

.logout-btn {
    background-color: #dc3545;
    color: #fff;
}

.logout-btn:hover {
    background-color: #c82333;
}
.welcome-message{color: white;}

/* Adjustments for responsive design */
@media (max-width: 768px) {
    .header-btns {
        flex-direction: column;
        align-items: flex-start;
    }

    .menu-btn {
        margin-bottom: 10px;
    }
}

</style>