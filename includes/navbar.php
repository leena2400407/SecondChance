
<?php
require_once __DIR__ . '/auth.php';

$isLoggedIn = isLoggedIn();

$rawName = $isLoggedIn
    ? ($_SESSION['fname'] ?? 'Student')
    : '';

$userName = escape($rawName);

$userFirstInitial = $isLoggedIn
    ? escape(mb_strtoupper(
        mb_substr($rawName, 0, 1, 'UTF-8'),
        'UTF-8'
    ))
    : '';
?>

<!-- Announcement Bar -->
<div class="top-bar">
    <div class="container top-bar-content">
        <div class="top-bar-message">
            <span class="top-bar-badge">🎓 Campus Verified</span>
            <span>
                Student-to-Student Pre-Loved Marketplace
                • Safe Campus Meetups • No Listing Fees
            </span>
        </div>

        <div class="top-bar-links">
            <a href="admin.php">🛡️ Moderator Portal</a>
        </div>
    </div>
</div>

<!-- Main Navigation -->
<header class="navbar-header">
    <div class="navbar-container">

        <!-- Logo -->
        <a href="index.php"
           class="brand-logo"
           aria-label="Second Chance Home">

            <div class="logo-symbol">
                <svg width="26" height="26"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    <path d="M12 6v6"></path>
                    <path d="M9 9h6"></path>
                </svg>
            </div>

            <div class="logo-text-group">
                <span class="brand-title">
                    Second<span>Chance</span>
                </span>
                <span class="brand-tagline">Campus Pre-Loved Hub</span>
            </div>
        </a>

        <!-- Navigation Links -->
        <nav class="main-navigation"
             id="mainNavigation"
             aria-label="Main navigation">

            <ul class="nav-links">
                <li>
                    <a href="index.php" class="nav-link">Home</a>
                </li>

                <li>
                    <a href="index.php#categoriesSection" class="nav-link">
                        Majors &amp; Faculties
                    </a>
                </li>

                <?php if ($isLoggedIn): ?>
                    <li>
                        <a href="messages.php" class="nav-link">
                            Messages
                        </a>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="index.php#aiPriceSection" class="nav-link">
                        AI Price Check
                        <span class="nav-badge-pill">AI</span>
                    </a>
                </li>

                <li>
                    <a href="index.php#howItWorksSection" class="nav-link">
                        How It Works
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Right Side Actions -->
        <div class="nav-actions">

            <!-- Search -->
            <a href="index.php#showcaseSection"
               class="search-mini-btn"
               aria-label="Search items"
               title="Search items on campus">

                <svg width="19" height="19"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </a>

            <!-- Sell Gear -->
            <a href="product-form.php"
               class="nav-sell-btn"
               title="List your item for sale">

                <svg width="17" height="17"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.4"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>

                <span>Sell Gear</span>
            </a>

            <!-- Logged-in User -->
            <?php if ($isLoggedIn): ?>

                <div class="user-menu-wrapper">

                    <button
                        class="user-profile-btn"
                        id="userProfileBtn"
                        type="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-controls="userDropdown">

                        <span class="user-avatar-mini">
                            <?= $userFirstInitial ?>
                        </span>

                        <span class="user-name-text">
                            <?= $userName ?>
                        </span>

                        <svg width="14" height="14"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M6 9l6 6 6-6"></path>
                        </svg>
                    </button>

                    <div class="user-dropdown" id="userDropdown" hidden>

                        <a href="profile.php">My Profile</a>

                        <a href="messages.php">Messages</a>

                        <a href="seller.php">My Seller Dashboard</a>

                        <a href="product-form.php">Post New Listing</a>

                        <div class="user-dropdown-divider"></div>

                        <a href="logout.php" class="nav-logout-link">
                            Sign Out
                        </a>

                    </div>
                </div>

            <?php else: ?>

                <!-- Logged-out User -->
                <a href="login.php" class="nav-login-btn">
                    Login
                </a>

                <a href="signup.php" class="nav-join-btn">
                    Join Now
                </a>

            <?php endif; ?>

            <!-- Mobile Menu Button -->
            <button
                class="mobile-menu-toggle"
                id="mobileMenuToggle"
                type="button"
                aria-label="Toggle navigation"
                aria-expanded="false"
                aria-controls="mainNavigation">

                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>
    </div>
</header>

<!-- Navbar JavaScript -->
<script>
(function () {
    const navbar = document.querySelector('.navbar-header');

    if (!navbar || navbar.dataset.initialized === 'true') {
        return;
    }

    navbar.dataset.initialized = 'true';

    const menuToggle = navbar.querySelector('#mobileMenuToggle');
    const navigation = navbar.querySelector('#mainNavigation');
    const profileButton = navbar.querySelector('#userProfileBtn');
    const userDropdown = navbar.querySelector('#userDropdown');

    // Mobile navigation
    if (menuToggle && navigation) {
        menuToggle.addEventListener('click', function () {
            const isOpen = navigation.classList.toggle('is-open');

            menuToggle.setAttribute('aria-expanded', String(isOpen));
            menuToggle.classList.toggle('is-active', isOpen);
        });

        navigation.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                navigation.classList.remove('is-open');
                menuToggle.classList.remove('is-active');
                menuToggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // User dropdown
    if (profileButton && userDropdown) {
        profileButton.addEventListener('click', function (event) {
            event.stopPropagation();

            const isOpen = !userDropdown.classList.contains('is-open');

            userDropdown.classList.toggle('is-open', isOpen);
            userDropdown.hidden = !isOpen;

            profileButton.setAttribute('aria-expanded', String(isOpen));
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.user-menu-wrapper')) {
                userDropdown.classList.remove('is-open');
                userDropdown.hidden = true;
                profileButton.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                userDropdown.classList.remove('is-open');
                userDropdown.hidden = true;
                profileButton.setAttribute('aria-expanded', 'false');
                profileButton.focus();
            }
        });
    }
})();
</script>
<?php sessionTimerMarkup(); ?>
