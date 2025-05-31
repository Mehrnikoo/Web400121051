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
        <?php else: ?>
            <a href="/web400121051/auth/login">Login</a> |
            <a href="/web400121051/auth/register">Register</a>
        <?php endif; ?>
    </div>

    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    <hr>

    <?php if (empty($items)): ?>
        <p>No items in stock yet! Check back soon.</p>
    <?php else: ?>
          <?php foreach ($items as $item): ?>
    <div class="item-card">
        <h3>
            <a href="/web400121051/shop/show/<?php echo $item['id']; ?>">
                <?php echo htmlspecialchars($item['name']); ?>
            </a>
        </h3>
        <p>Price: €<?php echo htmlspecialchars($item['price']); ?></p>
        <p>
            <?php 
            // Show a snippet of the description
            $descriptionSnippet = substr(strip_tags($item['description']), 0, 100);
            echo htmlspecialchars($descriptionSnippet);
            if (strlen(strip_tags($item['description'])) > 100) {
                echo "...";
            }
            ?>
        </p>
        <p><a href="/web400121051/shop/show/<?php echo $item['id']; ?>">View Details</a></p>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    
    
  
</body>
</html>