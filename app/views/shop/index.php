<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        /* .item-list { display: flex; flex-wrap: wrap; gap: 20px; } */
        .item-list {
    display: flex;         /* Enables Flexbox */
    flex-wrap: wrap;       /* Allows items to wrap to the next line */
    gap: 20px;             /* Adds space between the item cards */
    justify-content: flex-start; /* Aligns items to the start. You can also use 'center', 'space-around', etc. */
}
        .item-card { border: 1px solid #ddd; padding: 15px; width: 200px; box-shadow: 2px 2px 5px #eee; }
        .item-card h3 { margin-top: 0; }
        .user-info { text-align: right; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="user-info">
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
        <a href="/web400121051/shop/foryou">For You</a> | <a href="/web400121051/auth/logout">Logout</a>
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
    <div class="item-list"> 
        <?php foreach ($items as $item): ?>
            <div class="item-card">
                <?php if (!empty($item['image_filename'])): ?>
                    <img src="/web400121051/public/uploads/items/<?php echo htmlspecialchars($item['image_filename']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width:100%; height:auto; max-height:150px; object-fit:cover; margin-bottom:10px;">
                <?php else: ?>
                    <div style="width:100%; height:150px; background-color:#eee; display:flex; align-items:center; justify-content:center; margin-bottom:10px; text-align:center;">No Image</div>
                <?php endif; ?>
                <h3>
                    <a href="/web400121051/shop/show/<?php echo $item['id']; ?>">
                        <?php echo htmlspecialchars($item['name']); ?>
                    </a>
                </h3>
                <p>Price: €<?php echo htmlspecialchars($item['price']); ?></p>
                <p>
                    <?php
                    $descriptionSnippet = substr(strip_tags($item['description']), 0, 50);
                    echo htmlspecialchars($descriptionSnippet);
                    if (strlen(strip_tags($item['description'])) > 50) {
                        echo "...";
                    }
                    ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div> <?php endif; ?>
</body>
</html>