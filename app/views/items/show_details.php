<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    </head>
<body>
    <h1><?php echo htmlspecialchars($item['name']); ?></h1>
    <hr>
    <p><strong>Price:</strong> €<?php echo htmlspecialchars($item['price']); ?></p>
    <p><strong>Description:</strong></p>
    <p><?php echo nl2br(htmlspecialchars($item['description'])); // nl2br converts newlines to <br> ?></p>
    <p><em>Added on:</em> <?php echo htmlspecialchars($item['created_at']); ?></p>
    <hr>
    <p>
        <a href="/web400121051/items">Back to List</a> |
        <a href="/web400121051/items/edit/<?php echo $item['id']; ?>">Edit This Item</a>
    </p>

</body>
</html>