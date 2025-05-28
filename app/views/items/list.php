<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    </head>
<body>
    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>

    <a href="/web400121051/items/new">Add New Item</a>
    <hr>

    <?php if (empty($items)): ?>
        <p>No items in stock yet!</p>
    <?php else: ?>
        <ul>
            <?php foreach ($items as $item): ?>
                <li>
                    <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                    (€<?php echo htmlspecialchars($item['price']); ?>) -
                    <a href="/web400121051/items/show/<?php echo $item['id']; ?>">Details</a> |
                    <a href="/web400121051/items/edit/<?php echo $item['id']; ?>">Edit</a> |
                    <a href="/web400121051/items/delete/<?php echo $item['id']; ?>">Delete</a> </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>