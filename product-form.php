<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$previewErrors = [];
$previewSuccess = false;

$categories = [
    'books' => 'Books and Notes',
    'electronics' => 'Electronics and Calculators',
    'lab' => 'Lab Equipment',
    'art' => 'Art and Architecture Supplies',
    'accessories' => 'University Accessories'
];

$conditions = [
    'like_new' => 'Like New',
    'good' => 'Good',
    'fair' => 'Fair'
];

$title = '';
$category = '';
$condition = '';
$description = '';
$price = '';
$instapay = '';
$acceptsCod = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = formText('title');
    $category = formText('category');
    $condition = formText('item_condition');
    $description = formText('description');
    $price = formText('price');
    $instapay = formText('instapay_number');
    $acceptsCod = ($_POST['accepts_cod'] ?? '') === '1';

    // Validate the CSRF token.
    if (!validToken()) {
        $previewErrors[] =
            'Please refresh the page and try again.';
    }

    // Validate title.
    if (mb_strlen($title) < 3 || mb_strlen($title) > 150) {
        $previewErrors[] =
            'Title must contain 3 to 150 characters.';
    }

    // Validate category and condition.
    if (!array_key_exists($category, $categories)) {
        $previewErrors[] = 'Choose a valid category.';
    }

    if (!array_key_exists($condition, $conditions)) {
        $previewErrors[] = 'Choose a valid condition.';
    }

    // Validate description.
    if (
        mb_strlen($description) < 10
        || mb_strlen($description) > 3000
    ) {
        $previewErrors[] =
            'Description must contain 10 to 3000 characters.';
    }

    // Validate price format and range.
    if (
        !preg_match('/\A[0-9]+(?:\.[0-9]{1,2})?\z/', $price)
        || (float) $price < 0.01
        || (float) $price > 99999999.99
    ) {
        $previewErrors[] =
            'Enter a price from 0.01 to 99999999.99, '
            . 'with up to two decimal places.';
    }

    // Validate the optional Instapay number.
    if (
        $instapay !== ''
        && !validPhoneNumber($instapay)
    ) {
        $previewErrors[] =
            'Instapay number must contain 7 to 15 digits, '
            . 'with an optional leading +.';
    }

    // Validate the checkbox value if submitted.
    if (isset($_POST['accepts_cod']) && !$acceptsCod) {
        $previewErrors[] =
            'Invalid cash on delivery option.';
    }

    // Require at least one payment option.
    if (!$acceptsCod && $instapay === '') {
        $previewErrors[] =
            'Enter an Instapay number or accept cash on delivery.';
    }

    // Validate the uploaded image without saving it.
    $file = $_FILES['image'] ?? null;

    if (
        !is_array($file)
        || !isset($file['error'])
        || !is_int($file['error'])
        || $file['error'] !== UPLOAD_ERR_OK
    ) {
        $previewErrors[] =
            'Choose a valid image no larger than 5 MB.';
    } elseif (
        !is_string($file['tmp_name'] ?? null)
        || !is_uploaded_file($file['tmp_name'])
    ) {
        $previewErrors[] = 'Invalid image upload.';
    } else {
        $size = filesize($file['tmp_name']);

        $mime = (new finfo(FILEINFO_MIME_TYPE))
            ->file($file['tmp_name']);

        $imageInfo = @getimagesize($file['tmp_name']);

        if (
            $size === false
            || $size === 0
            || $size > 5 * 1024 * 1024
            || !in_array(
                $mime,
                ['image/jpeg', 'image/png', 'image/webp'],
                true
            )
            || !$imageInfo
            || $imageInfo[0] > 12000
            || $imageInfo[1] > 12000
        ) {
            $previewErrors[] =
                'Choose a real JPG, PNG or WEBP image, '
                . 'up to 5 MB and 12000 pixels per side.';
        }
    }

    $previewSuccess = empty($previewErrors);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>List an Item | Second Chance</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<main class="container">
    <div class="brand">Second Chance</div>

    <h1>Give your item a second chance.</h1>

    <p class="subtitle">
        List university-related items and help another student
        find what they need.
    </p>

    <?php if ($previewErrors): ?>
        <div class="message error" role="alert">
            <ul>
                <?php foreach ($previewErrors as $error): ?>
                    <li><?= escape($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif ($previewSuccess): ?>
        <div class="message success" role="status">
            Validation passed in the browser and PHP.
            Preview only: no item or image was saved.
        </div>
    <?php endif; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p class="hint">
            To submit again, please select your image again.
        </p>
    <?php endif; ?>

    <form
        id="productForm"
        method="post"
        enctype="multipart/form-data"
    >
        <input
            type="hidden"
            name="csrf_token"
            value="<?= escape($_SESSION['csrf_token']) ?>"
        >

        <div class="field">
            <label for="title">Product title *</label>

            <input
                type="text"
                id="title"
                name="title"
                value="<?= escape($title) ?>"
                placeholder="Example: Scientific calculator"
                minlength="3"
                maxlength="150"
                required
            >
        </div>

        <div class="row">
            <div class="field">
                <label for="category">Category *</label>

                <select id="category" name="category" required>
                    <option value="">Select a category</option>

                    <?php foreach ($categories as $value => $label): ?>
                        <option
                            value="<?= escape($value) ?>"
                            <?= $category === $value ? 'selected' : '' ?>
                        >
                            <?= escape($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="condition">Condition *</label>

                <select
                    id="condition"
                    name="item_condition"
                    required
                >
                    <option value="">Select condition</option>

                    <?php foreach ($conditions as $value => $label): ?>
                        <option
                            value="<?= escape($value) ?>"
                            <?= $condition === $value ? 'selected' : '' ?>
                        >
                            <?= escape($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="field">
            <label for="description">Description *</label>

            <textarea
                id="description"
                name="description"
                placeholder="Describe your item, its condition, and any defects."
                minlength="10"
                maxlength="3000"
                required
            ><?= escape($description) ?></textarea>
        </div>

        <div class="field">
            <label for="price">Price (EGP) *</label>

            <input
                type="number"
                id="price"
                name="price"
                value="<?= escape($price) ?>"
                placeholder="Example: 450"
                min="0.01"
                max="99999999.99"
                step="0.01"
                required
            >
        </div>

        <div class="field">
            <label for="image">Product image *</label>

            <input
                type="file"
                id="image"
                name="image"
                accept="image/jpeg,image/png,image/webp"
                aria-describedby="imageHint"
                required
            >

            <small id="imageHint" class="hint">
                JPG, PNG or WEBP. Maximum size: 5 MB.
                Maximum dimensions: 12000 pixels per side.
            </small>

            <img
                id="imagePreview"
                alt="Selected product image preview"
                style="max-width: 100%; max-height: 300px; object-fit: contain;"
                hidden
            >
        </div>

        <div class="field">
            <label for="instapay">Instapay number</label>

            <input
                type="tel"
                id="instapay"
                name="instapay_number"
                value="<?= escape($instapay) ?>"
                placeholder="Enter your Instapay-linked mobile number"
                maxlength="16"
                aria-describedby="instapayHint"
            >

            <small id="instapayHint" class="hint">
                Optional if you accept cash on delivery.
                Use 7 to 15 digits, with an optional leading +.
            </small>
        </div>

        <div class="field">
            <label class="checkbox">
                <input
                    type="checkbox"
                    id="cod"
                    name="accepts_cod"
                    value="1"
                    <?= $acceptsCod ? 'checked' : '' ?>
                >

                I accept cash on delivery
            </label>
        </div>

        <button type="submit">List Item</button>

        <div id="errorMessages" role="alert"></div>
        <div id="successMessage" role="status"></div>
    </form>

    <p>
        <a href="seller.php">Back to My Listings</a>
    </p>
</main>

<script>
(() => {
    const form = document.getElementById("productForm");
    const imageInput = document.getElementById("image");
    const preview = document.getElementById("imagePreview");
    const errorMessages = document.getElementById("errorMessages");
    const successMessage = document.getElementById("successMessage");

    const titleInput = document.getElementById("title");
    const descriptionInput = document.getElementById("description");
    const categoryInput = document.getElementById("category");
    const conditionInput = document.getElementById("condition");
    const priceInput = document.getElementById("price");
    const instapayInput = document.getElementById("instapay");
    const codInput = document.getElementById("cod");

    const allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/webp"
    ];

    const allowedCategories = [
        "books",
        "electronics",
        "lab",
        "art",
        "accessories"
    ];

    const allowedConditions = [
        "like_new",
        "good",
        "fair"
    ];

    let previewUrl = null;
    let imageCheckPending = false;
    let imageCheckVersion = 0;

    function clearPreview() {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        preview.removeAttribute("src");
        preview.hidden = true;
    }

    function showErrors(messages) {
        errorMessages.replaceChildren();

        if (messages.length === 0) {
            return;
        }

        const list = document.createElement("ul");

        messages.forEach(message => {
            const item = document.createElement("li");
            item.textContent = message;
            list.appendChild(item);
        });

        errorMessages.appendChild(list);
    }

    function checkImageFile(file) {
        if (!file) {
            return "Please select a product image.";
        }

        if (!allowedTypes.includes(file.type)) {
            return "Please select a JPG, PNG or WEBP image.";
        }

        if (file.size === 0 || file.size > 5 * 1024 * 1024) {
            return "The image must be non-empty and 5 MB or smaller.";
        }

        return "";
    }

    imageInput.addEventListener("change", () => {
        const version = ++imageCheckVersion;

        clearPreview();
        imageCheckPending = false;
        errorMessages.replaceChildren();
        successMessage.textContent = "";

        const file = imageInput.files[0];

        if (!file) {
            return;
        }

        const fileError = checkImageFile(file);

        if (fileError) {
            imageInput.value = "";
            showErrors([fileError]);
            return;
        }

        const objectUrl = URL.createObjectURL(file);
        previewUrl = objectUrl;
        imageCheckPending = true;

        const testImage = new Image();

        testImage.onload = () => {
            if (version !== imageCheckVersion) {
                return;
            }

            imageCheckPending = false;

            if (
                testImage.naturalWidth > 12000
                || testImage.naturalHeight > 12000
            ) {
                imageInput.value = "";
                clearPreview();

                showErrors([
                    "Image dimensions must not exceed "
                    + "12000 pixels per side."
                ]);

                return;
            }

            preview.src = objectUrl;
            preview.hidden = false;
        };

        testImage.onerror = () => {
            if (version !== imageCheckVersion) {
                return;
            }

            imageCheckPending = false;
            imageInput.value = "";
            clearPreview();

            showErrors([
                "The selected file could not be opened as an image."
            ]);
        };

        testImage.src = objectUrl;
    });

    form.addEventListener("submit", event => {
        errorMessages.replaceChildren();
        successMessage.textContent = "";

        const errors = [];

        const title = titleInput.value.trim();
        const description = descriptionInput.value.trim();
        const priceText = priceInput.value.trim();
        const price = Number(priceText);
        const instapay = instapayInput.value.trim();

        const titleLength = [...title].length;
        const descriptionLength = [...description].length;

        if (titleLength < 3 || titleLength > 150) {
            errors.push(
                "Product title must contain 3 to 150 characters."
            );
        }

        if (!allowedCategories.includes(categoryInput.value)) {
            errors.push("Choose a valid category.");
        }

        if (!allowedConditions.includes(conditionInput.value)) {
            errors.push("Choose a valid condition.");
        }

        if (
            descriptionLength < 10
            || descriptionLength > 3000
        ) {
            errors.push(
                "Description must contain 10 to 3000 characters."
            );
        }

        if (
            !/^[0-9]+(?:\.[0-9]{1,2})?$/.test(priceText)
            || !Number.isFinite(price)
            || price < 0.01
            || price > 99999999.99
        ) {
            errors.push(
                "Enter a price from 0.01 to 99999999.99, "
                + "with up to two decimal places."
            );
        }

        if (
            instapay !== ""
            && !/^\+?[0-9]{7,15}$/.test(instapay)
        ) {
            errors.push(
                "Instapay number must contain 7 to 15 digits, "
                + "with an optional leading +."
            );
        }

        if (!codInput.checked && instapay === "") {
            errors.push(
                "Enter an Instapay number or accept cash on delivery."
            );
        }

        const fileError = checkImageFile(imageInput.files[0]);

        if (fileError) {
            errors.push(fileError);
        }

        if (imageCheckPending) {
            errors.push(
                "Please wait for the image check to finish."
            );
        }

        if (errors.length > 0) {
            event.preventDefault();
            showErrors(errors);
            return;
        }

        // Allow submission to PHP for validation only.
        successMessage.textContent =
            "Checking preview with PHP...";
    });
})();
</script>

<?php sessionTimerMarkup(); ?>

</body>
</html>