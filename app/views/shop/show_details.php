<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        /* Basic styling */
        body { font-family: sans-serif; margin: 20px; }
        .product-details { max-width: 700px; margin: auto; }
        .product-details img { max-width: 100%; height: auto; margin-bottom: 20px; } /* For when you add images */
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
<div class="comments-section" style="margin-top: 30px;">
            <h2>Comments</h2>
            <?php if (empty($comments)): ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                        <p>
                            <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                            <small style="color: #777;">(<?php echo date('M j, Y, g:i a', strtotime($comment['created_at'])); ?>)</small>
                        </p>
                        <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="add-comment-form" style="margin-top: 30px;">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <h3>Leave a Comment</h3>
                <form action="/web400121051/shop/addComment/<?php echo $item['id']; ?>" method="POST">
                    <textarea name="comment_text" rows="4" style="width: 100%; margin-bottom: 10px;" placeholder="Write your comment here..." required></textarea>
                    <button type="submit">Post Comment</button>
                </form>
            <?php else: ?>
                <p><a href="/web400121051/auth/login">Login</a> or <a href="/web400121051/auth/register">Register</a> to post a comment.</p>
            <?php endif; ?>
        </div>
        <hr style="margin-top:30px;">
        <p><a href="/web400121051/shop">Back to Shop</a></p>
    </div> </body>
</html>

    <div class="product-details">
        <h1><?php echo htmlspecialchars($item['name']); ?></h1>
        <hr>
        <?php if (!empty($item['image_filename'])): ?>
            <img src="/web400121051/public/uploads/items/<?php echo htmlspecialchars($item['image_filename']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="max-width:100%; height:auto; margin-bottom:20px; border:1px solid #ddd;">
        <?php else: ?>
            <p><em>No image available for this item.</em></p>
        <?php endif; ?>
        <p><strong>Price:</strong> €<?php echo htmlspecialchars($item['price']); ?></p>
        
        <h2>Description:</h2>
        <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
        
        <p><em>Product ID: <?php echo htmlspecialchars($item['id']); ?> (Added: <?php echo htmlspecialchars($item['created_at']); ?>)</em></p>
        <hr>
        
        <p><a href="/web400121051/shop">Back to Shop</a></p>
    </div>

</body>
</html>