<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>

    <form action="/web400121051/items/update/<?php echo $item['id']; ?>" method="POST" enctype="multipart/form-data">
        <div>
            <label for="name">Item Name:</label><br>
            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($item['name']); ?>">
        </div>
        <br>

        <?php if (!empty($item['image_filename'])): ?>
        <div>
            <p><strong>Current Image:</strong></p>
            <img src="/web400121051/public/uploads/items/<?php echo htmlspecialchars($item['image_filename']); ?>" alt="Current image for <?php echo htmlspecialchars($item['name']); ?>" style="max-width: 200px; max-height: 200px; margin-bottom: 10px; border: 1px solid #ddd;">
        </div>
        <?php endif; ?>

        <div>
            <label for="item_image">Change/Upload New Item Image:</label><br>
            <input type="file" id="item_image" name="item_image">
            <small>Leave blank to keep the current image.</small>
        </div>
        <br>

        <div>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"><?php echo htmlspecialchars($item['description']); ?></textarea>
        </div>
        <br>
        <div>
            <label for="price">Price (€):</label><br>
            <input type="number" id="price" name="price" step="0.01" required value="<?php echo htmlspecialchars($item['price']); ?>">
        </div>
        <br>
        <div>
            <button type="submit">Update Item</button>
        </div>
    </form>

    <p><a href="/web400121051/items">Back to List</a></p>

</body>
</html>