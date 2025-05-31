<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Register'; ?></title>
    <style> /* Using same styles as login for now */
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 80vh; background-color: #f4f4f4; }
        .form-container { background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"], input[type="email"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 3px; }
        button { padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .error-message { color: red; margin-bottom: 10px; }
        .success-message { color: green; margin-bottom: 10px; }
        .login-link { margin-top: 15px; text-align: center; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Register New Account</h1>

        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="/web400121051/auth/processRegistration" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirm">Confirm Password:</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="/web400121051/auth/login">Login here</a></p>
        </div>
    </div>
</body>
</html>