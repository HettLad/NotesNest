<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "notesnest_db");
if ($conn->connect_error) {
    die("Error: Database Not Connected");
}

$error = "";
$success = "";

// Login Logic
if (isset($_POST["signInUser"])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT user_id, name, email, user_name, password, isBlocked FROM tbl_user WHERE email = '$email' AND role_id = 2";
    $result = $conn->query($query);

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($row['isBlocked'] == 1) {
            $error = "This user is blocked. Please contact support.";
        } elseif (password_verify($password, $row["password"])) {
            $_SESSION["email"] = $row["email"];
            $_SESSION["userName"] = $row["user_name"];
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["name"] = $row["name"];
            header("Location: index.php");
            exit;
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "User not found or invalid credentials.";
    }
}

// Registration Logic
if (isset($_POST['signUp'])) {
    $name = $conn->real_escape_string($_POST['cName']);
    $email = $conn->real_escape_string($_POST['cEmail']);
    $userName = $conn->real_escape_string($_POST['cUserName']);
    $phoneNo = $conn->real_escape_string($_POST['cPhone']);
    $password = password_hash($_POST['cPassword'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT user_id FROM tbl_user WHERE user_name = '$userName' OR email = '$email'");
    if ($check->num_rows > 0) {
        $error = "Username or Email already taken.";
    } else {
        $sql = "INSERT INTO tbl_user(name, user_name, phone_no, email, password, role_id) VALUES('$name', '$userName', '$phoneNo', '$email', '$password', 2)";
        if ($conn->query($sql)) {
            $success = "Account created successfully! Please sign in.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login / Register - NotesNest</title>
    <link href="style.css" rel="stylesheet" />
    <style>
        .error-box {
            color: #fff;
            background-color: #dc3545;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2);
        }
        .success-box {
            color: #fff;
            background-color: #28a745;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }
        .toggle-form {
            color: var(--nn-primary);
            font-weight: 800;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>

<body class="nn-auth-page">
    <!-- Login Card -->
    <div class="nn-auth-card" id="loginCard">
        <div class="nn-auth-header">
            <h2 style="font-family: 'Merriweather', serif;">Welcome Back</h2>
            <p>Login to access your personalized notes library.</p>
        </div>

        <?php if ($error): ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-box"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div style="margin-bottom: 5px;">
                <label style="display: block; font-weight: 700; margin-bottom: 10px; font-size: 14px; color: #333;">EMAIL ADDRESS</label>
                <input class="nn-input" type="email" name="email" placeholder="e.g. john@example.com" required />
            </div>
            <div style="margin-bottom: 5px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <label style="font-weight: 700; font-size: 14px; color: #333;">PASSWORD</label>
                    <a href="reset_passowrd.php" style="font-size: 13px; color: var(--nn-secondary); font-weight: 700;">Forgot Password?</a>
                </div>
                <input class="nn-input" type="password" name="password" placeholder="Enter your password" required />
            </div>
            <button type="submit" name="signInUser" class="nn-btn" style="width: 100%; padding: 16px; border-radius: 14px; font-size: 16px; margin-top: 10px; font-weight: 800; background: var(--nn-primary);">
                Login to Your Account
            </button>
        </form>

        <div class="nn-auth-footer">
            New to NotesNest? <span class="toggle-form" onclick="toggleAuth()">Create an Account</span>
        </div>
    </div>

    <!-- Register Card (Hidden by default) -->
    <div class="nn-auth-card" id="registerCard" style="display: none; max-width: 550px;">
        <div class="nn-auth-header">
            <h2 style="font-family: 'Merriweather', serif;">Create Account</h2>
            <p>Join our community of students and start sharing knowledge.</p>
        </div>

        <form action="" method="post">
            <div style="margin-bottom: 5px;">
                <label style="display: block; font-weight: 700; margin-bottom: 10px; font-size: 14px; color: #333;">FULL NAME</label>
                <input class="nn-input" type="text" name="cName" placeholder="e.g. John Doe" required />
            </div>
            <div style="margin-bottom: 5px;">
                <label style="display: block; font-weight: 700; margin-bottom: 10px; font-size: 14px; color: #333;">USERNAME</label>
                <input class="nn-input" type="text" name="cUserName" placeholder="e.g. johndoe123" required />
            </div>
            <div style="margin-bottom: 5px;">
                <label style="display: block; font-weight: 700; margin-bottom: 10px; font-size: 14px; color: #333;">EMAIL ADDRESS</label>
                <input class="nn-input" type="email" name="cEmail" placeholder="e.g. john@example.com" required />
            </div>
            <div style="margin-bottom: 5px;">
                <label style="display: block; font-weight: 700; margin-bottom: 10px; font-size: 14px; color: #333;">PHONE NUMBER</label>
                <input class="nn-input" type="tel" name="cPhone" placeholder="e.g. 9876543210" minlength="10" maxlength="10" required />
            </div>
            <div style="margin-bottom: 5px;">
                <label style="display: block; font-weight: 700; margin-bottom: 10px; font-size: 14px; color: #333;">CREATE PASSWORD</label>
                <input class="nn-input" type="password" name="cPassword" placeholder="Minimum 8 characters" minlength="8" required />
            </div>
            <button type="submit" name="signUp" class="nn-btn" style="width: 100%; padding: 16px; border-radius: 14px; font-size: 16px; margin-top: 10px; font-weight: 800; background: var(--nn-primary);">
                Complete Registration
            </button>
        </form>

        <div class="nn-auth-footer">
            Already have an account? <span class="toggle-form" onclick="toggleAuth()">Login Now</span>
        </div>
    </div>

    <script>
        function toggleAuth() {
            const loginCard = document.getElementById('loginCard');
            const registerCard = document.getElementById('registerCard');
            if (loginCard.style.display === 'none') {
                loginCard.style.display = 'block';
                registerCard.style.display = 'none';
            } else {
                loginCard.style.display = 'none';
                registerCard.style.display = 'block';
            }
        }

        // Show registration if there was a registration error/success
        <?php if (isset($_POST['signUp'])): ?>
            toggleAuth();
        <?php endif; ?>
    </script>
</body>

</html>