<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Second Chance | University Pre-Loved & Course Gear Marketplace</title>
  <meta name="description" content="Second Chance is the dedicated university marketplace for students to buy, sell, or exchange used textbooks, calculators, Arduino kits, medical coats, and campus essentials safely inside their university.">
  
  <!-- Google Fonts: Plus Jakarta Sans & Playfair Display for Chic Editorial Feel -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

  <!-- Navigation Bar Component -->
  <?php include 'includes/navbar.php'; ?>

  <!-- =========================================================================
       Hero Section
       ========================================================================= -->
  <section class="hero-section">
    <div class="container">
      <div class="hero-grid">
        <!-- Hero Left Column -->
        <div class="hero-left">
          <div class="hero-tag">
            <span class="hero-tag-icon"></span>
            <span>University Only • Safe On-Campus Meetups</span>
          </div>

          <h1 class="hero-title">
            Pass It Forward.<br>
            <em>Save Big</em> on Campus Essentials.
          </h1>

          <p class="hero-subtitle">
            The curated student marketplace to buy, sell, or exchange textbooks, Casio calculators, Arduino &amp; electronics kits, and lab gear with verified students in your university.
          </p>

          <!-- Quick Live Search & Filter Bar -->
          <div class="hero-search-box">
            <div class="hero-search-icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
            </div>
            <input type="text" id="heroSearchInput" class="hero-search-input" placeholder="Search Arduino Uno, Casio fx-991, Calculus book, Lab coat...">
            <button class="btn btn-primary btn-sm" id="heroSearchBtn" type="button">
              Search Campus
            </button>
          </div>

          <!-- Hero Trust & Impact Counters -->
          <div class="hero-stats-row">
            <div class="stat-item">
              <span class="stat-num">2,400+</span>
              <span class="stat-label">Verified Students</span>
            </div>
            <div class="stat-item">
              <span class="stat-num">1,850+</span>
              <span class="stat-label">Items Rehomed</span>
            </div>
            <div class="stat-item">
              <span class="stat-num">320k+</span>
              <span class="stat-label">EGP Saved in Tuition Gear</span>
            </div>
          </div>
        </div>

        <!-- Hero Right Column (Dynamic Campus Product Slideshow) -->
        <div class="hero-right">
          <div class="hero-slideshow-container" id="heroSlideshow">
            <!-- Top Controls & Counter -->
            <div class="slideshow-top-meta">
              <span class="slideshow-counter" id="slideCounter">
                <span style="color:var(--color-primary); font-weight:800;">1 / 6</span> • Campus Product Showcase
              </span>
              <div class="slideshow-nav-buttons">
                <button class="slideshow-arrow-btn" id="slidePrevBtn" type="button" aria-label="Previous Slide">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button class="slideshow-arrow-btn" id="slideNextBtn" type="button" aria-label="Next Slide">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
              </div>
            </div>

            <!-- Slides Track -->
            <div class="slideshow-track">
              <!-- Slide 1: Arduino Uno Kit -->
              <div class="hero-slide active" data-index="0">
                <div class="hero-card-header">
                  <span class="hero-card-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    Engineering • Hall 3
                  </span>
                  <span class="hero-card-condition">Like New</span>
                </div>
                <div class="hero-card-visual">
                  <img src="https://images.unsplash.com/photo-1553406830-ef2513450d76?auto=format&fit=crop&w=700&q=80" alt="Arduino Uno Complete Kit">
                  <div class="hero-card-ai-badge">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    AI Valued: 490 – 550 EGP
                  </div>
                </div>
                <div class="hero-card-body">
                  <h3>Arduino Uno Rev3 Starter Kit</h3>
                  <p>Complete with breadboard, ultrasonic sensor, RFID, 16x2 LCD &amp; organizer box. Passed Digital Systems course!</p>
                </div>
                <div class="hero-card-footer">
                  <div class="hero-card-price-group">
                    <span class="hero-card-price">520 EGP</span>
                    <span class="hero-card-old-price">Retail: 850 EGP (Save 39%)</span>
                  </div>
                  <button class="btn btn-primary btn-sm" onclick="openQuickView(1)">Quick View</button>
                </div>
              </div>

              <!-- Slide 2: Casio fx-991EX Calculator -->
              <div class="hero-slide" data-index="1">
                <div class="hero-card-header">
                  <span class="hero-card-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    Central Library • 2nd Fl.
                  </span>
                  <span class="hero-card-condition">Like New</span>
                </div>
                <div class="hero-card-visual">
                  <img src="https://images.unsplash.com/photo-1594980596870-8aa52a78d8cd?auto=format&fit=crop&w=700&q=80" alt="Casio Calculator">
                  <div class="hero-card-ai-badge">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    AI Valued: 450 – 500 EGP
                  </div>
                </div>
                <div class="hero-card-body">
                  <h3>Casio fx-991EX ClassWiz Calculator</h3>
                  <p>High-resolution screen, QR code matrix calculation, hard slide case included. Zero scratches on screen!</p>
                </div>
                <div class="hero-card-footer">
                  <div class="hero-card-price-group">
                    <span class="hero-card-price">480 EGP</span>
                    <span class="hero-card-old-price">Retail: 750 EGP (Save 36%)</span>
                  </div>
                  <button class="btn btn-primary btn-sm" onclick="openQuickView(2)">Quick View</button>
                </div>
              </div>

              <!-- Slide 3: Pure Cotton Lab Coat & Dissection Kit -->
              <div class="hero-slide" data-index="2">
                <div class="hero-card-header">
                  <span class="hero-card-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    Faculty of Medicine Gate
                  </span>
                  <span class="hero-card-condition">Like New</span>
                </div>
                <div class="hero-card-visual">
                  <img src="https://images.unsplash.com/photo-1582719471384-894fbb16e074?auto=format&fit=crop&w=700&q=80" alt="Medical Lab Coat">
                  <div class="hero-card-ai-badge">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    AI Valued: 320 – 360 EGP
                  </div>
                </div>
                <div class="hero-card-body">
                  <h3>Cotton Lab Coat + Dissection Kit</h3>
                  <p>Pure cotton white lab coat (Size L) freshly dry-cleaned, with a 7-piece stainless surgical dissection pouch.</p>
                </div>
                <div class="hero-card-footer">
                  <div class="hero-card-price-group">
                    <span class="hero-card-price">340 EGP</span>
                    <span class="hero-card-old-price">Retail: 580 EGP (Save 41%)</span>
                  </div>
                  <button class="btn btn-primary btn-sm" onclick="openQuickView(4)">Quick View</button>
                </div>
              </div>

              <!-- Slide 4: Rotring Drawing Set -->
              <div class="hero-slide" data-index="3">
                <div class="hero-card-header">
                  <span class="hero-card-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    Architecture Studios • Bldg D
                  </span>
                  <span class="hero-card-condition" style="background:#e3f2fd; color:#1565c0;">Good Condition</span>
                </div>
                <div class="hero-card-visual">
                  <img src="https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?auto=format&fit=crop&w=700&q=80" alt="Architecture Drafting Set">
                  <div class="hero-card-ai-badge">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    AI Valued: 460 – 520 EGP
                  </div>
                </div>
                <div class="hero-card-body">
                  <h3>Rotring Technical Pens &amp; 90cm T-Square</h3>
                  <p>3 Isograph technical pens (0.2, 0.35, 0.5mm), compass with extension bar, set squares, and acrylic T-square.</p>
                </div>
                <div class="hero-card-footer">
                  <div class="hero-card-price-group">
                    <span class="hero-card-price">490 EGP</span>
                    <span class="hero-card-old-price">Retail: 950 EGP (Save 48%)</span>
                  </div>
                  <button class="btn btn-primary btn-sm" onclick="openQuickView(5)">Quick View</button>
                </div>
              </div>

              <!-- Slide 5: Raspberry Pi 4 Model B -->
              <div class="hero-slide" data-index="4">
                <div class="hero-card-header">
                  <span class="hero-card-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    CS Faculty Lounge
                  </span>
                  <span class="hero-card-condition">Like New</span>
                </div>
                <div class="hero-card-visual">
                  <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=700&q=80" alt="Raspberry Pi 4">
                  <div class="hero-card-ai-badge">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    AI Valued: 1750 – 1950 EGP
                  </div>
                </div>
                <div class="hero-card-body">
                  <h3>Raspberry Pi 4 (4GB RAM) + Cooler Case</h3>
                  <p>Includes passive aluminum heatsink case with dual quiet fans, 5V 3A Type-C power adapter, and 32GB MicroSD.</p>
                </div>
                <div class="hero-card-footer">
                  <div class="hero-card-price-group">
                    <span class="hero-card-price">1850 EGP</span>
                    <span class="hero-card-old-price">Retail: 2600 EGP (Save 29%)</span>
                  </div>
                  <button class="btn btn-primary btn-sm" onclick="openQuickView(3)">Quick View</button>
                </div>
              </div>

              <!-- Slide 6: Organic Chemistry Molecular Kit -->
              <div class="hero-slide" data-index="5">
                <div class="hero-card-header">
                  <span class="hero-card-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    Pharmacy Lab 3
                  </span>
                  <span class="hero-card-condition">Like New</span>
                </div>
                <div class="hero-card-visual">
                  <img src="https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=700&q=80" alt="Chemistry Molecular Kit">
                  <div class="hero-card-ai-badge">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    AI Valued: 290 – 330 EGP
                  </div>
                </div>
                <div class="hero-card-body">
                  <h3>Organic Chemistry Molecular Model Kit</h3>
                  <p>240 pieces with all atom bonds, removal tool, and protective case for organic chemistry and stereochemistry.</p>
                </div>
                <div class="hero-card-footer">
                  <div class="hero-card-price-group">
                    <span class="hero-card-price">310 EGP</span>
                    <span class="hero-card-old-price">Retail: 550 EGP (Save 44%)</span>
                  </div>
                  <button class="btn btn-primary btn-sm" onclick="openQuickView(7)">Quick View</button>
                </div>
              </div>
            </div>

            <!-- Dots Pagination -->
            <div class="slideshow-dots" id="slideshowDots">
              <button class="slide-dot active" data-slide="0" aria-label="Slide 1"></button>
              <button class="slide-dot" data-slide="1" aria-label="Slide 2"></button>
              <button class="slide-dot" data-slide="2" aria-label="Slide 3"></button>
              <button class="slide-dot" data-slide="3" aria-label="Slide 4"></button>
              <button class="slide-dot" data-slide="4" aria-label="Slide 5"></button>
              <button class="slide-dot" data-slide="5" aria-label="Slide 6"></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- =========================================================================
       Academic Majors Categories Section ("Categorized by Majors")
       ========================================================================= -->
  <section class="categories-section" id="categoriesSection">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Campus Catalog</span>
        <h2 class="section-title">Explore by <span>Academic Major</span></h2>
        <p class="section-desc">
          Find textbooks, specialized lab gear, hardware kits, and tools tailored directly to your faculty curriculum and semester courses.
        </p>
      </div>

      <div class="categories-grid">
        <!-- Major 1: Engineering -->
        <div class="category-card" data-category="engineering">
          <div class="category-icon-wrapper">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3"></circle>
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
          </div>
          <h3 class="category-title">Engineering</h3>
          <span class="category-count">165 Items Listed</span>
        </div>

        <!-- Major 2: Medicine & Health -->
        <div class="category-card" data-category="medicine">
          <div class="category-icon-wrapper">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
            </svg>
          </div>
          <h3 class="category-title">Medicine &amp; Health</h3>
          <span class="category-count">124 Items Listed</span>
        </div>

        <!-- Major 3: Computer Science & AI -->
        <div class="category-card" data-category="cs">
          <div class="category-icon-wrapper">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
              <line x1="8" y1="21" x2="16" y2="21"></line>
              <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
          </div>
          <h3 class="category-title">Computer Science &amp; AI</h3>
          <span class="category-count">140 Items Listed</span>
        </div>

        <!-- Major 4: Architecture & Design -->
        <div class="category-card" data-category="architecture">
          <div class="category-icon-wrapper">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
              <polyline points="2 17 12 22 22 17"></polyline>
              <polyline points="2 12 12 17 22 12"></polyline>
            </svg>
          </div>
          <h3 class="category-title">Architecture &amp; Arts</h3>
          <span class="category-count">88 Items Listed</span>
        </div>

        <!-- Major 5: Pharmacy & Sciences -->
        <div class="category-card" data-category="pharmacy">
          <div class="category-icon-wrapper">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M10 2v7.31L4.14 19.53A2 2 0 0 0 5.86 22h12.28a2 2 0 0 0 1.72-2.47L14 9.31V2"></path>
              <line x1="8" y1="2" x2="16" y2="2"></line>
              <line x1="9" y1="15" x2="15" y2="15"></line>
            </svg>
          </div>
          <h3 class="category-title">Pharmacy &amp; Science</h3>
          <span class="category-count">92 Items Listed</span>
        </div>

        <!-- Major 6: Business & Management -->
        <div class="category-card" data-category="business">
          <div class="category-icon-wrapper">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
          </div>
          <h3 class="category-title">Business &amp; Mgmt</h3>
          <span class="category-count">78 Items Listed</span>
        </div>
      </div>
    </div>
  </section>

  <!-- =========================================================================
       Product Showcase Section (Interactive Live Grid by Major)
       ========================================================================= -->
  <section class="showcase-section" id="showcaseSection">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Campus Major Catalog</span>
        <h2 class="section-title">Discover <span>Pre-Loved Gear</span> by Major</h2>
        <p class="section-desc">
          Browse verified items listed by students in your department. Filter by major, compare against AI suggested price ranges, and arrange a safe campus meetup.
        </p>
      </div>

      <!-- Filter Pills Toolbar by Major -->
      <div class="showcase-toolbar">
        <div class="filter-pills">
          <button class="filter-pill active" data-category="all">All Majors</button>
          <button class="filter-pill" data-category="engineering">Engineering</button>
          <button class="filter-pill" data-category="medicine">Medicine &amp; Health</button>
          <button class="filter-pill" data-category="cs">Computer Science &amp; AI</button>
          <button class="filter-pill" data-category="architecture">Architecture &amp; Arts</button>
          <button class="filter-pill" data-category="pharmacy">Pharmacy &amp; Science</button>
          <button class="filter-pill" data-category="business">Business &amp; Mgmt</button>
        </div>

        <div class="sort-select-wrapper">
          <select id="sortSelect" onchange="showToast('Sorted listings')">
            <option value="featured">Sort by: Featured First</option>
            <option value="low-high">Price: Low to High</option>
            <option value="high-low">Price: High to Low</option>
            <option value="savings">Biggest Savings</option>
          </select>
        </div>
      </div>

      <!-- Products Grid (Populated dynamically via index.js) -->
      <div class="products-grid" id="productsGrid">
        <!-- Rendered via JavaScript with rich data -->
      </div>

      <div style="text-align:center; margin-top:48px;">
        <a href="products.php" class="btn btn-secondary btn-lg">
          Browse All 500+ Campus Listings
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
        </a>
      </div>
    </div>
  </section>

  <!-- =========================================================================
       AI Feature Section: Fair Price Suggestion (Standout Feature Demo)
       ========================================================================= -->
  <section class="ai-feature-section" id="aiPriceSection">
    <div class="container">
      <div class="ai-feature-grid">
        <!-- Left: AI Feature Explanation -->
        <div class="ai-feature-info">
          <div class="ai-pill">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            Standout AI Innovation
          </div>

          <h2 class="ai-title">
            Fair Price Suggestion.<br>
            <em>No More Guesswork.</em>
          </h2>

          <p class="ai-desc">
            Never wonder whether you're overpaying or underpricing. When listing or browsing, our intelligent valuation algorithm analyzes completed sales from peer students across Egyptian universities to recommend a balanced fair price range.
          </p>

          <div class="ai-bullet-list">
            <div class="ai-bullet">
              <div class="ai-bullet-icon">✓</div>
              <div class="ai-bullet-text">
                <h4>Ground-Truth Student Transactions</h4>
                <p>Calculates weighted median prices based on recent sales from Cairo Univ, GUC, AUC, and Ain Shams.</p>
              </div>
            </div>

            <div class="ai-bullet">
              <div class="ai-bullet-icon">✓</div>
              <div class="ai-bullet-text">
                <h4>Condition &amp; Accessories Aware</h4>
                <p>Accounts for cosmetic condition, original packaging, jumper wires, manuals, and included sensors.</p>
              </div>
            </div>

            <div class="ai-bullet">
              <div class="ai-bullet-icon">✓</div>
              <div class="ai-bullet-text">
                <h4>100% Seller Freedom</h4>
                <p>The AI suggests a sweet spot for a fast 48-hour sale, but the student seller always chooses the final asking price.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Interactive Live AI Calculator Widget -->
        <div class="ai-interactive-card">
          <div class="ai-card-top">
            <div class="ai-card-title">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
              Live Campus Price Estimator
            </div>
            <div class="ai-live-pulse">Campus Model Active</div>
          </div>

          <div class="ai-form-group">
            <label class="ai-form-label" for="aiItemSelect">Select Course Item / Hardware</label>
            <select id="aiItemSelect" class="ai-select">
              <option value="arduino_uno">Arduino Uno Rev3 Starter Kit + Breadboard &amp; Sensors</option>
              <option value="casio_991">Casio fx-991EX ClassWiz Scientific Calculator</option>
              <option value="ti84_calc">TI-84 Plus CE Graphing Calculator</option>
              <option value="stewart_calc">Stewart Calculus: Early Transcendentals (Metric Ed.)</option>
              <option value="medical_coat">Pure Cotton Medical Lab Coat + Dissection Set</option>
              <option value="architecture_set">Rotring Technical Pens + 90cm T-Square</option>
              <option value="raspberry_pi">Raspberry Pi 4 Model B (4GB RAM) with Case</option>
            </select>
          </div>

          <div class="ai-form-group">
            <label class="ai-form-label">Physical Condition</label>
            <div class="condition-radio-group">
              <div>
                <input type="radio" id="cond_new" name="ai_condition" value="new">
                <label for="cond_new" class="condition-pill-label">Brand New</label>
              </div>
              <div>
                <input type="radio" id="cond_likenew" name="ai_condition" value="like_new" checked>
                <label for="cond_likenew" class="condition-pill-label">Like New</label>
              </div>
              <div>
                <input type="radio" id="cond_good" name="ai_condition" value="good">
                <label for="cond_good" class="condition-pill-label">Good</label>
              </div>
              <div>
                <input type="radio" id="cond_fair" name="ai_condition" value="fair">
                <label for="cond_fair" class="condition-pill-label">Fair</label>
              </div>
            </div>
          </div>

          <!-- Product Photo Upload for Visual AI Inspection -->
          <div class="ai-form-group">
            <label class="ai-form-label">Product Photo (Visual AI Condition Scan)</label>
            <div class="ai-upload-dropzone" id="aiPhotoDropzone">
              <input type="file" id="aiPhotoInput" accept="image/*" style="display:none;">
              
              <div class="ai-upload-empty" id="aiUploadEmpty">
                <div class="ai-upload-icon">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                  </svg>
                </div>
                <div class="ai-upload-text">
                  <span>Click to insert item photo</span> or drag &amp; drop
                  <div style="font-size:0.72rem; color:var(--color-lavender); margin-top:2px;">PNG, JPG, WEBP • Visual analysis of wear &amp; accessories</div>
                </div>
              </div>

              <!-- Upload Preview State -->
              <div class="ai-upload-preview" id="aiUploadPreview" style="display:none;">
                <div class="ai-preview-img-wrap">
                  <img id="aiPreviewImg" src="" alt="Item Preview">
                </div>
                <div class="ai-preview-info">
                  <div class="ai-preview-name" id="aiPreviewName">product-photo.jpg</div>
                  <div class="ai-preview-tag">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Visual AI Scan Ready
                  </div>
                  <button type="button" class="ai-remove-photo-btn" id="aiRemovePhotoBtn">Remove photo</button>
                </div>
              </div>
            </div>
          </div>

          <div class="ai-form-group" style="display:flex; align-items:center; gap:10px;">
            <input type="checkbox" id="aiAccessories" checked style="accent-color: var(--color-rose); width:18px; height:18px;">
            <label for="aiAccessories" style="font-size:0.86rem; color:var(--color-cream); cursor:pointer;">
              Includes all original cables, boxes, or components
            </label>
          </div>

          <button type="button" class="ai-calc-btn" id="aiCalcBtn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
            Estimate Fair Student Price
          </button>

          <!-- Interactive Output Box -->
          <div class="ai-output-box" id="aiOutputBox">
            <div class="ai-output-header">
              <span style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--color-lavender);">Suggested Fair Range</span>
              <span class="ai-badge-fair">✓ Highly Recommended</span>
            </div>
            <div class="ai-result-range" id="aiResultRange">450 – 550 EGP</div>
            
            <div class="ai-bar-visual">
              <div class="ai-bar-fill" id="aiBarFill"></div>
            </div>
            <div class="ai-bar-labels">
              <span>Low (Quick Sale)</span>
              <span>Fair Market Sweetspot</span>
              <span>High (Like New)</span>
            </div>

            <div class="ai-explanation-text" id="aiExplanationText">
              <strong>Recommendation:</strong> Asking around <strong>480 EGP</strong> is estimated to sell within <strong>48 hours</strong> on campus.
              <br><span style="color: var(--color-lavender); font-size: 0.78rem;">Based on 8 verified student sales in GUC, Cairo Univ, and Ain Shams in the last 30 days.</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- =========================================================================
       How It Works Steps
       ========================================================================= -->
  <section class="how-it-works-section" id="howItWorksSection">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Simple &amp; Seamless</span>
        <h2 class="section-title">How <span>Second Chance</span> Works</h2>
        <p class="section-desc">
          Four easy steps to rehome course materials and put extra money back into your student pocket.
        </p>
      </div>

      <div class="steps-grid">
        <!-- Step 1 -->
        <div class="step-card">
          <span class="step-number">01</span>
          <div class="step-icon-wrap">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
          </div>
          <h3>Snap &amp; List in 60s</h3>
          <p>Take quick photos of your gear, choose its condition, and view the AI fair price estimate to set a competitive asking price.</p>
        </div>

        <!-- Step 2 -->
        <div class="step-card">
          <span class="step-number">02</span>
          <div class="step-icon-wrap">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
          </div>
          <h3>Chat &amp; Agree on Terms</h3>
          <p>Receive offers from fellow students and coordinate your on-campus meetup location and time between lectures.</p>
        </div>

        <!-- Step 3 -->
        <div class="step-card">
          <span class="step-number">03</span>
          <div class="step-icon-wrap">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          </div>
          <h3>Campus Meetup</h3>
          <p>Meet up safely between lectures at your faculty cafeteria or the campus library to inspect the physical condition.</p>
        </div>

        <!-- Step 4 -->
        <div class="step-card">
          <span class="step-number">04</span>
          <div class="step-icon-wrap">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
          </div>
          <h3>Inspect &amp; Rate Peer</h3>
          <p>Inspect your course gear in person, complete your exchange smoothly, and leave a star rating to build campus trust.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- =========================================================================
       Student Testimonials Section
       ========================================================================= -->
  <section class="testimonials-section">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Campus Stories</span>
        <h2 class="section-title">Loved by <span>University Students</span></h2>
        <p class="section-desc">
          See how students from Engineering, Medicine, and Design save money and make peer connections every semester.
        </p>
      </div>

      <div class="testimonials-grid">
        <!-- Testimonial 1 -->
        <div class="testimonial-card">
          <p class="testimonial-quote">
            "I listed my Casio fx-991EX calculator after finishing my Math 3 midterm. The AI suggested 450 EGP, a sophomore messaged me that same evening, and we met at the engineering library. Super smooth!"
          </p>
          <div class="student-author">
            <div class="student-avatar">M</div>
            <div class="student-info">
              <h4>Mariam Khaled</h4>
              <p>Faculty of Engineering (3rd Year)</p>
            </div>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="testimonial-card">
          <p class="testimonial-quote">
            "Buying brand-new lab coats and histology dissection kits was way too costly for a single semester. I got both on Second Chance for 340 EGP from a 4th-year med senior in pristine condition."
          </p>
          <div class="student-author">
            <div class="student-avatar">Z</div>
            <div class="student-info">
              <h4>Ziad Mostafa</h4>
              <p>Faculty of Medicine (1st Year)</p>
            </div>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="testimonial-card">
          <p class="testimonial-quote">
            "Finding my Rotring technical pens and drafting board from an architecture senior saved me over 450 EGP compared to bookstore prices. Super smooth and trusted on campus!"
          </p>
          <div class="student-author">
            <div class="student-avatar">H</div>
            <div class="student-info">
              <h4>Hana El-Sayed</h4>
              <p>Faculty of Applied Arts (2nd Year)</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- =========================================================================
       Call To Action Banner (CTA)
       ========================================================================= -->
  <section class="cta-banner-section">
    <div class="container">
      <div class="cta-box">
        <span class="top-bar-badge" style="background:rgba(201,173,167,0.25); color:var(--color-rose);">
          🎓 Start Saving Today
        </span>
        <h2>
          Have used course items sitting on your desk?<br>
          <span>Give them a second life.</span>
        </h2>
        <p>
          Turn your completed course kits, reference books, and tools into cash for next semester. It takes less than 60 seconds to list.
        </p>
        <div class="cta-buttons">
          <a href="product-form.php" class="btn btn-accent btn-lg">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Post a Listing Now
          </a>
          <a href="products.php" class="btn btn-secondary btn-lg" style="background:rgba(255,255,255,0.1); color:#fff; border-color:rgba(255,255,255,0.3);">
            Browse Campus Gear
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Modular Footer Component with Chatbot & Modals -->
  <?php include 'includes/footer.php'; ?>

  <!-- Main JavaScript File -->
  <script src="assets/index.js"></script>
</body>
</html>

