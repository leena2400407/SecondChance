<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['email']);
$userName = $isLoggedIn ? (isset($_SESSION['fname']) ? htmlspecialchars($_SESSION['fname']) : 'Student') : '';
$userFirstInitial = $isLoggedIn ? strtoupper(substr($userName, 0, 1)) : '';
?>
<!-- Top Announcement Bar -->
<div class="top-bar">
  <div class="container top-bar-content">
    <div style="display:flex; align-items:center; gap:8px;">
      <span class="top-bar-badge">🎓 Campus Verified</span>
      <span>100% Student-to-Student Pre-Loved Marketplace • Safe Campus Meetups • No Listing Fees</span>
    </div>
    <div class="top-bar-links">
      <a href="admin.php">🛡️ Moderator Portal</a>
    </div>
  </div>
</div>

<!-- Main Sticky Navigation Bar -->
<header class="navbar-header">
  <div class="navbar-container">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-logo" aria-label="Second Chance Home">
      <div class="logo-symbol">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
          <path d="M12 6v6"></path>
          <path d="M9 9h6"></path>
        </svg>
      </div>
      <div class="logo-text-group">
        <span class="brand-title">Second<span>Chance</span></span>
        <span class="brand-tagline">Campus Pre-Loved Hub</span>
      </div>
    </a>

    <!-- Navigation Links -->
    <nav>
      <ul class="nav-links">
        <li><a href="index.php" class="nav-link active">Home</a></li>
        <li><a href="#categoriesSection" class="nav-link">Majors &amp; Faculties</a></li>
        <li><a href="products.php" class="nav-link">All Products</a></li>
        <li>
          <a href="#aiPriceSection" class="nav-link">
            AI Price Check <span class="nav-badge-pill">AI</span>
          </a>
        </li>
        <li><a href="#howItWorksSection" class="nav-link">How It Works</a></li>
      </ul>
    </nav>

    <!-- Navigation Right Action Buttons -->
    <div class="nav-actions">
      <!-- Search Trigger or Quick Jump -->
      <a href="#showcaseSection" class="search-mini-btn" title="Search items on campus">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
      </a>

      <!-- Sell an Item CTA Button (leads to Mohab's product-form / seller page) -->
      <a href="product-form.php" class="btn btn-primary btn-sm" title="List your course book, calculator, or kit">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Sell Gear</span>
      </a>

      <!-- Authentication / Session Hub conforming to uni rubric -->
      <?php if ($isLoggedIn): ?>
        <div class="user-menu-wrapper">
          <button class="user-profile-btn" id="userProfileBtn" type="button" aria-haspopup="true">
            <span class="user-avatar-mini"><?= $userFirstInitial ?></span>
            <span class="user-name-text"><?= $userName ?></span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"></path></svg>
          </button>
          <div class="user-dropdown" id="userDropdown">
            <a href="seller.php">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
              My Seller Dashboard
            </a>
            <a href="product-form.php">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
              Post New Listing
            </a>
            <a href="admin.php">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
              Moderator / Admin
            </a>
            <div class="user-dropdown-divider"></div>
            <a href="logout.php" style="color:#d32f2f;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
              Sign Out
            </a>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn btn-secondary btn-sm" title="Log into your student account">Login</a>
        <a href="signup.php" class="btn btn-accent btn-sm" title="Create student account">Join Now</a>
      <?php endif; ?>

      <!-- Mobile Hamburger Button -->
      <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle navigation">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </div>
</header>
