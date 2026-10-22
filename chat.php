<?php
require_once __DIR__ . '/includes/auth.php';

// Safe session fallback
$isLoggedIn = isLoggedIn();
$userName = $isLoggedIn ? escape($_SESSION['fname'] ?? 'Student') : 'Student';

// Optional URL query params: chat.php?seller=Omar&item=Calculus&price=450
$activeSeller = escape($_GET['seller'] ?? 'Omar Farouk');
$activeItem   = escape($_GET['item'] ?? 'Calculus: Early Transcendentals (9th Ed)');
$activePrice  = escape($_GET['price'] ?? '450 EGP');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Chat | Second Chance</title>

    <!-- Project Styles & Fonts -->
    <link rel="stylesheet" href="assets/index.css">
    <link rel="stylesheet" href="assets/chat.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="chat-page">

<?php 
if (file_exists(__DIR__ . '/includes/navbar.php')) {
    include __DIR__ . '/includes/navbar.php';
}
?>

<div class="chat-wrapper">

    <!-- Main Chat Card -->
    <div class="chat-app">

        <!-- Conversations Sidebar -->
        <aside class="chat-sidebar">
            <div class="sidebar-header">Conversations</div>
            <div class="threads-list" id="threadsList">
                <!-- Thread 1 -->
                <div class="thread-item active" data-seller="Omar Farouk" data-item="Calculus 9th Edition" data-price="450 EGP">
                    <div class="thread-name">Omar Farouk (Seller)</div>
                    <div class="thread-item-name">📚 Calculus 9th Ed • 450 EGP</div>
                    <div class="thread-snippet">Can we meet at the Central Library?</div>
                </div>
                <!-- Thread 2 -->
                <div class="thread-item" data-seller="Nour El-Din" data-item="TI-84 Plus CE Calculator" data-price="1,800 EGP">
                    <div class="thread-name">Nour El-Din (Buyer)</div>
                    <div class="thread-item-name">🧮 TI-84 Plus CE • 1,800 EGP</div>
                    <div class="thread-snippet">Offer made: 1,650 EGP</div>
                </div>
                <!-- Thread 3 -->
                <div class="thread-item" data-seller="Mariam Hassan" data-item="Organic Chem Model Kit" data-price="320 EGP">
                    <div class="thread-name">Mariam Hassan (Seller)</div>
                    <div class="thread-item-name">🔬 Molecular Model Kit • 320 EGP</div>
                    <div class="thread-snippet">All pieces are complete!</div>
                </div>
            </div>
        </aside>

        <!-- Active Chat Window -->
        <main class="chat-area">

            <!-- Chat Header -->
            <div class="chat-header">
                <div>
                    <div class="partner-title" id="partnerTitle"><?= $activeSeller ?></div>
                    <div class="partner-status">● Active now • On-Campus Verified</div>
                </div>
            </div>

            <!-- Pinned Item Bar -->
            <div class="item-context">
                <div>
                    Negotiating: <strong id="itemTitle"><?= $activeItem ?></strong> (<span id="itemPrice"><?= $activePrice ?></span>)
                </div>
                <div class="quick-actions">
                    <button type="button" class="btn-action" id="btnQuickOffer">🏷️ Make Offer</button>
                    <button type="button" class="btn-action" id="btnQuickMeetup">📍 Meetup</button>
                </div>
            </div>

            <!-- Messages Stream -->
            <div class="messages-stream" id="messagesStream">
                <div class="msg received">
                    Hi! Are you interested in the <?= $activeItem ?>? It's in great condition.
                    <div class="msg-time">10:00 AM</div>
                </div>
                <div class="msg sent">
                    Hello! Yes, is it still available? Can we meet on campus today?
                    <div class="msg-time">10:02 AM</div>
                </div>
            </div>

            <!-- Typing indicator -->
            <div class="typing" id="typingIndicator">Omar is typing...</div>

            <!-- Quick Reply Chips -->
            <div class="quick-chips">
                <button type="button" class="chip" data-msg="Is this still available?">Still available?</button>
                <button type="button" class="chip" data-msg="Can we meet at Central Library ground floor?">📚 Meet at Library?</button>
                <button type="button" class="chip" data-msg="Would you accept 400 EGP cash?">💰 400 EGP cash?</button>
                <button type="button" class="chip" data-msg="Do you accept InstaPay on meetup?">📱 InstaPay ok?</button>
            </div>

            <!-- Message Input Form -->
            <form id="chatForm" class="chat-input-row" onsubmit="return false;">
                <input type="text" id="chatInput" class="chat-input" placeholder="Type a message (or click a quick chip)..." autocomplete="off">
                <button type="submit" id="btnSend" class="btn-send">Send</button>
            </form>

        </main>
    </div>
