<!-- Site Footer -->
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <!-- Col 1: About Second Chance -->
      <div class="footer-col-about">
        <div class="brand-logo" style="text-decoration:none;">
          <div class="logo-symbol">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
              <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
              <path d="M12 6v6"></path>
              <path d="M9 9h6"></path>
            </svg>
          </div>
          <span class="footer-logo-title">Second<span>Chance</span></span>
        </div>
        <p class="footer-about-text">
          The curated student-to-student marketplace for textbooks, calculators, Arduino kits, electronic components, and medical lab coats. Save money, reduce waste, and connect with peers inside your university.
        </p>
        <div style="display:flex; gap:10px; margin-top:8px;">
          <span class="top-bar-badge" style="background:rgba(201,173,167,0.2); color:var(--color-rose);">🔒 University Verified Only</span>
        </div>
      </div>

      <!-- Col 2: Marketplace Links -->
      <div class="footer-col">
        <h4>Marketplace</h4>
        <ul class="footer-links">
          <li><a href="products.php">All Course Items</a></li>
          <li><a href="#categoriesSection">Academic Majors</a></li>
          <li><a href="product-form.php">Post a Listing</a></li>
          <li><a href="#aiPriceSection">AI Fair Price Tool</a></li>
        </ul>
      </div>

      <!-- Col 3: Student Hub -->
      <div class="footer-col">
        <h4>Student Hub</h4>
        <ul class="footer-links">
          <li><a href="login.php">Student Login</a></li>
          <li><a href="signup.php">Create Account</a></li>
          <li><a href="seller.php">Seller Portal</a></li>
          <li><a href="admin.php">Admin & Moderation</a></li>
          <li><a href="#howItWorksSection">Safe Campus Meetups</a></li>
        </ul>
      </div>

      <!-- Col 4: Campus Safety & Trust -->
      <div class="footer-col">
        <h4>Campus Trust</h4>
        <p style="font-size:0.86rem; color:var(--color-lavender); line-height:1.55; margin-bottom:12px;">
          All listings and transactions are conducted directly between verified university students on campus premises in safe, public student areas.
        </p>
        <div style="font-size:0.8rem; color:var(--color-rose); font-weight:600;">
          📍 Top Meetup Spots: Central Library • Student Lounge • Main Hall Cafeteria
        </div>
      </div>
    </div>

    <!-- Footer Bottom Row -->
    <div class="footer-bottom">
      <div>
        &copy; <?= date('Y') ?> <strong>Second Chance Marketplace</strong>. All rights reserved.
      </div>
      <div style="display:flex; gap:20px; align-items:center;">
        <a href="#" style="color:var(--color-lavender); font-size:0.82rem; text-decoration:none;">Privacy Policy</a>
        <a href="#" style="color:var(--color-lavender); font-size:0.82rem; text-decoration:none;">Terms of Use</a>
        <span style="color:var(--color-lavender); font-size:0.82rem;">University Verified Platform</span>
      </div>
    </div>
  </div>
</footer>

