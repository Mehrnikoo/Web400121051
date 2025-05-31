<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .item-list { display: flex; flex-wrap: wrap; gap: 20px; }
        .item-card { border: 1px solid #ddd; padding: 15px; width: 200px; box-shadow: 2px 2px 5px #eee; }
        .item-card h3 { margin-top: 0; }
        .user-info { text-align: right; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="user-info">
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
            <a href="/web400121051/auth/logout">Logout</a>
        <?php endif; ?>
    </div>

    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    <hr>

    <?php if (empty($items)): ?>
        <p>No items in stock yet! Check back soon.</p>
    <?php else: ?>
        <div class="item-list">
            <?php foreach ($items as $item): ?>
                <div class="item-card">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p>Price: €<?php echo htmlspecialchars($item['price']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>