<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Admin - Item List'); ?></title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions form { display: inline; margin-left: 5px; }
        .item-image { max-width: 60px; max-height: 60px; object-fit: cover; }
        .add-item-link { margin-bottom: 20px; display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .add-item-link:hover { background-color: #0056b3; }
        .user-info { text-align: right; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="user-info">
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['user_role']); ?>)!
            <a href="/web400121051/auth/logout">Logout</a>
        <?php else: ?>
            <a href="/web400121051/auth/login">Login</a> |
            <a href="/web400121051/auth/register">Register</a>
        <?php endif; ?>
    </div>

    <h1><?php echo htmlspecialchars($pageTitle ?? 'Admin - Item List'); ?></h1>

    <a href="/web400121051/items/new" class="add-item-link">Add New Item</a>

    <?php if (empty($items)): ?>
        <p>No items found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['id']); ?></td>
                        <td>
                            <?php if (!empty($item['image_filename'])): ?>
                                <img src="/web400121051/public/uploads/items/<?php echo htmlspecialchars($item['image_filename']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>€<?php echo htmlspecialchars(number_format((float)$item['price'], 2, '.', '')); ?></td>
                        <td><?php echo htmlspecialchars(substr($item['description'] ?? '', 0, 50)); if(strlen($item['description'] ?? '') > 50) echo "..."; ?></td>
                        <td><?php echo htmlspecialchars($item['created_at']); ?></td>
                        <td class="actions">
                            <a href="/web400121051/items/show/<?php echo $item['id']; ?>">Details</a> |
                            <a href="/web400121051/items/edit/<?php echo $item['id']; ?>">Edit</a>
                            <form action="/web400121051/items/delete/<?php echo $item['id']; ?>" method="POST" style="display: inline;">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this item: <?php echo htmlspecialchars(addslashes($item['name'])); ?>?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>