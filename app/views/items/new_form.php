<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Add New Item'; ?></title>
    </head>
<body>
        <h1><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Add New Item'; ?></h1>

    <?php
    if (isset($_SESSION['form_error'])) {
        echo '<p style="color:red;">' . htmlspecialchars($_SESSION['form_error']) . '</p>';
        unset($_SESSION['form_error']); // Clear error after displaying
    }
    if (isset($_SESSION['form_success'])) {
        echo '<p style="color:green;">' . htmlspecialchars($_SESSION['form_success']) . '</p>';
        unset($_SESSION['form_success']); // Clear success after displaying
    }
    // Optional: Re-populate form data if you store it in session on error
    // $name_value = $_SESSION['form_data']['name'] ?? '';
    // unset($_SESSION['form_data']); // Clear after use
    ?>

    <form action="/web400121051/items/create" method="POST" enctype="multipart/form-data">
        <div>
            <label for="name">Item Name:</label><br>
            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>
        <br>
        <div>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>
        <br>
        <div>
            <label for="price">Price (€):</label><br>
            <input type="number" id="price" name="price" step="0.01" required value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
        </div>
        <br>

        <div>
            <label for="item_image">Item Image:</label><br>
            <input type="file" id="item_image" name="item_image">
        </div>
        <br>
        <div>
            <button type="submit">Add Item</button>
        </div>
    </form>

    <p><a href="/web400121051/items">Back to List</a></p>

</body>
</html>