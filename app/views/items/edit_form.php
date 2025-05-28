<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    </head>
<body>
    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>

    <form action="/web400121051/items/update/<?php echo $item['id']; ?>" method="POST">
        <div>
            <label for="name">Item Name:</label><br>
            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($item['name']); ?>">
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