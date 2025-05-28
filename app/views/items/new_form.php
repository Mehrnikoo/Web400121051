<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Add New Item'; ?></title>
    </head>
<body>
    <h1><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Add New Item'; ?></h1>

    <form action="/web400121051/items/create" method="POST">
        <div>
            <label for="name">Item Name:</label><br>
            <input type="text" id="name" name="name" required>
        </div>
        <br>
        <div>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea>
        </div>
        <br>
        <div>
            <label for="price">Price (€):</label><br>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>
        <br>
        <div>
            <button type="submit">Add Item</button>
        </div>
    </form>

    <p><a href="/web400121051/items">Back to List</a></p>

</body>
</html>