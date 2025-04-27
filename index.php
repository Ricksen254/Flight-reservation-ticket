<?php include 'authentication/auth_logic.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login/Register | Flight Booking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- For mobile responsiveness -->
    <style>
        * {
            box-sizing: border-box;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 50, 0.7), rgba(0, 0, 70, 0.7)),
                url('images/take-off.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ffffff;
        }

        .container {
            width: 400px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            text-align: center;
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
            color: #ffcc00;
        }

        input,
        button {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input {
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            outline: none;
        }

        input:focus {
            background: #fff;
            border: 2px solid #ffcc00;
        }

        button {
            background: #004080;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .switch-form-text {
            margin-top: 15px;
            font-size: 14px;
            color: #ddd;
        }

        .switch-form-text span {
            color: #ffcc00;
            cursor: pointer;
            font-weight: bold;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .error-message,
        .success-message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }

        .error-message {
            background-color: rgba(255, 0, 0, 0.2);
            color: #ff3333;
        }

        .success-message {
            background-color: rgba(0, 255, 0, 0.2);
            color: #33cc33;
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <?php if (!empty($errors)) : ?>
            <div class="error-message"><?php echo $errors; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)) : ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <div id="login-section" class="form-section <?php echo (empty($success)) ? 'active' : ''; ?>">
            <h2>Login</h2>
            <form method="POST">
                <input type="hidden" name="action" value="login">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" minlength="5" required>
                <button type="submit">Login</button>
            </form>
            <div class="switch-form-text">
                Don't have an account? <span onclick="toggleForm('register')">Sign Up</span>
            </div>
        </div>

        <!-- Registration Form -->
        <div id="register-section" class="form-section <?php echo (!empty($success)) ? 'active' : ''; ?>">
            <h2>Sign Up</h2>
            <form method="POST">
                <input type="hidden" name="action" value="register">
                <input type="text" name="fullname" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="tel" name="phone_number" placeholder="Phone Number" pattern="[0-9+]+" required>
                <input type="password" name="password" placeholder="Create Password" minlength="8" required>
                <button type="submit">Register</button>
            </form>
            <div class="switch-form-text">
                Already have an account? <span onclick="toggleForm('login')">Login</span>
            </div>
        </div>
    </div>

    <script>
        function toggleForm(section) {
            document.getElementById('login-section').classList.remove('active');
            document.getElementById('register-section').classList.remove('active');
            document.getElementById(section + '-section').classList.add('active');
        }
    </script>

</body>

</html>