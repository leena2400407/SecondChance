
<?php
$productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Product Details | Second Chance</title>

    <!-- Your existing project styles -->
    <link rel="stylesheet" href="assets/index.css">
    <link rel="stylesheet" href="assets/product-details.css">

    <!-- Fonts used by the project -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="product-details-page">

<?php include 'includes/navbar.php'; ?>

<main class="details-main">

    <!-- Breadcrumb -->
    <div class="details-container">
        <nav class="details-breadcrumb" aria-label="Breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <a href="index.php#showcase">Marketplace</a>
            <span>/</span>
            <span class="breadcrumb-current">Product Details</span>
        </nav>

        <!-- Loading state -->
        <div id="productLoading" class="details-message">
            <div class="loading-spinner"></div>
            <h2>Preparing something special...</h2>
            <p>Loading your selected product.</p>
        </div>

        <!-- Product not found -->
        <div id="productNotFound" class="details-message" hidden>
            <div class="not-found-icon">✦</div>
            <h2>Product not found</h2>
            <p>We couldn't find this product. Please return to the marketplace and choose a product again.</p>
            <a href="index.php" class="details-btn details-btn-primary">
                Back to Marketplace
            </a>
        </div>

        <!-- Product content -->
        <section id="productDetails" hidden>

            <div class="details-topline">
                <div>
                    <span class="details-eyebrow">SECOND CHANCE COLLECTION</span>
                    <h1>Find your next <span>favorite.</span></h1>
                    <p class="details-intro">
                        Good finds, fair prices, and a second life for every item.
                    </p>
                </div>

                <div class="campus-trust">
                    <span class="trust-icon">✦</span>
                    <div>
                        <strong>Campus Marketplace</strong>
                        <small>Buy smarter. Waste less.</small>
                    </div>
                </div>
            </div>

            <div class="details-layout">

                <!-- Left: Product image -->
                <div class="details-gallery">
                    <div class="details-image-wrap">
                        <div class="image-top-badges">
                            <span id="conditionBadge" class="details-condition-badge">Like New</span>
                            <span id="exchangeBadge" class="details-exchange-badge" hidden>
                                ⇄ Exchange Available
                            </span>
                        </div>

                        <button
                            type="button"
                            id="detailsWishlist"
                            class="details-wishlist"
                            aria-label="Add to wishlist"
                            title="Add to wishlist"
                        >♡</button>

                        <img id="detailImage" src="" alt="Product image">

                        <div class="image-caption">
                            <span class="caption-dot"></span>
                            A second chance at something great
                        </div>
                    </div>

                    <div class="gallery-note">
                        <span class="gallery-note-icon">✧</span>
                        <div>
                            <strong>Make a smarter choice</strong>
                            <p>Discover pre-loved items from your campus community.</p>
                        </div>
                    </div>

                    <div class="details-mini-stats">
                        <div>
                            <span class="mini-stat-icon">♡</span>
                            <strong>Pre-loved</strong>
                            <small>Less waste</small>
                        </div>
                        <div>
                            <span class="mini-stat-icon">✦</span>
                            <strong>Student finds</strong>
                            <small>Campus community</small>
                        </div>
                    </div>
                </div>

                <!-- Right: Product information -->
                <div class="details-information">

                    <div class="details-category-row">
                        <span id="detailCategory" class="details-category">Product Category</span>
                        <span id="detailFaculty" class="details-faculty">Faculty</span>
                    </div>

                    <h2 id="detailTitle" class="details-product-title">Product Name</h2>

                    <div class="details-rating-row">
                        <span class="rating-stars">★★★★★</span>
                        <strong id="detailRating">5.0</strong>
                        <span class="rating-label">Seller rating</span>
                    </div>

                    <!-- Price -->
                    <div class="details-price-card">
                        <div class="price-main-row">
                            <div>
                                <span class="price-label">Asking price</span>
                                <div class="price-value">
                                    <span id="detailPrice">0</span>
                                    <span class="price-currency">EGP</span>
                                </div>
                            </div>

                            <span id="detailDiscount" class="details-discount" hidden>
                                SAVE 0%
                            </span>
                        </div>

                        <div class="price-bottom-row">
                            <span class="original-price-label">Original price</span>
                            <del id="detailOriginalPrice">0 EGP</del>
                        </div>

                        <div class="price-divider"></div>

                        <div class="fair-price-row" id="fairPriceRow">
                            <span class="fair-price-icon">✧</span>
                            <div>
                                <strong>AI Fair Price Insight</strong>
                                <p id="detailFairPrice">
                                    Check whether this item offers good value.
                                </p>
                            </div>
                            <span class="fair-price-tag">SMART FIND</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="details-section">
                        <div class="details-section-heading">
                            <span class="section-heading-icon">✦</span>
                            <h3>About this item</h3>
                        </div>
                        <p id="detailDescription" class="details-description"></p>
                    </div>

                    <!-- Product specifications -->
                    <div class="details-section">
                        <div class="details-section-heading">
                            <span class="section-heading-icon">▦</span>
                            <h3>Product information</h3>
                        </div>

                        <div class="specifications-grid">
                            <div class="specification-item">
                                <span class="spec-icon">✧</span>
                                <div>
                                    <small>Condition</small>
                                    <strong id="specCondition">-</strong>
                                </div>
                            </div>

                            <div class="specification-item">
                                <span class="spec-icon">⌂</span>
                                <div>
                                    <small>Faculty</small>
                                    <strong id="specFaculty">-</strong>
                                </div>
                            </div>

                            <div class="specification-item">
                                <span class="spec-icon">▤</span>
                                <div>
                                    <small>Major</small>
                                    <strong id="specMajor">-</strong>
                                </div>
                            </div>

                            <div class="specification-item">
                                <span class="spec-icon">⌖</span>
                                <div>
                                    <small>Location</small>
                                    <strong id="specLocation">-</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment options -->
                    <div class="details-section">
                        <div class="details-section-heading">
                            <span class="section-heading-icon">◈</span>
                            <h3>Payment & exchange</h3>
                        </div>

                        <div class="payment-options">
                            <div class="payment-option" id="instapayOption">
                                <span class="payment-option-icon">↗</span>
                                <div>
                                    <strong>InstaPay</strong>
                                    <small id="instapayStatus">Check with seller</small>
                                </div>
                            </div>

                            <div class="payment-option" id="depositOption">
                                <span class="payment-option-icon">◉</span>
                                <div>
                                    <strong>Deposit</strong>
                                    <small id="depositStatus">Check with seller</small>
                                </div>
                            </div>

                            <div class="payment-option" id="exchangeOption">
                                <span class="payment-option-icon">⇄</span>
                                <div>
                                    <strong>Exchange</strong>
                                    <small id="exchangeStatus">Check with seller</small>
                                </div>
                            </div>
                        </div>

                        <p class="payment-disclaimer">
                            Confirm the payment method and all transaction details directly
                            with the seller before making any payment.
                        </p>
                    </div>

                    <!-- Seller -->
                    <div class="seller-details-card">
                        <div class="seller-card-heading">
                            <span>MEET THE SELLER</span>
                            <span class="seller-online-dot"></span>
                        </div>

                        <div class="seller-card-content">
                            <div id="detailSellerAvatar" class="seller-details-avatar">S</div>

                            <div class="seller-details-info">
                                <h3 id="detailSellerName">Student Seller</h3>
                                <p>
                                    <span class="seller-star">★</span>
                                    <span id="sellerRatingText">5.0</span>
                                    <span class="seller-rating-caption">Seller rating</span>
                                </p>
                                <small id="sellerLocationText">Campus community</small>
                            </div>

                            <span class="seller-verified">✦ Campus seller</span>
                        </div>

                        <div class="seller-safety-note">
                            <span>♧</span>
                            <p>For a safer transaction, arrange to meet in a public campus location.</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="details-actions">
                        <button type="button" id="contactSellerBtn" class="details-btn details-btn-primary">
                            <span>✉</span> Contact Seller
                        </button>

                        <button type="button" id="shareProductBtn" class="details-btn details-btn-secondary">
                            <span>↗</span> Share Product
                        </button>
                    </div>

                    <a href="index.php#showcase" class="back-to-marketplace">
                        <span>←</span> Back to all products
                    </a>

                    <p id="detailsNotice" class="details-notice" role="status" hidden></p>

                </div>
            </div>

            <!-- Bottom banner -->
            <section class="details-bottom-banner">
                <div class="bottom-banner-symbol">✧</div>
                <div>
                    <span>THE SECOND CHANCE PROMISE</span>
                    <h3>Great finds deserve another chapter.</h3>
                    <p>Every pre-loved purchase gives useful items a new home.</p>
                </div>
                <a href="index.php#showcase" class="banner-link">Explore more <span>→</span></a>
            </section>

        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productId = <?= json_encode($productId) ?>;

    const loading = document.getElementById('productLoading');
    const notFound = document.getElementById('productNotFound');
    const details = document.getElementById('productDetails');

    function showNotFound() {
        loading.hidden = true;
        details.hidden = true;
        notFound.hidden = false;
    }

    function formatPrice(value) {
        const number = Number(value);
        if (!Number.isFinite(number)) return 'Price not specified';

        return new Intl.NumberFormat('en-EG', {
            maximumFractionDigits: 2
        }).format(number);
    }

    function setText(id, value, fallback = 'Not specified') {
        document.getElementById(id).textContent =
            value !== undefined && value !== null && value !== ''
                ? String(value)
                : fallback;
    }

    // Product data is currently stored in script.js and passed through localStorage.
    let product;

    try {
        product = JSON.parse(localStorage.getItem('selectedProduct') || 'null');
    } catch (error) {
        product = null;
    }

    if (!product || Number(product.id) !== productId || productId <= 0) {
        showNotFound();
        return;
    }

    // Basic product information
    setText('detailTitle', product.title);
    setText('detailCategory', product.majorName || product.major);
    setText('detailFaculty', product.faculty);
    setText('detailPrice', formatPrice(product.price));
    setText('detailOriginalPrice', formatPrice(product.originalPrice) + ' EGP');
    setText('detailDescription', product.description);
    setText('detailRating', formatPrice(product.sellerRating || 0));
    setText('detailSellerName', product.sellerName);
    setText('sellerRatingText', formatPrice(product.sellerRating || 0));
    setText('sellerLocationText', product.location, 'Campus community');
    setText('specCondition', product.condition);
    setText('specFaculty', product.faculty);
    setText('specMajor', product.majorName || product.major);
    setText('specLocation', product.location);

    // Product image
    const image = document.getElementById('detailImage');
    image.src = product.image || '';
    image.alt = product.title || 'Product image';

    image.addEventListener('error', function () {
        image.alt = 'Product image unavailable';
        image.style.display = 'none';
    });

    // Condition badge
    const conditionBadge = document.getElementById('conditionBadge');
    conditionBadge.textContent = product.condition || 'Pre-loved';
    conditionBadge.classList.add(product.conditionClass || '');

    // Exchange
    const exchangeAvailable =
        product.exchange === true ||
        product.exchange === 'true' ||
        product.exchange === 'yes';

    document.getElementById('exchangeBadge').hidden = !exchangeAvailable;
    setText(
        'exchangeStatus',
        exchangeAvailable ? 'Available' : 'Not available'
    );

    // Discount
    const discountBadge = document.getElementById('detailDiscount');
    let discount = Number(product.discount);

    if (!Number.isFinite(discount) && Number(product.originalPrice) > 0) {
        discount = Math.round(
            (1 - Number(product.price) / Number(product.originalPrice)) * 100
        );
    }

    if (discount > 0) {
        discountBadge.hidden = false;
        discountBadge.textContent = 'SAVE ' + Math.round(discount) + '%';
    } else {
        discountBadge.hidden = true;
    }

    // AI fair-price information
    const fairPriceRow = document.getElementById('fairPriceRow');

    if (product.aiSuggested) {
        setText('detailFairPrice', 'Suggested price: ' + formatPrice(product.aiSuggested) + ' EGP');
    } else {
        setText('detailFairPrice', 'Compare the asking price with similar items before buying.');
    }

    // Payment options
    function showAvailability(elementId, value) {
        const element = document.getElementById(elementId);
        const available =
            value === true ||
            value === 'true' ||
            value === 'yes';

        element.textContent = available ? 'Available' : 'Confirm with seller';
        element.closest('.payment-option').classList.toggle('is-available', available);
    }

    showAvailability('instapayStatus', product.instapay);
    showAvailability('depositStatus', product.deposit);

    // Seller avatar
    const avatar = document.getElementById('detailSellerAvatar');
    avatar.textContent = product.sellerAvatar || (product.sellerName || 'S').charAt(0).toUpperCase();

    // Wishlist button: reuses the existing wishlist data if available.
    const wishlistButton = document.getElementById('detailsWishlist');
    let wishlist = [];

    try {
        wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        if (!Array.isArray(wishlist)) wishlist = [];
    } catch (error) {
        wishlist = [];
    }

    function isWishlisted() {
        return wishlist.some(item =>
            Number(typeof item === 'object' ? item.id : item) === productId
        );
    }

    function updateWishlistButton() {
        const active = isWishlisted();
        wishlistButton.textContent = active ? '♥' : '♡';
        wishlistButton.classList.toggle('is-active', active);
        wishlistButton.setAttribute(
            'aria-label',
            active ? 'Remove from wishlist' : 'Add to wishlist'
        );
    }

    updateWishlistButton();

    wishlistButton.addEventListener('click', function () {
        if (isWishlisted()) {
            wishlist = wishlist.filter(item =>
                Number(typeof item === 'object' ? item.id : item) !== productId
            );
        } else {
            wishlist.push(productId);
        }

        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        updateWishlistButton();
    });

    // Contact seller: no contact details exist in the current mock data.
    document.getElementById('contactSellerBtn').addEventListener('click', function () {
        const notice = document.getElementById('detailsNotice');
        notice.textContent = 'Seller contact details are not available yet. You can add a contact method when you connect the project to seller accounts.';
        notice.hidden = false;
    });

    // Share product
    document.getElementById('shareProductBtn').addEventListener('click', async function () {
        const shareData = {
            title: product.title || 'Second Chance Product',
            text: 'Check out this product on Second Chance!',
            url: window.location.href
        };

        try {
            if (navigator.share) {
                await navigator.share(shareData);
            } else if (navigator.clipboard) {
                await navigator.clipboard.writeText(window.location.href);
                const notice = document.getElementById('detailsNotice');
                notice.textContent = 'Product link copied!';
                notice.hidden = false;
            } else {
                const notice = document.getElementById('detailsNotice');
                notice.textContent = 'Copy this page URL to share the product.';
                notice.hidden = false;
            }
        } catch (error) {
            // The user may cancel the share window.
        }
    });

    loading.hidden = true;
    notFound.hidden = true;
    details.hidden = false;
});
</script>

</body>
</html>