</div>

<?php 
if (file_exists(__DIR__ . '/includes/footer.php')) {
    include __DIR__ . '/includes/footer.php';
}
?>

<!-- Minimal, easy-to-study JavaScript -->
<script>
(function() {
    'use strict';

    let isSeller = false; // Perspective: false = Buyer, true = Seller
    const messagesStream = document.getElementById('messagesStream');
    const chatInput = document.getElementById('chatInput');
    const btnSend = document.getElementById('btnSend');
    const btnRoleToggle = document.getElementById('btnRoleToggle');
    const viewModeText = document.getElementById('viewModeText');
    const typingIndicator = document.getElementById('typingIndicator');
    const partnerTitle = document.getElementById('partnerTitle');
    const itemTitle = document.getElementById('itemTitle');
    const itemPrice = document.getElementById('itemPrice');

    function getTime() {
        const d = new Date();
        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // 1. Add standard text message
    function appendMessage(text, isSent) {
        const div = document.createElement('div');
        div.className = 'msg ' + (isSent ? 'sent' : 'received');
        div.innerHTML = `<div>${escapeHtml(text)}</div><div class="msg-time">${getTime()}</div>`;
        messagesStream.appendChild(div);
        messagesStream.scrollTop = messagesStream.scrollHeight;
    }

    // 2. Add special card (Offer / Meetup)
    function appendCard(badge, title, note, isSent) {
        const div = document.createElement('div');
        div.className = 'card-msg ' + (isSent ? 'sent' : 'received');
        div.innerHTML = `
            <div class="card-badge">${badge}</div>
            <div class="card-title">${escapeHtml(title)}</div>
            <div style="font-size:12px; color:var(--sc-slate);">${escapeHtml(note)}</div>
            <div class="msg-time">${getTime()}</div>
        `;
        messagesStream.appendChild(div);
        messagesStream.scrollTop = messagesStream.scrollHeight;
    }

    // 3. User sends a message
    function handleSend() {
        const text = chatInput.value.trim();
        if (!text) return;

        appendMessage(text, true);
        chatInput.value = '';

        // Simulate counterpart reply after 1s
        typingIndicator.style.display = 'block';
        setTimeout(() => {
            typingIndicator.style.display = 'none';
            let reply = "Sounds good! I can meet you right between lectures at Central Library.";
            if (text.toLowerCase().includes('offer') || text.toLowerCase().includes('egp')) {
                reply = "That offer works for me. Let's confirm the campus meetup spot!";
            }
            appendMessage(reply, false);
        }, 1100);
    }

    btnSend.addEventListener('click', handleSend);
    chatInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleSend();
        }
    });

    // 4. Quick suggestions chips
    document.querySelectorAll('.chip').forEach(chip => {
        chip.addEventListener('click', () => {
            chatInput.value = chip.dataset.msg;
            handleSend();
        });
    });

    // 5. Quick actions: Offer & Meetup
    document.getElementById('btnQuickOffer').addEventListener('click', () => {
        const offer = prompt('Enter your offer in EGP:', '400');
        if (offer) {
            appendCard('🏷️ Price Offer', offer + ' EGP', 'Offer proposed on campus.', true);
        }
    });

    document.getElementById('btnQuickMeetup').addEventListener('click', () => {
        const spot = prompt('Choose meetup spot:', 'Central Library - Ground Floor Study Desk');
        if (spot) {
            appendCard('📍 Campus Meetup Proposal', spot, 'Tomorrow at 2:00 PM (Mid-day Break)', true);
        }
    });

    // 6. Switch between Buyer and Seller perspective
    btnRoleToggle.addEventListener('click', () => {
        isSeller = !isSeller;
        viewModeText.textContent = isSeller ? 'Viewing as Seller' : 'Viewing as Buyer';
        btnRoleToggle.textContent = isSeller ? '⇄ Switch to Buyer View' : '⇄ Switch to Seller View';
        partnerTitle.textContent = isSeller ? 'Nour El-Din (Student Buyer)' : 'Omar Farouk (Seller)';
    });

    // 7. Click conversation thread to switch active item
    document.querySelectorAll('.thread-item').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.thread-item').forEach(i => i.classList.remove('active'));
            item.classList.add('active');
            partnerTitle.textContent = item.dataset.seller;
            itemTitle.textContent = item.dataset.item;
            itemPrice.textContent = item.dataset.price;
        });
    });

    function escapeHtml(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
})();
</script>
<script src="assets/script.js"></script>

</body>
</html>
