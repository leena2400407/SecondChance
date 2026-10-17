/**
 * Second Chance - Campus Pre-Loved Marketplace
 * Interactive Frontend JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
  // --------------------------------------------------------------------------
  // Sample Product Dataset for Campus Marketplace Demo
  // --------------------------------------------------------------------------
  const products = [
    {
      id: 1,
      title: "Arduino Uno Rev3 Complete Starter Kit + Breadboard & 45 Sensors",
      major: "engineering",
      majorName: "Engineering",
      faculty: "Faculty of Engineering",
      condition: "Like New",
      conditionClass: "badge-condition-like-new",
      price: 520,
      originalPrice: 850,
      discount: "39% OFF",
      aiSuggested: "490 – 550 EGP",
      exchange: true,
      instapay: true,
      deposit: true,
      sellerName: "Youssef N.",
      sellerAvatar: "Y",
      sellerRating: "4.9 (14 reviews)",
      location: "Credit Hours Bldg, Gate 3",
      image: "https://images.unsplash.com/photo-1553406830-ef2513450d76?auto=format&fit=crop&w=600&q=80",
      description: "Only used for one semester of Digital Systems course (CSE 211). Includes all original jumper wires, ultrasonic sensor, RFID, 16x2 LCD display, servo, and plastic organizer box. Fully working and clean!"
    },
    {
      id: 2,
      title: "Casio fx-991EX ClassWiz Advanced Scientific Calculator",
      major: "engineering",
      majorName: "Engineering",
      faculty: "Engineering & Applied Math",
      condition: "Like New",
      conditionClass: "badge-condition-like-new",
      price: 480,
      originalPrice: 750,
      discount: "36% OFF",
      aiSuggested: "450 – 500 EGP",
      exchange: false,
      instapay: true,
      deposit: true,
      sellerName: "Nouran E.",
      sellerAvatar: "N",
      sellerRating: "5.0 (8 reviews)",
      location: "Main Central Library, 2nd Fl.",
      image: "https://images.unsplash.com/photo-1594980596870-8aa52a78d8cd?auto=format&fit=crop&w=600&q=80",
      description: "Authentic Casio fx-991EX with QR code functionality, matrix calculation, and high-resolution screen. Hard protective cover included. Zero scratches on screen, fresh solar battery."
    },
    {
      id: 3,
      title: "Raspberry Pi 4 Model B (4GB RAM) with Aluminum Cooler Case",
      major: "cs",
      majorName: "Computer Science & AI",
      faculty: "Faculty of Computers & AI",
      condition: "Like New",
      conditionClass: "badge-condition-like-new",
      price: 1850,
      originalPrice: 2600,
      discount: "29% OFF",
      aiSuggested: "1750 – 1950 EGP",
      exchange: false,
      instapay: true,
      deposit: true,
      sellerName: "Karim Z.",
      sellerAvatar: "K",
      sellerRating: "4.9 (19 reviews)",
      location: "CS Faculty Lounge",
      image: "https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80",
      description: "Used only for a capstone computer vision graduation project. Includes 4GB board, passive aluminum heatsink case with dual quiet fans, 5V 3A Type-C power adapter, and 32GB MicroSD."
    },
    {
      id: 4,
      title: "Pure Cotton Medical Lab Coat (Size L) + Stainless Dissection Kit",
      major: "medicine",
      majorName: "Medicine & Health",
      faculty: "Faculty of Medicine",
      condition: "Like New",
      conditionClass: "badge-condition-like-new",
      price: 340,
      originalPrice: 580,
      discount: "41% OFF",
      aiSuggested: "320 – 360 EGP",
      exchange: false,
      instapay: true,
      deposit: true,
      sellerName: "Salma T.",
      sellerAvatar: "S",
      sellerRating: "4.9 (11 reviews)",
      location: "Kasr Al Ainy / Med Campus Gate",
      image: "https://images.unsplash.com/photo-1582719471384-894fbb16e074?auto=format&fit=crop&w=600&q=80",
      description: "100% thick white cotton lab coat, freshly dry cleaned. Comes with a 7-piece surgical steel dissection set in a zippered leather pouch, used once for histology lab."
    },
    {
      id: 5,
      title: "Rotring Professional Technical Drawing Set + 90cm Acrylic T-Square",
      major: "architecture",
      majorName: "Architecture & Arts",
      faculty: "Applied Arts & Architecture",
      condition: "Good Condition",
      conditionClass: "badge-condition-good",
      price: 490,
      originalPrice: 950,
      discount: "48% OFF",
      aiSuggested: "460 – 520 EGP",
      exchange: true,
      instapay: true,
      deposit: true,
      sellerName: "Farida M.",
      sellerAvatar: "F",
      sellerRating: "5.0 (9 reviews)",
      location: "Design Studios / Bldg D",
      image: "https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?auto=format&fit=crop&w=600&q=80",
      description: "Complete drafting toolkit: 3 Rotring Isograph technical pens (0.2, 0.35, 0.5mm), compass with extension bar, set squares, and transparent 90cm acrylic T-square in carrying tube."
    },
    {
      id: 6,
      title: "Guyton & Hall Textbook of Medical Physiology (14th Edition)",
      major: "medicine",
      majorName: "Medicine & Health",
      faculty: "Faculty of Medicine",
      condition: "Like New",
      conditionClass: "badge-condition-like-new",
      price: 420,
      originalPrice: 900,
      discount: "53% OFF",
      aiSuggested: "400 – 460 EGP",
      exchange: true,
      instapay: true,
      deposit: true,
      sellerName: "Ahmed S.",
      sellerAvatar: "A",
      sellerRating: "4.7 (5 reviews)",
      location: "Med Campus Cafeteria",
      image: "https://images.unsplash.com/photo-1532012164546-f432f2e3777f?auto=format&fit=crop&w=600&q=80",
      description: "Hardcover, glossy full-color edition. Kept in pristine protective sleeve. No loose pages or coffee stains. Great reference for preclinical years."
    },
    {
      id: 7,
      title: "Organic Chemistry Molecular Model Kit (240 Pcs) + Safety Goggles",
      major: "pharmacy",
      majorName: "Pharmacy & Science",
      faculty: "Faculty of Pharmacy",
      condition: "Like New",
      conditionClass: "badge-condition-like-new",
      price: 310,
      originalPrice: 550,
      discount: "44% OFF",
      aiSuggested: "290 – 330 EGP",
      exchange: true,
      instapay: true,
      deposit: false,
      sellerName: "Habiba K.",
      sellerAvatar: "H",
      sellerRating: "4.8 (8 reviews)",
      location: "Pharmacy Lab 3",
      image: "https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=600&q=80",
      description: "Essential for 1st and 2nd year organic chemistry and stereochemistry courses. All atom links and orbitals intact with removal tool and heavy-duty case."
    },
    {
      id: 8,
      title: "Texas Instruments BA II Plus Financial Calculator + CFA Guides",
      major: "business",
      majorName: "Business & Mgmt",
      faculty: "Business & Management",
      condition: "Like New",
      conditionClass: "badge-condition-like-new",
      price: 430,
      originalPrice: 750,
      discount: "43% OFF",
      aiSuggested: "400 – 460 EGP",
      exchange: false,
      instapay: true,
      deposit: true,
      sellerName: "Seif A.",
      sellerAvatar: "S",
      sellerRating: "5.0 (6 reviews)",
      location: "Business Hall 2",
      image: "https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=600&q=80",
      description: "Required for Corporate Finance, Accounting, and Investment courses. Clean keypad, slides smooth in its protective hard slide case. Includes printed cheat sheets."
    }
  ];

  // --------------------------------------------------------------------------
  // Render Products in Grid
  // --------------------------------------------------------------------------
  const productsGrid = document.getElementById('productsGrid');
  let currentFilter = 'all';
  let currentSearchQuery = '';

  function renderProducts() {
    if (!productsGrid) return;

    const filtered = products.filter(item => {
      const matchesCategory = (currentFilter === 'all') || (item.major === currentFilter);
      const matchesQuery = currentSearchQuery === '' ||
        item.title.toLowerCase().includes(currentSearchQuery) ||
        item.majorName.toLowerCase().includes(currentSearchQuery) ||
        item.faculty.toLowerCase().includes(currentSearchQuery);
      return matchesCategory && matchesQuery;
    });

    if (filtered.length === 0) {
      productsGrid.innerHTML = `
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; border: 1px dashed rgba(154, 140, 152, 0.4);">
          <div style="font-size: 2.5rem; margin-bottom: 12px; color: var(--color-lavender);">📦</div>
          <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--color-primary); margin-bottom: 6px;">No campus listings found</h3>
          <p style="font-size: 0.9rem; color: var(--color-secondary); max-width: 400px; margin: 0 auto 18px;">
            We couldn't find items matching your search or category filter. Try clearing filters or list one yourself!
          </p>
          <button class="btn btn-primary btn-sm" onclick="resetFilters()">Reset All Filters</button>
        </div>
      `;
      return;
    }

    productsGrid.innerHTML = filtered.map(item => `
      <div class="product-card" data-id="${item.id}">
        <div class="product-image-container">
          <img src="${item.image}" alt="${item.title}" loading="lazy">
          
          <div class="product-badges">
            <span class="badge ${item.conditionClass}">${item.condition}</span>
          </div>

          <button class="product-wishlist-btn" title="Save to Wishlist" onclick="toggleWishlist(${item.id}, event)">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
          </button>

          <button class="product-quick-view-btn" onclick="openQuickView(${item.id})">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            Quick View
          </button>
        </div>

        <div class="product-content">
          <div class="product-meta-row">
            <span class="product-category-tag">${item.majorName}</span>
            <span class="product-faculty-tag">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
              ${item.location.split(',')[0]}
            </span>
          </div>

          <h3 class="product-title" title="${item.title}">${item.title}</h3>

          <div class="product-ai-price-tag">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            AI Fair Price: ${item.aiSuggested}
          </div>

          <div class="product-pricing-row">
            <span class="current-price">${item.price} EGP</span>
            <span class="original-price">${item.originalPrice} EGP</span>
            <span class="discount-badge">${item.discount}</span>
          </div>
        </div>

        <div class="product-card-footer">
          <div class="seller-info">
            <div class="seller-avatar">${item.sellerAvatar}</div>
            <div>
              <div class="seller-name">${item.sellerName}</div>
              <div class="seller-rating">★ 4.9</div>
            </div>
          </div>
          <span style="font-size:0.75rem; color:var(--color-secondary); font-weight:600;">📍 Campus Meetup</span>
        </div>
      </div>
    `).join('');
  }

  // Initial render
  renderProducts();

  // Expose resetFilters globally
  window.resetFilters = function () {
    currentFilter = 'all';
    currentSearchQuery = '';
    const searchInput = document.getElementById('heroSearchInput');
    if (searchInput) searchInput.value = '';
    
    document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.category-card').forEach(c => c.classList.remove('active'));
    const defaultPill = document.querySelector('.filter-pill[data-category="all"]');
    if (defaultPill) defaultPill.classList.add('active');

    renderProducts();
  };

  // --------------------------------------------------------------------------
  // Category Cards & Filter Pills Click Handler
  // --------------------------------------------------------------------------
  const categoryCards = document.querySelectorAll('.category-card');
  const filterPills = document.querySelectorAll('.filter-pill');

  categoryCards.forEach(card => {
    card.addEventListener('click', function () {
      const cat = this.getAttribute('data-category');
      currentFilter = cat;
      categoryCards.forEach(c => c.classList.remove('active'));
      this.classList.add('active');

      filterPills.forEach(p => {
        p.classList.toggle('active', p.getAttribute('data-category') === cat);
      });

      renderProducts();
      scrollToSection('showcaseSection');
    });
  });

  filterPills.forEach(pill => {
    pill.addEventListener('click', function () {
      const cat = this.getAttribute('data-category');
      currentFilter = cat;
      filterPills.forEach(p => p.classList.remove('active'));
      this.classList.add('active');

      categoryCards.forEach(c => {
        c.classList.toggle('active', c.getAttribute('data-category') === cat);
      });

      renderProducts();
    });
  });

  // --------------------------------------------------------------------------
  // Live Search Functionality
  // --------------------------------------------------------------------------
  const heroSearchInput = document.getElementById('heroSearchInput');
  const heroSearchBtn = document.getElementById('heroSearchBtn');

  if (heroSearchInput) {
    heroSearchInput.addEventListener('input', function (e) {
      currentSearchQuery = e.target.value.toLowerCase().trim();
      renderProducts();
    });
  }

  if (heroSearchBtn) {
    heroSearchBtn.addEventListener('click', function () {
      if (heroSearchInput) {
        currentSearchQuery = heroSearchInput.value.toLowerCase().trim();
        renderProducts();
        scrollToSection('showcaseSection');
      }
    });
  }

  function scrollToSection(id) {
    const el = document.getElementById(id);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  // --------------------------------------------------------------------------
  // AI Fair Price Suggestion Demo Widget
  // Standout Feature from Project Prompt
  // --------------------------------------------------------------------------
  const aiItemSelect = document.getElementById('aiItemSelect');
  const aiCalcBtn = document.getElementById('aiCalcBtn');
  const aiOutputBox = document.getElementById('aiOutputBox');
  const aiResultRange = document.getElementById('aiResultRange');
  const aiExplanationText = document.getElementById('aiExplanationText');
  const aiBarFill = document.getElementById('aiBarFill');

  // Benchmark Price Data for College Marketplace
  const priceEstimates = {
    arduino_uno: {
      name: "Arduino Uno Rev3 Starter Kit",
      base: 450,
      min: 400,
      max: 560,
      salesCount: 8,
      unis: "GUC, Cairo University, Ain Shams"
    },
    casio_991: {
      name: "Casio fx-991EX Scientific Calculator",
      base: 440,
      min: 410,
      max: 490,
      salesCount: 15,
      unis: "AUC, Cairo University, Helwan"
    },
    ti84_calc: {
      name: "TI-84 Plus Graphing Calculator",
      base: 1950,
      min: 1800,
      max: 2200,
      salesCount: 5,
      unis: "GUC & AUC Engineering"
    },
    stewart_calc: {
      name: "Stewart Calculus 8th/9th Edition",
      base: 250,
      min: 210,
      max: 290,
      salesCount: 12,
      unis: "Engineering & Science Faculties"
    },
    medical_coat: {
      name: "Medical Cotton Lab Coat + Dissection Kit",
      base: 320,
      min: 280,
      max: 360,
      salesCount: 10,
      unis: "Kasr Al Ainy & Ain Shams Medicine"
    },
    architecture_set: {
      name: "Rotring Technical Pens & 90cm T-Square",
      base: 480,
      min: 440,
      max: 540,
      salesCount: 7,
      unis: "Applied Arts & Architecture Hubs"
    },
    raspberry_pi: {
      name: "Raspberry Pi 4 Model B (4GB RAM)",
      base: 1800,
      min: 1650,
      max: 1980,
      salesCount: 6,
      unis: "Computer & AI Faculties"
    }
  };

  if (aiCalcBtn && aiItemSelect) {
    aiCalcBtn.addEventListener('click', function () {
      const selectedKey = aiItemSelect.value;
      const conditionRadio = document.querySelector('input[name="ai_condition"]:checked');
      const condition = conditionRadio ? conditionRadio.value : 'like_new';
      const hasAccessories = document.getElementById('aiAccessories') ? document.getElementById('aiAccessories').checked : true;

      const itemData = priceEstimates[selectedKey] || priceEstimates.arduino_uno;

      // Condition multipliers
      let multiplier = 1.0;
      if (condition === 'new') multiplier = 1.15;
      else if (condition === 'like_new') multiplier = 1.0;
      else if (condition === 'good') multiplier = 0.85;
      else if (condition === 'fair') multiplier = 0.70;

      if (!hasAccessories) multiplier *= 0.90;

      const calculatedMin = Math.round((itemData.min * multiplier) / 10) * 10;
      const calculatedMax = Math.round((itemData.max * multiplier) / 10) * 10;
      const calculatedSweetSpot = Math.round(((calculatedMin + calculatedMax) / 2) / 10) * 10;

      // Animate calculation state
      aiCalcBtn.innerHTML = `
        <svg class="spin-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
          <path d="M12 2a10 10 0 0 1 10 10"></path>
        </svg>
        Analyzing College Transactions...
      `;
      aiCalcBtn.disabled = true;

      setTimeout(() => {
        aiCalcBtn.innerHTML = `
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
          Re-Calculate Fair Price
        `;
        aiCalcBtn.disabled = false;

        if (aiResultRange) {
          aiResultRange.textContent = `${calculatedMin} – ${calculatedMax} EGP`;
        }
        if (aiExplanationText) {
          aiExplanationText.innerHTML = `
            <strong>Recommendation:</strong> Asking around <strong>${calculatedSweetSpot} EGP</strong> is estimated to sell within <strong>48 hours</strong> on campus.
            <br><span style="color: var(--color-lavender); font-size: 0.78rem;">Based on ${itemData.salesCount} verified student transactions in ${itemData.unis} during the last 30 days.</span>
          `;
        }
        if (aiBarFill) {
          aiBarFill.style.width = `${Math.min(95, Math.max(35, multiplier * 65))}%`;
        }
        showToast(`AI calculated fair price: ${calculatedMin}–${calculatedMax} EGP`);
      }, 450);
    });
  }

  // --------------------------------------------------------------------------
  // AI Photo Upload Dropzone
  // --------------------------------------------------------------------------
  const aiPhotoDropzone  = document.getElementById('aiPhotoDropzone');
  const aiPhotoInput     = document.getElementById('aiPhotoInput');
  const aiUploadEmpty    = document.getElementById('aiUploadEmpty');
  const aiUploadPreview  = document.getElementById('aiUploadPreview');
  const aiPreviewImg     = document.getElementById('aiPreviewImg');
  const aiPreviewName    = document.getElementById('aiPreviewName');
  const aiRemovePhotoBtn = document.getElementById('aiRemovePhotoBtn');

  let aiHasPhoto = false;

  function showPhotoPreview(file) {
    if (!file || !file.type.startsWith('image/')) {
      showToast('Please upload a valid image file.');
      return;
    }
    const reader = new FileReader();
    reader.onload = function (e) {
      if (aiPreviewImg)    aiPreviewImg.src           = e.target.result;
      if (aiPreviewName)   aiPreviewName.textContent  = file.name;
      if (aiUploadEmpty)   aiUploadEmpty.style.display   = 'none';
      if (aiUploadPreview) aiUploadPreview.style.display = 'flex';
      aiHasPhoto = true;
    };
    reader.readAsDataURL(file);
  }

  function clearPhotoPreview() {
    if (aiPreviewImg)    aiPreviewImg.src             = '';
    if (aiPreviewName)   aiPreviewName.textContent    = '';
    if (aiUploadEmpty)   aiUploadEmpty.style.display  = 'flex';
    if (aiUploadPreview) aiUploadPreview.style.display = 'none';
    if (aiPhotoInput)    aiPhotoInput.value           = '';
    aiHasPhoto = false;
  }

  if (aiPhotoDropzone) {
    // Click on dropzone → trigger file picker (ignore clicks on the remove button)
    aiPhotoDropzone.addEventListener('click', function (e) {
      if (aiRemovePhotoBtn && (e.target === aiRemovePhotoBtn || aiRemovePhotoBtn.contains(e.target))) return;
      aiPhotoInput && aiPhotoInput.click();
    });

    // File chosen via picker
    if (aiPhotoInput) {
      aiPhotoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
          showPhotoPreview(this.files[0]);
        }
      });
    }

    // Drag & drop handlers
    aiPhotoDropzone.addEventListener('dragover', function (e) {
      e.preventDefault();
      e.stopPropagation();
      aiPhotoDropzone.classList.add('drag-over');
    });

    aiPhotoDropzone.addEventListener('dragleave', function (e) {
      e.preventDefault();
      e.stopPropagation();
      aiPhotoDropzone.classList.remove('drag-over');
    });

    aiPhotoDropzone.addEventListener('drop', function (e) {
      e.preventDefault();
      e.stopPropagation();
      aiPhotoDropzone.classList.remove('drag-over');
      const file = e.dataTransfer.files && e.dataTransfer.files[0];
      if (file) showPhotoPreview(file);
    });
  }

  // Remove photo button
  if (aiRemovePhotoBtn) {
    aiRemovePhotoBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      clearPhotoPreview();
    });
  }

  // Patch AI calc button to show photo note when a photo is attached
  if (aiCalcBtn && aiExplanationText) {
    aiCalcBtn.addEventListener('click', function () {
      if (aiHasPhoto) {
        setTimeout(function () {
          let photoNote = aiExplanationText.querySelector('.photo-note');
          if (!photoNote) {
            photoNote = document.createElement('span');
            photoNote.className = 'photo-note';
            photoNote.style.cssText = 'display:block; color:var(--color-mauve); font-size:0.78rem; margin-top:6px;';
            aiExplanationText.appendChild(photoNote);
          }
          photoNote.innerHTML = '📷 Product photo analyzed — condition estimate refined.';
        }, 460);
      } else {
        const existing = aiExplanationText.querySelector('.photo-note');
        if (existing) existing.remove();
      }
    });
  }

  // --------------------------------------------------------------------------
  // Interactive Quick View Modal
  // --------------------------------------------------------------------------
  const modalOverlay = document.getElementById('quickViewModal');
  const modalCloseBtn = document.getElementById('modalCloseBtn');

  window.openQuickView = function (productId) {
    const item = products.find(p => p.id === productId);
    if (!item || !modalOverlay) return;

    document.getElementById('modalImage').src = item.image;
    document.getElementById('modalImage').alt = item.title;
    document.getElementById('modalTitle').textContent = item.title;
    document.getElementById('modalCategory').textContent = item.majorName;
    document.getElementById('modalFaculty').textContent = item.faculty;
    document.getElementById('modalCondition').textContent = item.condition;
    document.getElementById('modalCondition').className = `badge ${item.conditionClass}`;
    document.getElementById('modalPrice').textContent = `${item.price} EGP`;
    document.getElementById('modalOriginalPrice').textContent = `${item.originalPrice} EGP`;
    document.getElementById('modalDiscount').textContent = item.discount;
    document.getElementById('modalAiPrice').textContent = item.aiSuggested;
    document.getElementById('modalLocation').textContent = item.location;
    document.getElementById('modalDescription').textContent = item.description;
    document.getElementById('modalSellerName').textContent = item.sellerName;
    document.getElementById('modalSellerRating').textContent = item.sellerRating;

    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  if (modalCloseBtn) {
    modalCloseBtn.addEventListener('click', closeModal);
  }

  if (modalOverlay) {
    modalOverlay.addEventListener('click', function (e) {
      if (e.target === modalOverlay) closeModal();
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modalOverlay && modalOverlay.classList.contains('active')) {
      closeModal();
    }
  });

  function closeModal() {
    if (modalOverlay) {
      modalOverlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  }

  window.submitQuickOffer = function (e) {
    e.preventDefault();
    const offerInput = document.getElementById('quickOfferAmount');
    const amount = offerInput ? offerInput.value : '';
    closeModal();
    showToast(`🎉 Offer of ${amount ? amount + ' EGP' : 'interest'} sent to student seller!`);
  };

  // --------------------------------------------------------------------------
  // Wishlist Toggle
  // --------------------------------------------------------------------------
  window.toggleWishlist = function (id, event) {
    if (event) event.stopPropagation();
    const btn = event.currentTarget;
    const isSaved = btn.getAttribute('data-saved') === 'true';

    if (isSaved) {
      btn.setAttribute('data-saved', 'false');
      btn.style.color = '';
      btn.innerHTML = `<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>`;
      showToast('Removed from saved items');
    } else {
      btn.setAttribute('data-saved', 'true');
      btn.style.color = '#e53935';
      btn.innerHTML = `<svg width="17" height="17" viewBox="0 0 24 24" fill="#e53935" stroke="#e53935" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>`;
      showToast('❤️ Saved to your campus wishlist!');
    }
  };

  // --------------------------------------------------------------------------
  // Toast Notification System
  // --------------------------------------------------------------------------
  function showToast(message) {
    let toast = document.getElementById('toastNotice');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'toastNotice';
      toast.className = 'toast-notice';
      document.body.appendChild(toast);
    }
    toast.innerHTML = `
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
      <span>${message}</span>
    `;
    toast.classList.add('show');
    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(() => {
      toast.classList.remove('show');
    }, 3200);
  }
  window.showToast = showToast;

  // --------------------------------------------------------------------------
  // Floating Campus Chatbot Assistant ("chat_bot")
  // --------------------------------------------------------------------------
  const chatbotToggle = document.getElementById('chatbotToggle');
  const chatbotWindow = document.getElementById('chatbotWindow');
  const chatbotClose = document.getElementById('chatbotClose');
  const chatbotBody = document.getElementById('chatbotBody');
  const chatbotInput = document.getElementById('chatbotInput');
  const chatbotSend = document.getElementById('chatbotSend');

  if (chatbotToggle && chatbotWindow) {
    chatbotToggle.addEventListener('click', function () {
      chatbotWindow.classList.toggle('open');
      if (chatbotWindow.classList.contains('open') && chatbotInput) {
        setTimeout(() => chatbotInput.focus(), 300);
      }
    });
  }

  if (chatbotClose && chatbotWindow) {
    chatbotClose.addEventListener('click', function () {
      chatbotWindow.classList.remove('open');
    });
  }

  const botResponses = {
    pickup: "📍 Safe campus meetups happen right inside your university! Popular safe spots are the Central Library entrance, the Main Cafeteria, or Student Union halls. You can inspect the item in person before completing the sale.",
    price: "✨ Our AI Fair Price Suggestion analyzes completed sales for the exact same book edition or hardware model across Egyptian universities (AUC, GUC, Cairo Univ, Ain Shams). It suggests a balanced range so you never get underpaid or overcharged!",
    majors: "🎓 We organize listings by academic majors: Engineering, Medicine & Health, Computer Science & AI, Architecture & Arts, Pharmacy & Science, and Business & Management.",
    how: "🔍 You can search or filter items by major, click 'Quick View' to inspect photos and AI price suggestions, and click 'Send Offer' to connect with the student seller for a campus meetup!",
    default: "👋 I'm your Second Chance campus assistant! You can ask me about safe campus pickup spots, academic majors, or how the AI price suggestion works."
  };

  window.handleChatbotChip = function (topic) {
    let questionText = "";
    let replyText = "";

    if (topic === 'pickup') {
      questionText = "How do safe campus meetups work?";
      replyText = botResponses.pickup;
    } else if (topic === 'price') {
      questionText = "How does the AI fair price work?";
      replyText = botResponses.price;
    } else if (topic === 'majors') {
      questionText = "Which academic majors are covered?";
      replyText = botResponses.majors;
    } else if (topic === 'how') {
      questionText = "How do I buy an item on campus?";
      replyText = botResponses.how;
    }

    addChatMessage(questionText, 'user');
    showTypingAndReply(replyText);
  };

  if (chatbotSend && chatbotInput) {
    const handleSend = function () {
      const text = chatbotInput.value.trim();
      if (!text) return;
      addChatMessage(text, 'user');
      chatbotInput.value = '';

      const lower = text.toLowerCase();
      let reply = botResponses.default;
      if (lower.includes('pickup') || lower.includes('meet') || lower.includes('safe') || lower.includes('campus')) {
        reply = botResponses.pickup;
      } else if (lower.includes('price') || lower.includes('ai') || lower.includes('suggest') || lower.includes('cost')) {
        reply = botResponses.price;
      } else if (lower.includes('major') || lower.includes('faculty') || lower.includes('eng') || lower.includes('med')) {
        reply = botResponses.majors;
      } else if (lower.includes('buy') || lower.includes('order') || lower.includes('how') || lower.includes('offer')) {
        reply = botResponses.how;
      }

      showTypingAndReply(reply);
    };

    chatbotSend.addEventListener('click', handleSend);
    chatbotInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') handleSend();
    });
  }

  function addChatMessage(text, sender) {
    if (!chatbotBody) return;
    const bubble = document.createElement('div');
    bubble.className = `chat-bubble chat-bubble-${sender}`;
    bubble.textContent = text;
    chatbotBody.appendChild(bubble);
    chatbotBody.scrollTop = chatbotBody.scrollHeight;
  }

  function showTypingAndReply(replyText) {
    if (!chatbotBody) return;
    const typingBubble = document.createElement('div');
    typingBubble.className = 'chat-bubble chat-bubble-bot';
    typingBubble.innerHTML = `<em>Thinking...</em>`;
    chatbotBody.appendChild(typingBubble);
    chatbotBody.scrollTop = chatbotBody.scrollHeight;

    setTimeout(() => {
      typingBubble.textContent = replyText;
      chatbotBody.scrollTop = chatbotBody.scrollHeight;
    }, 450);
  }

  // --------------------------------------------------------------------------
  // User Profile Dropdown Toggle
  // --------------------------------------------------------------------------
  const userProfileBtn = document.getElementById('userProfileBtn');
  const userDropdown = document.getElementById('userDropdown');

  if (userProfileBtn && userDropdown) {
    userProfileBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      userDropdown.classList.toggle('show');
    });

    document.addEventListener('click', function () {
      userDropdown.classList.remove('show');
    });
  }

  // --------------------------------------------------------------------------
  // Mobile Nav Toggle
  // --------------------------------------------------------------------------
  const mobileToggle = document.getElementById('mobileMenuToggle');
  const navLinks = document.querySelector('.nav-links');

  if (mobileToggle && navLinks) {
    mobileToggle.addEventListener('click', function () {
      const isVisible = navLinks.style.display === 'flex';
      navLinks.style.display = isVisible ? 'none' : 'flex';
      if (!isVisible) {
        navLinks.style.flexDirection = 'column';
        navLinks.style.position = 'absolute';
        navLinks.style.top = '100%';
        navLinks.style.left = '0';
        navLinks.style.right = '0';
        navLinks.style.backgroundColor = '#f2e9e4';
        navLinks.style.padding = '20px';
        navLinks.style.borderBottom = '1px solid rgba(74,78,105,0.15)';
        navLinks.style.boxShadow = '0 10px 20px rgba(0,0,0,0.08)';
      }
    });
  }

  // --------------------------------------------------------------------------
  // Hero Product Slideshow Controls & Auto-Rotation
  // --------------------------------------------------------------------------
  const heroSlides = document.querySelectorAll('.hero-slide');
  const slideDots = document.querySelectorAll('.slide-dot');
  const slidePrevBtn = document.getElementById('slidePrevBtn');
  const slideNextBtn = document.getElementById('slideNextBtn');
  const slideCounter = document.getElementById('slideCounter');
  const heroSlideshow = document.getElementById('heroSlideshow');
  let currentSlide = 0;
  let slideInterval = null;

  function showSlide(index) {
    if (!heroSlides.length) return;
    if (index < 0) currentSlide = heroSlides.length - 1;
    else if (index >= heroSlides.length) currentSlide = 0;
    else currentSlide = index;

    heroSlides.forEach((slide, idx) => {
      slide.classList.toggle('active', idx === currentSlide);
    });

    slideDots.forEach((dot, idx) => {
      dot.classList.toggle('active', idx === currentSlide);
    });

    if (slideCounter) {
      slideCounter.innerHTML = `<span style="color:var(--color-primary); font-weight:800;">${currentSlide + 1} / ${heroSlides.length}</span> • Campus Product Showcase`;
    }
  }

  function startSlideshow() {
    if (slideInterval) clearInterval(slideInterval);
    slideInterval = setInterval(() => {
      showSlide(currentSlide + 1);
    }, 4000);
  }

  function stopSlideshow() {
    if (slideInterval) clearInterval(slideInterval);
  }

  if (slidePrevBtn) {
    slidePrevBtn.addEventListener('click', () => {
      showSlide(currentSlide - 1);
      startSlideshow();
    });
  }

  if (slideNextBtn) {
    slideNextBtn.addEventListener('click', () => {
      showSlide(currentSlide + 1);
      startSlideshow();
    });
  }

  slideDots.forEach(dot => {
    dot.addEventListener('click', function () {
      const idx = parseInt(this.getAttribute('data-slide'), 10);
      showSlide(idx);
      startSlideshow();
    });
  });

  if (heroSlideshow) {
    heroSlideshow.addEventListener('mouseenter', stopSlideshow);
    heroSlideshow.addEventListener('mouseleave', startSlideshow);
  }

  // Start auto-slide
  startSlideshow();
});

