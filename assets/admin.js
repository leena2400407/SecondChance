(() => {
    "use strict";//strict mode for better error checking

    const categories = {
        books: "Books and Notes",
        electronics: "Electronics and Calculators",
        lab: "Lab Equipment",
        art: "Art and Architecture Supplies",
        accessories: "University Accessories"
    };

    const conditions = {
        like_new: "Like New",
        good: "Good",
        fair: "Fair"
    };

    const listingStatuses = {
        available: "Available",
        reserved: "Reserved",
        sold: "Sold",
        hidden: "Hidden"
    };

    const userStatuses = {
        active: "Active",
        suspended: "Suspended"
    };

    const roles = {
        user: "User",
        admin: "Admin"
    };

    const namePattern = /^\p{L}[\p{L}\p{M} '\u2019-]*$/u;
    const phonePattern = /^\+?[0-9]{7,15}$/;

    const seed = {
        users: [
            {
                id: 1,
                fname: "Mariam",
                lname: "Hassan",
                email: "mariam@example.com",
                phone: "01012345678",
                role: "user",
                status: "active"
            },
            {
                id: 2,
                fname: "Omar",
                lname: "Ali",
                email: "omar@example.com",
                phone: "",
                role: "user",
                status: "active"
            },
            {
                id: 3,
                fname: "Salma",
                lname: "Mostafa",
                email: "salma@example.com",
                phone: "",
                role: "user",
                status: "suspended"
            },
            {
                id: 4,
                fname: "Demo",
                lname: "Administrator",
                email: "admin@example.com",
                phone: "",
                role: "admin",
                status: "active"
            }
        ],

        listings: [
            {
                id: 101,
                sellerId: 1,
                title: "Scientific calculator",
                category: "electronics",
                condition: "like_new",
                description: "Scientific calculator with its original cover.",
                price: 650,
                image: "",
                instapay: "01012345678",
                acceptsCod: true,
                status: "available"
            },
            {
                id: 102,
                sellerId: 2,
                title: "Calculus textbook",
                category: "books",
                condition: "good",
                description: "Clean textbook with a few pencil notes.",
                price: 240,
                image: "",
                instapay: "",
                acceptsCod: true,
                status: "reserved"
            },
            {
                id: 103,
                sellerId: 3,
                title: "Architecture drawing set",
                category: "art",
                condition: "good",
                description: "Drawing set including a ruler and compass.",
                price: 320,
                image: "",
                instapay: "",
                acceptsCod: true,
                status: "hidden"
            }
        ],

        activity: []
    };

    const $ = id => document.getElementById(id);

    const cloneSeed = () => JSON.parse(JSON.stringify(seed));

    let data = cloneSeed();
    let section = "listings";

    function fullName(user) {
        return user
            ? `${user.fname} ${user.lname}`
            : "Unknown user";
    }

    function sellerName(id) {
        return fullName(
            data.users.find(user => user.id === Number(id))
        );
    }

    function nextId(records) {//find the next available id for a new record
        return Math.max(
            0,
            ...records.map(record => Number(record.id))
        ) + 1;
    }

    function notice(message) {//display a notice to the admin 
        $("adminNotice").textContent = message;
        $("adminNotice").hidden = false;
    }

    function save(message) {
        data.activity.unshift({
            time: new Date().toISOString(),
            message
        });

        data.activity = data.activity.slice(0, 200);

        render();
        notice(message + " Demo changes last until the page is reloaded.");
    }

    function makeButton(text, action, style = "secondary") {
        const button = document.createElement("button");

        button.type = "button";
        button.className = "admin-button " + style;
        button.textContent = text;

        button.addEventListener("click", action);

        return button;
    }

    function addCell(row, value) {
        const cell = document.createElement("td");

        cell.textContent = String(value ?? "");
        row.appendChild(cell);

        return cell;
    }

    function addBadge(row, status, labels) {
        const cell = addCell(row, "");
        const badge = document.createElement("span");

        badge.className = "admin-status " + status;
        badge.textContent = labels[status] || status;

        cell.appendChild(badge);
    }

    function filteredRecords() {
        const query = $("searchInput").value
            .trim()
            .toLowerCase();

        const status = $("statusFilter").value;

        return data[section].filter(record => {
            let searchable = Object.values(record)
                .filter(value => typeof value !== "boolean")
                .join(" ");

            if (section === "listings") {
                searchable = [
                    record.id,
                    record.title,
                    sellerName(record.sellerId),
                    categories[record.category],
                    conditions[record.condition],
                    record.description,
                    record.price,
                    listingStatuses[record.status]
                ].join(" ");
            }

            return (
                searchable.toLowerCase().includes(query)
                && (!status || record.status === status)
            );
        });
    }

    function renderStats() {//manage dashboard cells in the top
        const values = [
            [
                "Total Listings",
                data.listings.length,
                "All demo marketplace items"
            ],
            [
                "Available Listings",
                data.listings.filter(
                    item => item.status === "available"
                ).length,
                "Available demo items"
            ],
            [
                "Total Users",
                data.users.length,
                "All demo accounts"
            ],
            [
                "Active Users",
                data.users.filter(
                    user => user.status === "active"
                ).length,
                "Active demo accounts"
            ]
        ];

        $("adminStats").replaceChildren();

        values.forEach(([label, value, hint]) => {
            const card = document.createElement("article");
            card.className = "admin-stat";

            const title = document.createElement("p");
            title.textContent = label;

            const total = document.createElement("strong");
            total.textContent = value;

            const description = document.createElement("small");
            description.textContent = hint;

            card.append(title, total, description);
            $("adminStats").appendChild(card);
        });
    }

    function renderTable() {//betrsem el gadwal 
        const headings = {
            listings: [
                "Listing",
                "Seller",
                "Category",
                "Price",
                "Status",
                "Actions"
            ],

            users: [
                "Name",
                "Email",
                "Phone",
                "Role",
                "Status",
                "Actions"
            ],

            activity: [
                "Date and Time",
                "Action"
            ]
        };

        $("tableHead").replaceChildren();
        $("tableBody").replaceChildren();

        const headingRow = document.createElement("tr");

        headings[section].forEach(text => {
            const th = document.createElement("th");

            th.scope = "col";
            th.textContent = text;

            headingRow.appendChild(th);
        });

        $("tableHead").appendChild(headingRow);

        const records = filteredRecords();

        $("resultCount").textContent =
            records.length + " records shown";

        $("emptyMessage").hidden = records.length > 0;

        records.forEach(record => {
            const row = document.createElement("tr");

            if (section === "activity") {
                addCell(
                    row,
                    new Date(record.time).toLocaleString()
                );

                addCell(row, record.message);
            } else {
                if (section === "listings") {
                    addCell(row, record.title);
                    addCell(row, sellerName(record.sellerId));
                    addCell(row, categories[record.category]);

                    addCell(
                        row,
                        Number(record.price).toFixed(2) + " EGP"
                    );

                    addBadge(
                        row,
                        record.status,
                        listingStatuses
                    );
                } else {
                    addCell(row, fullName(record));
                    addCell(row, record.email);
                    addCell(row, record.phone || "Not provided");
                    addCell(row, roles[record.role]);

                    addBadge(
                        row,
                        record.status,
                        userStatuses
                    );
                }

                const recordSection = section;
                const actions = document.createElement("div");

                actions.className = "admin-row-actions";

                actions.append(
                    makeButton(
                        "View",
                        () => viewRecord(recordSection, record.id)
                    ),

                    makeButton(
                        "Edit",
                        () => openEditor(recordSection, record.id)
                    ),

                    makeButton(
                        "Delete",
                        () => deleteRecord(recordSection, record.id),
                        "danger"
                    )
                );

                addCell(row, "").appendChild(actions);
            }

            $("tableBody").appendChild(row);
        });
    }

    function render() {
        renderStats();
        renderTable();
    }

    function setSection(value) {
        section = value;

        const titles = {
            listings: "All Listings",
            users: "All Users",
            activity: "Activity History"
        };

        const descriptions = {
            listings: "Manage listings using your website's product fields.",
            users: "Manage demo users using your sign-up fields.",
            activity: "Review your recent demo management actions."
        };

        $("sectionHeading").textContent = titles[section];

        $("sectionDescription").textContent =
            descriptions[section];

        $("searchInput").value = "";

        $("searchInput").placeholder =
            "Search " + section + "...";

        $("statusFilter").replaceChildren(
            new Option("All statuses", "")
        );

        const statuses = section === "listings"
            ? listingStatuses
            : section === "users"
                ? userStatuses
                : {};

        Object.entries(statuses).forEach(([value, label]) => {
            $("statusFilter").add(
                new Option(label, value)
            );
        });

        $("statusFilter").disabled = section === "activity";
        $("addButton").hidden = section === "activity";

        $("addButton").textContent = section === "users"
            ? "+ Add User"
            : "+ Add Listing";

        document.querySelectorAll("[data-section]")
            .forEach(button => {
                if (button.dataset.section === section) {
                    button.setAttribute("aria-current", "page");
                } else {
                    button.removeAttribute("aria-current");
                }
            });

        render();
    }

    function openDialog(title) {//opens forms in the pages 
        $("dialogHeading").textContent = title;
        $("dialogBody").replaceChildren();

        $("adminDialog").showModal();
    }

    function safeImageSource(value) {
        return typeof value === "string"
            && /^data:image\/(?:jpeg|png|webp);base64,/i.test(value);
    }

    function viewRecord(type, id) {//view the details of a record 
        const record = data[type].find(item => item.id === id);

        if (!record) return;

        openDialog(
            type === "listings"
                ? "Listing Details"
                : "User Details"
        );

        if (
            type === "listings"
            && safeImageSource(record.image)
        ) {
            const image = document.createElement("img");

            image.className = "admin-product-preview";
            image.src = record.image;
            image.alt = record.title;

            $("dialogBody").appendChild(image);
        }

        const details = document.createElement("dl");
        details.className = "admin-details";

        const values = type === "listings"
            ? [
                ["ID", "#" + record.id],
                ["Title", record.title],
                ["Seller", sellerName(record.sellerId)],
                ["Category", categories[record.category]],
                ["Condition", conditions[record.condition]],
                ["Description", record.description],
                ["Price", record.price + " EGP"],
                ["Instapay", record.instapay || "Not provided"],
                [
                    "Cash on delivery",
                    record.acceptsCod ? "Accepted" : "Not accepted"
                ],
                ["Status", listingStatuses[record.status]],
                [
                    "Image",
                    record.image
                        ? "Image attached"
                        : "No image attached to this sample"
                ]
            ]
            : [
                ["ID", "#" + record.id],
                ["First name", record.fname],
                ["Last name", record.lname],
                ["Email", record.email],
                ["Phone", record.phone || "Not provided"],
                ["Role", roles[record.role]],
                ["Status", userStatuses[record.status]]
            ];

        values.forEach(([label, value]) => {
            const term = document.createElement("dt");
            const description = document.createElement("dd");

            term.textContent = label;
            description.textContent = value;

            details.append(term, description);
        });

        $("dialogBody").appendChild(details);
    }

    function addField(parent, labelText, name, value, options = {}) {
        const label = document.createElement("label");
        label.textContent = labelText;

        let input;

        if (options.choices) {
            input = document.createElement("select");

            Object.entries(options.choices).forEach(
                ([optionValue, optionLabel]) => {
                    input.add(
                        new Option(optionLabel, optionValue)
                    );
                }
            );
        } else if (options.multiline) {
            input = document.createElement("textarea");
        } else {
            input = document.createElement("input");
            input.type = options.type || "text";
        }

        input.name = name;
        input.required = options.required !== false;

        if (input.type !== "file") {
            input.value = value ?? "";
        }

        if (options.maxLength) {
            input.maxLength = options.maxLength;
        }

        if (options.autocomplete) {
            input.autocomplete = options.autocomplete;
        }

        if (options.min !== undefined) {
            input.min = options.min;
        }

        if (options.max !== undefined) {
            input.max = options.max;
        }

        if (options.step !== undefined) {
            input.step = options.step;
        }

        label.appendChild(input);

        if (options.hint) {
            const hint = document.createElement("small");
            hint.textContent = options.hint;

            label.appendChild(hint);
        }

        parent.appendChild(label);

        return input;
    }

    function addRow(form) {
        const row = document.createElement("div");
        row.className = "admin-form-row";

        form.appendChild(row);

        return row;
    }

    async function readImage(file) {//read and validate the image file size
        if (
            !["image/jpeg", "image/png", "image/webp"]
                .includes(file.type)
        ) {
            throw new Error("Choose a JPG, PNG or WEBP image.");
        }

        if (file.size === 0 || file.size > 5 * 1024 * 1024) {
            throw new Error(
                "Choose a non-empty image no larger than 5 MB."
            );
        }

        const source = await new Promise((resolve, reject) => {
            const reader = new FileReader();

            reader.onload = () => resolve(reader.result);

            reader.onerror = () => reject(
                new Error("The image could not be read.")
            );

            reader.readAsDataURL(file);
        });

        await new Promise((resolve, reject) => {
            const image = new Image();

            image.onload = () => {
                if (
                    image.naturalWidth > 12000
                    || image.naturalHeight > 12000
                ) {
                    reject(
                        new Error(
                            "Image dimensions must not exceed 12000 pixels per side."
                        )
                    );
                } else {
                    resolve();
                }
            };

            image.onerror = () => reject(
                new Error("The selected file is not a readable image.")
            );

            image.src = source;
        });

        return source;
    }

    function validName(value) {
        const length = [...value].length;

        return length >= 2
            && length <= 50
            && namePattern.test(value);
    }

    function openEditor(type, id = null) {//open the form to add or edit a record
        const existing = id === null
            ? null
            : data[type].find(item => item.id === id);

        if (id !== null && !existing) return;

        if (type === "listings" && data.users.length === 0) {
            notice("Add a demo user before creating a listing.");
            return;
        }

        const record = existing || {};

        openDialog(
            (existing ? "Edit " : "Add ")
            + (type === "listings" ? "Listing" : "User")
        );

        const form = document.createElement("form");
        form.className = "admin-form";

        let imageValue = record.image || "";
        let imagePending = false;
        let imageVersion = 0;

        const errorBox = document.createElement("div");

        errorBox.className = "admin-form-error";
        errorBox.setAttribute("role", "alert");
        errorBox.hidden = true;

        function showErrors(errors) {
            errorBox.replaceChildren();
            errorBox.hidden = errors.length === 0;

            if (!errors.length) return;

            const list = document.createElement("ul");

            errors.forEach(message => {
                const li = document.createElement("li");
                li.textContent = message;

                list.appendChild(li);
            });

            errorBox.appendChild(list);
        }

        if (type === "users") {
            const row = addRow(form);

            addField(row, "First name", "fname", record.fname, {
                maxLength: 50,
                autocomplete: "given-name"
            });

            addField(row, "Last name", "lname", record.lname, {
                maxLength: 50,
                autocomplete: "family-name"
            });

            addField(form, "Email address", "email", record.email, {
                type: "email",
                maxLength: 255,
                autocomplete: "email"
            });

            addField(
                form,
                "Phone number (optional)",
                "phone",
                record.phone,
                {
                    type: "tel",
                    maxLength: 16,
                    required: false,
                    autocomplete: "tel",
                    hint: "Use 7 to 15 digits, with an optional leading +."
                }
            );

            if (!existing) {
                addField(form, "Password", "password", "", {
                    type: "password",
                    maxLength: 72,
                    autocomplete: "new-password",
                    hint: "8 to 72 UTF-8 bytes, including an English letter and a number. Not stored in this demo."
                });

                addField(
                    form,
                    "Confirm password",
                    "confirm_password",
                    "",
                    {
                        type: "password",
                        maxLength: 72,
                        autocomplete: "new-password"
                    }
                );
            }

            const controls = addRow(form);

            addField(
                controls,
                "Account role",
                "role",
                record.role || "user",
                {
                    choices: roles
                }
            );

            addField(
                controls,
                "Account status",
                "status",
                record.status || "active",
                {
                    choices: userStatuses
                }
            );
        } else {
            const sellerChoices = Object.fromEntries(
                data.users.map(user => [
                    user.id,
                    fullName(user)
                ])
            );

            addField(
                form,
                "Seller",
                "sellerId",
                record.sellerId ?? data.users[0].id,
                {
                    choices: sellerChoices
                }
            );

            addField(
                form,
                "Product title",
                "title",
                record.title,
                {
                    maxLength: 150
                }
            );

            const row = addRow(form);

            addField(
                row,
                "Category",
                "category",
                record.category || "",
                {
                    choices: {
                        "": "Select a category",
                        ...categories
                    }
                }
            );

            addField(
                row,
                "Condition",
                "condition",
                record.condition || "",
                {
                    choices: {
                        "": "Select condition",
                        ...conditions
                    }
                }
            );

            addField(
                form,
                "Description",
                "description",
                record.description,
                {
                    multiline: true,
                    maxLength: 3000
                }
            );

            addField(
                form,
                "Price (EGP)",
                "price",
                record.price ?? "",
                {
                    type: "number",
                    min: "0.01",
                    max: "99999999.99",
                    step: "0.01"
                }
            );

            const imageInput = addField(
                form,
                existing
                    ? "Product image (select to replace)"
                    : "Product image",
                "image",
                "",
                {
                    type: "file",
                    required: !existing,
                    hint: "JPG, PNG or WEBP. Up to 5 MB and 12000 pixels per side."
                }
            );

            imageInput.accept = "image/jpeg,image/png,image/webp";

            const preview = document.createElement("img");
            preview.className = "admin-product-preview";
            preview.alt = "Selected product image preview";
            preview.hidden = !safeImageSource(imageValue);

            if (!preview.hidden) {
                preview.src = imageValue;
            }

            form.appendChild(preview);

            imageInput.addEventListener("change", async () => {
                const version = ++imageVersion;
                const file = imageInput.files[0];

                imageInput.setCustomValidity("");
                imagePending = false;
                showErrors([]);

                if (!file) {
                    imageValue = record.image || "";
                    preview.hidden = !safeImageSource(imageValue);

                    if (!preview.hidden) {
                        preview.src = imageValue;
                    }

                    return;
                }

                imagePending = true;

                try {
                    const source = await readImage(file);

                    if (version !== imageVersion) return;

                    imageValue = source;
                    preview.src = source;
                    preview.hidden = false;
                } catch (error) {
                    if (version !== imageVersion) return;

                    imageInput.value = "";
                    imageValue = record.image || "";

                    preview.hidden = !safeImageSource(imageValue);

                    if (!preview.hidden) {
                        preview.src = imageValue;
                    }

                    imageInput.setCustomValidity(error.message);
                    showErrors([error.message]);
                } finally {
                    if (version === imageVersion) {
                        imagePending = false;
                    }
                }
            });

            addField(
                form,
                "Instapay number",
                "instapay_number",
                record.instapay,
                {
                    type: "tel",
                    maxLength: 16,
                    required: false,
                    hint: "Optional if you accept cash on delivery. Use 7 to 15 digits, with an optional leading +."
                }
            );

            const checkboxLabel = document.createElement("label");
            checkboxLabel.className = "admin-checkbox";

            const checkbox = document.createElement("input");

            checkbox.type = "checkbox";
            checkbox.name = "accepts_cod";
            checkbox.checked = record.acceptsCod ?? true;

            checkboxLabel.append(
                checkbox,
                document.createTextNode(
                    "I accept cash on delivery"
                )
            );

            form.appendChild(checkboxLabel);

            addField(
                form,
                "Listing status",
                "status",
                record.status || "available",
                {
                    choices: listingStatuses
                }
            );
        }

        form.appendChild(errorBox);

        const actions = document.createElement("div");
        actions.className = "admin-form-actions";

        actions.appendChild(
            makeButton(
                "Cancel",
                () => $("adminDialog").close()
            )
        );

        const submit = document.createElement("button");

        submit.type = "submit";
        submit.className = "admin-button";
        submit.textContent = existing
            ? "Save Changes"
            : type === "users"
                ? "Add User"
                : "Add Listing";

        actions.appendChild(submit);
        form.appendChild(actions);

        form.addEventListener("submit", event => {
            event.preventDefault();

            const values = new FormData(form);

            const text = name =>
                String(values.get(name) || "").trim();

            const errors = [];
            let updated;

            if (type === "users") {
                const fname = text("fname");
                const lname = text("lname");
                const email = text("email");
                const phone = text("phone");
                const role = text("role");
                const status = text("status");

                if (!validName(fname)) {
                    errors.push(
                        "First name must contain 2 to 50 characters: letters, spaces, apostrophes or hyphens."
                    );
                }

                if (!validName(lname)) {
                    errors.push(
                        "Last name must contain 2 to 50 characters: letters, spaces, apostrophes or hyphens."
                    );
                }

                if (
                    !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
                    || new TextEncoder().encode(email).length > 255
                ) {
                    errors.push("Enter a valid email address.");
                }

                if (
                    data.users.some(user =>
                        user.id !== existing?.id
                        && user.email.toLowerCase()
                            === email.toLowerCase()
                    )
                ) {
                    errors.push(
                        "This email is already used by another demo user."
                    );
                }

                if (phone && !phonePattern.test(phone)) {
                    errors.push(
                        "Phone must contain 7 to 15 digits, with an optional leading +."
                    );
                }

                if (!existing) {
                    const password =
                        String(values.get("password") || "");

                    const confirmation =
                        String(values.get("confirm_password") || "");

                    const bytes =
                        new TextEncoder().encode(password).length;

                    if (
                        bytes < 8
                        || bytes > 72
                        || !/[a-zA-Z]/.test(password)
                        || !/[0-9]/.test(password)
                        || password.includes("\0")
                    ) {
                        errors.push(
                            "Password must contain 8 to 72 bytes, including an English letter and a number, with no null bytes."
                        );
                    }

                    if (password !== confirmation) {
                        errors.push("Passwords do not match.");
                    }
                }

                if (!Object.hasOwn(roles, role)) {
                    errors.push("Choose a valid role.");
                }

                if (!Object.hasOwn(userStatuses, status)) {
                    errors.push("Choose a valid account status.");
                }

                updated = {
                    id: existing?.id ?? nextId(data.users),
                    fname,
                    lname,
                    email,
                    phone,
                    role,
                    status
                };
            } else {
                const title = text("title");
                const category = text("category");
                const condition = text("condition");
                const description = text("description");
                const priceText = text("price");
                const price = Number(priceText);
                const instapay = text("instapay_number");
                const acceptsCod = values.has("accepts_cod");
                const status = text("status");
                const sellerId = Number(text("sellerId"));

                if (
                    [...title].length < 3
                    || [...title].length > 150
                ) {
                    errors.push(
                        "Title must contain 3 to 150 characters."
                    );
                }

                if (!Object.hasOwn(categories, category)) {
                    errors.push("Choose a valid category.");
                }

                if (!Object.hasOwn(conditions, condition)) {
                    errors.push("Choose a valid condition.");
                }

                if (
                    [...description].length < 10
                    || [...description].length > 3000
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
                        "Enter a price from 0.01 to 99999999.99 with up to two decimal places."
                    );
                }

                if (instapay && !phonePattern.test(instapay)) {
                    errors.push(
                        "Instapay must contain 7 to 15 digits, with an optional leading +."
                    );
                }

                if (!acceptsCod && !instapay) {
                    errors.push(
                        "Enter an Instapay number or accept cash on delivery."
                    );
                }

                if (imagePending) {
                    errors.push(
                        "Please wait for the image check to finish."
                    );
                }

                if (!existing && !safeImageSource(imageValue)) {
                    errors.push("Select a valid product image.");
                }

                if (
                    !data.users.some(user => user.id === sellerId)
                ) {
                    errors.push("Choose an existing seller.");
                }

                if (!Object.hasOwn(listingStatuses, status)) {
                    errors.push("Choose a valid listing status.");
                }

                updated = {
                    id: existing?.id ?? nextId(data.listings),
                    sellerId,
                    title,
                    category,
                    condition,
                    description,
                    price,
                    image: imageValue,
                    instapay,
                    acceptsCod,
                    status
                };
            }

            showErrors(errors);

            if (errors.length) return;

            if (existing) {
                const index = data[type].findIndex(
                    item => item.id === existing.id
                );

                data[type][index] = updated;
            } else {
                data[type].unshift(updated);
            }

            form.querySelectorAll('input[type="password"]')
                .forEach(input => {
                    input.value = "";
                });

            $("adminDialog").close();

            save(
                (type === "users" ? "User" : "Listing")
                + " #" + updated.id
                + (existing ? " updated." : " added.")
            );
        });

        $("dialogBody").appendChild(form);
    }

    function deleteRecord(type, id) {
        const record = data[type].find(item => item.id === id);

        if (!record) return;

        if (
            type === "users"
            && data.listings.some(item => item.sellerId === id)
        ) {
            notice(
                "Reassign or delete this user's listings before deleting the user."
            );

            return;
        }

        const name = type === "users"
            ? fullName(record)
            : record.title;

        if (!confirm(`Delete "${name}" from this demo?`)) {
            return;
        }

        data[type] = data[type].filter(item => item.id !== id);

        save(
            (type === "users" ? "User" : "Listing")
            + " #" + id + " deleted."
        );
    }

    function exportCSV() {
        const records = filteredRecords();

        if (!records.length) {
            notice("No matching records to export.");
            return;
        }

        const exportRows = records.map(record => {
            if (section === "listings") {
                return {
                    id: record.id,
                    title: record.title,
                    seller: sellerName(record.sellerId),
                    category: categories[record.category],
                    condition: conditions[record.condition],
                    description: record.description,
                    price: record.price,
                    instapay: record.instapay,
                    cash_on_delivery: record.acceptsCod ? "Yes" : "No",
                    status: listingStatuses[record.status]
                };
            }

            return record;
        });

        const columns = Object.keys(exportRows[0]);

        function quote(value) {
            let text = String(value ?? "");

            if (/^\s*[=+@-]/.test(text)) {
                text = "'" + text;
            }

            return '"' + text.replaceAll('"', '""') + '"';
        }

        const csv = [
            columns,
            ...exportRows.map(record =>
                columns.map(column => record[column])
            )
        ]
            .map(row => row.map(quote).join(","))
            .join("\r\n");

        const url = URL.createObjectURL(
            new Blob(
                ["\uFEFF" + csv],
                { type: "text/csv;charset=utf-8" }
            )
        );

        const link = document.createElement("a");

        link.href = url;
        link.download = `second-chance-demo-${section}.csv`;

        document.body.appendChild(link);
        link.click();
        link.remove();

        setTimeout(() => URL.revokeObjectURL(url), 1000);

        notice("Demo records exported.");
    }

    document.querySelectorAll("[data-section]")
        .forEach(button => {
            button.addEventListener("click", () => {
                setSection(button.dataset.section);
            });
        });

    $("searchInput").addEventListener("input", renderTable);

    $("statusFilter").addEventListener("change", renderTable);

    $("addButton").addEventListener("click", () => {
        if (section !== "activity") {
            openEditor(section);
        }
    });

    $("exportButton").addEventListener("click", exportCSV);

    $("closeDialog").addEventListener("click", () => {
        $("adminDialog").close();
    });

    $("adminDialog").addEventListener("close", () => {
        $("dialogBody").replaceChildren();
    });

    setSection("listings");
})();