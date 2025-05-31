<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin Login'; ?></title>
    <style>
        /* ... your existing styles ... */
        .form-container { /* Ensure this class exists if you copied my login.php styles */
            background-color: #fff; 
            padding: 20px; 
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
            width: 300px; /* Or whatever width your login box is */
            text-align: center; /* Center the contents of the box */
        }
        .register-link { /* Added a class for the new div for potential styling */
            margin-top: 15px; 
        }
    </style>
</head>
<body>
    <div class="login-container"> <h1>Admin Login</h1>
        
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="/web400121051/auth/processLogin" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="register-link">
            <p>Don't have an account? <a href="/web400121051/auth/register">Register here</a></p>
        </div>
        </div> </body>
</html>