<!-- Interactive Quick View Modal (Pure CSS/JS) -->
<div class="modal-overlay" id="quickViewModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
  <div class="modal-dialog">
    <button class="modal-close-btn" id="modalCloseBtn" aria-label="Close modal">&times;</button>
    <div class="modal-grid">
      <div class="modal-image-col">
        <img id="modalImage" src="" alt="Product Preview">
      </div>
      <div class="modal-details-col">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <span class="product-category-tag" id="modalCategory">Textbooks</span>
          <span id="modalCondition" class="badge">Like New</span>
        </div>

        <h2 id="modalTitle" style="font-size:1.35rem; font-weight:800; color:var(--color-primary); line-height:1.3;">Product Title</h2>

        <div style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:var(--color-secondary);">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <span id="modalLocation">Campus Library</span> • <span id="modalFaculty" style="font-weight:600;">Faculty of Engineering</span>
        </div>

        <div class="product-ai-price-tag" style="margin:4px 0;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          AI Fair Market Estimate: <strong id="modalAiPrice" style="margin-left:4px;">450 – 520 EGP</strong>
        </div>

        <div style="display:flex; align-items:baseline; gap:10px; margin: 6px 0;">
          <span id="modalPrice" class="current-price" style="font-size:1.7rem;">450 EGP</span>
          <span id="modalOriginalPrice" class="original-price">800 EGP</span>
          <span id="modalDiscount" class="discount-badge">Save 44%</span>
        </div>

        <p id="modalDescription" style="font-size:0.88rem; color:var(--color-secondary); line-height:1.55; background:var(--color-cream-soft); padding:12px; border-radius:8px; border:1px solid rgba(154,140,152,0.2);">
          Product description goes here.
        </p>

        <!-- Seller Info & Status -->
        <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-top:1px solid rgba(74,78,105,0.1);">
          <div style="display:flex; align-items:center; gap:8px;">
            <div style="width:32px; height:32px; border-radius:50%; background:var(--color-primary); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.85rem;">🎓</div>
            <div>
              <div id="modalSellerName" style="font-size:0.85rem; font-weight:700; color:var(--color-primary);">Student Seller</div>
              <div id="modalSellerRating" style="font-size:0.75rem; color:#f57c00;">★ 4.9 Verified Peer</div>
            </div>
          </div>
          <span class="badge" style="background:#e8f5e9; color:#2e7d32; font-size:0.75rem;">Verified Student Listing</span>
        </div>

        <!-- Quick Offer Form -->
        <form onsubmit="submitQuickOffer(event)" style="display:flex; gap:10px; margin-top:8px;">
          <input type="number" id="quickOfferAmount" placeholder="Your offer in EGP..." class="ai-input" style="flex:1; padding:10px 14px; background:var(--color-cream-soft); color:var(--color-primary); border:1px solid rgba(154,140,152,0.3);">
          <button type="submit" class="btn btn-primary btn-sm" style="padding:10px 20px;">Send Offer</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Floating Chatbot Assistant Widget ("chat_bot") -->
<div class="chatbot-launcher">
  <button class="chatbot-btn" id="chatbotToggle" aria-label="Open Second Chance Campus AI Chatbot" title="Ask Second Chance AI Assistant">
    <span class="chatbot-pulse-dot"></span>
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
  </button>
</div>

<div class="chatbot-window" id="chatbotWindow" role="region" aria-label="Campus Chatbot Assistant">
  <div class="chatbot-header">
    <div class="chatbot-header-info">
      <div class="chatbot-header-avatar">SC</div>
      <div>
        <div class="chatbot-header-name">Second Chance AI</div>
        <div class="chatbot-header-status">● Active Campus Guide</div>
      </div>
    </div>
    <button id="chatbotClose" style="color:var(--color-cream); font-size:1.4rem; padding:4px;" aria-label="Close Chat">&times;</button>
  </div>

  <div class="chatbot-body" id="chatbotBody">
    <div class="chat-bubble chat-bubble-bot">
      👋 Hey there! I'm your Second Chance campus assistant. How can I help you today?
    </div>
    <div class="chatbot-chips">
      <button class="chatbot-chip" onclick="handleChatbotChip('pickup')">📍 Safe Meetups</button>
      <button class="chatbot-chip" onclick="handleChatbotChip('price')">✨ AI Fair Price</button>
      <button class="chatbot-chip" onclick="handleChatbotChip('majors')">🎓 Academic Majors</button>
      <button class="chatbot-chip" onclick="handleChatbotChip('how')">🔍 How to Buy</button>
    </div>
  </div>

  <div class="chatbot-footer">
    <input type="text" id="chatbotInput" class="chatbot-input" placeholder="Ask about items, meetups, fair pricing...">
    <button id="chatbotSend" class="chatbot-send-btn" aria-label="Send Message">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
    </button>
  </div>
</div>

