<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard | Second Chance</title>

    <link rel="stylesheet" href="assets/index.css">
    <link rel="stylesheet" href="assets/admin.css">

    <script src="assets/admin.js" defer></script>
    <?php require __DIR__ . '/includes/navbar.php'; ?>
</head>

<body>
<main class="admin-app">

    <header class="admin-heading">
        <div>
            <p class="admin-eyebrow">
                SECOND CHANCE ADMINISTRATION
            </p>

            <h1>Manage your marketplace.</h1>

            <p class="admin-subtitle">
                Manage listings, community members, and marketplace activity.
            </p>
        </div>

        <a href="index.php" class="admin-button secondary">
            View Marketplace ↗
        </a>
    </header>

    <section
        class="admin-stats"
        id="adminStats"
        aria-label="Marketplace statistics"
    ></section>

    <div class="admin-layout">

        <nav class="admin-sidebar" aria-label="Administration">
            <p class="admin-eyebrow">WORKSPACE</p>

            <button
                type="button"
                data-section="listings"
                aria-current="page"
            >
                All Listings
            </button>

            <button type="button" data-section="users">
                All Users
            </button>

            <button type="button" data-section="activity">
                Activity History
            </button>

            <div class="admin-sidebar-note">
                Give useful items a second chance.
            </div>
        </nav>

        <section
            class="admin-panel"
            aria-labelledby="sectionHeading"
        >
            <div class="admin-panel-heading">
                <div>
                    <h2 id="sectionHeading">All Listings</h2>

                    <p id="sectionDescription">
                        View and manage marketplace listings.
                    </p>
                </div>

                <div class="admin-panel-actions">
                    <button
                        type="button"
                        class="admin-button secondary"
                        id="exportButton"
                    >
                        Export CSV
                    </button>

                    <button
                        type="button"
                        class="admin-button"
                        id="addButton"
                    >
                        + Add Listing
                    </button>
                </div>
            </div>

            <div class="admin-toolbar">
                <label>
                    Search

                    <input
                        type="search"
                        id="searchInput"
                        placeholder="Search listings..."
                    >
                </label>

                <label>
                    Status

                    <select id="statusFilter">
                        <option value="">All statuses</option>
                    </select>
                </label>
            </div>

            <p
                id="resultCount"
                class="admin-result-count"
                aria-live="polite"
            ></p>

            <div
                class="admin-table-wrapper"
                tabindex="0"
                role="region"
                aria-label="Administration records"
            >
                <table class="admin-table">
                    <thead id="tableHead"></thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>

            <p class="admin-empty" id="emptyMessage" hidden>
                No records found. Try a different search or add a new record.
            </p>
        </section>

    </div>

    <p
        class="admin-notice"
        id="adminNotice"
        role="status"
        aria-live="polite"
        hidden
    ></p>

</main>

<dialog
    id="adminDialog"
    class="admin-dialog"
    aria-labelledby="dialogHeading"
>
    <div class="admin-dialog-heading">
        <h2 id="dialogHeading">Manage record</h2>

        <button
            type="button"
            id="closeDialog"
            aria-label="Close dialog"
        >
            ×
        </button>
    </div>

    <div id="dialogBody"></div>
</dialog>

<?php sessionTimerMarkup(); ?>
<script src="assets/script.js"></script>

</body>
</html>