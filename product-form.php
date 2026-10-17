<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List an Item | Second Chance</title>

    <link rel="stylesheet" href="assets/styles.css">
</head>

<body>

<main class="container">
    <div class="brand">Second Chance</div>

    <h1>Give your item a second chance.</h1>

    <p class="subtitle">
        List university-related items and help another student
        find what they need.
    </p>

    <form id="productForm" method="post"
          enctype="multipart/form-data">

        <div class="field">
            <label for="title">Product title *</label>
            <input
                type="text"
                id="title"
                name="title"
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
                    <option value="books">Books and Notes</option>
                    <option value="electronics">
                        Electronics and Calculators
                    </option>
                    <option value="lab">Lab Equipment</option>
                    <option value="art">
                        Art and Architecture Supplies
                    </option>
                    <option value="accessories">
                        University Accessories
                    </option>
                </select>
            </div>

            <div class="field">
                <label for="condition">Condition *</label>

                <select id="condition" name="item_condition" required>
                    <option value="">Select condition</option>
                    <option value="like_new">Like New</option>
                    <option value="good">Good</option>
                    <option value="fair">Fair</option>
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
            ></textarea>
        </div>

        <div class="field">
            <label for="price">Price (EGP) *</label>

            <input
                type="number"
                id="price"
                name="price"
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
                required
            >

            <small class="hint">
                JPG, PNG, or WEBP. Maximum size: 5 MB.
            </small>

            <img id="imagePreview"
                 alt="Selected product image preview"
                 hidden>
        </div>

        <div class="field">
            <label for="instapay">Instapay number</label>

            <input
                type="tel"
                id="instapay"
                name="instapay_number"
                placeholder="Enter your Instapay-linked mobile number"
                maxlength="20"
            >

            <small class="hint">
                Optional if you accept cash on delivery.
            </small>
        </div>

        <div class="field">
            <label class="checkbox">
                <input
                    type="checkbox"
                    id="cod"
                    name="accepts_cod"
                    value="1"
                    checked
                >
                I accept cash on delivery
            </label>
        </div>

        <button type="submit">List Item</button>

        <div id="errorMessages" role="alert"></div>
        <div id="successMessage" role="status"></div>
    </form>
</main>

<script>
    const form = document.getElementById("productForm");
    const imageInput = document.getElementById("image");
    const preview = document.getElementById("imagePreview");
    const errorMessages = document.getElementById("errorMessages");
    const successMessage = document.getElementById("successMessage");

    const allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/webp"
    ];

    let previewUrl = null;

    function clearPreview() {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        preview.removeAttribute("src");
        preview.hidden = true;
    }

    imageInput.addEventListener("change", function () {
        clearPreview();
        errorMessages.textContent = "";
        successMessage.textContent = "";

        const file = imageInput.files[0];

        if (!file) {
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            errorMessages.textContent =
                "Please select a JPG, PNG, or WEBP image.";
            imageInput.value = "";
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            errorMessages.textContent =
                "The image must be 5 MB or smaller.";
            imageInput.value = "";
            return;
        }

        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        preview.hidden = false;
    });

    form.addEventListener("submit", function (event) {
        // Preview stage: prevent submission until PHP saving is added.
        event.preventDefault();

        errorMessages.textContent = "";
        successMessage.textContent = "";

        const errors = [];

        const title = document.getElementById("title").value.trim();
        const description =
            document.getElementById("description").value.trim();
        const price = Number(document.getElementById("price").value);
        const instapay =
            document.getElementById("instapay").value.trim();
        const acceptsCod = document.getElementById("cod").checked;

        if (title.length < 3) {
            errors.push("Product title must contain at least 3 characters.");
        }

        if (description.length < 10) {
            errors.push("Description must contain at least 10 characters.");
        }

        if (!Number.isFinite(price) || price <= 0) {
            errors.push("Please enter a valid price greater than zero.");
        }

        if (!acceptsCod && instapay === "") {
            errors.push(
                "Enter an Instapay number or accept cash on delivery."
            );
        }

        if (errors.length > 0) {
            const list = document.createElement("ul");

            errors.forEach(function (message) {
                const item = document.createElement("li");
                item.textContent = message;
                list.appendChild(item);
            });

            errorMessages.appendChild(list);
            return;
        }

        successMessage.textContent =
            "Form validation passed. This preview does not save the item yet.";
    });
</script>

</body>
</html>