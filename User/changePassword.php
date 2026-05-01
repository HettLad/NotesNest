<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Change Password - NotesNest</title>
    <link href="style.css" rel="stylesheet" />
    <style>
        .msg {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .msg-error { background-color: #dc3545; color: #fff; }
        .msg-success { background-color: #28a745; color: #fff; }
    </style>
</head>

<body class="nn-auth-page">
    <div class="nn-auth-card">
        <div class="nn-auth-header">
            <h2 style="font-family: 'Merriweather', serif;">Account Security</h2>
            <p>Choose a strong password to keep your account safe.</p>
        </div>

        <?php
        if (isset($_POST["changePassword"])) {
            $conn = new mysqli("localhost", "root", "", "notesnest_db");
            if ($conn->connect_error) {
                die("Error: Database Not Connected");
            }

            $oldPassword = $_POST['oldPassword'];
            $newPassword = $_POST['password'];
            $cnfPassword = $_POST['cnfPassword'];
            $email = $_SESSION['email'];

            $query = "SELECT password FROM tbl_user WHERE email = '$email' AND role_id = 2";
            $res = $conn->query($query);
            $row = $res->fetch_assoc();

            if ($newPassword != $cnfPassword) {
                echo '<div class="msg msg-error">Passwords do not match.</div>';
            } elseif (password_verify($oldPassword, $row['password'])) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $conn->query("UPDATE tbl_user SET password = '$hashedPassword' WHERE email = '$email'");
                echo '<div class="msg msg-success">Password changed successfully! Redirecting...</div>';
                echo '<script>setTimeout(() => window.location.href="user_Profile.php", 2000);</script>';
            } else {
                echo '<div class="msg msg-error">Incorrect old password.</div>';
            }
        }
        ?>

        <form action="" method="post">
            <div style="margin-bottom: 5px;">
                <label style="display: block; font-weight: 700; margin-bottom: 10px; font-size: 13px; color: #333;">CURRENT PASSWORD</label>
                <input class="nn-input" type="password" name="oldPassword" placeholder="Enter current password" required />
            </div>
            <div style="margin-bottom: 5px;">
                <label style="display: block; font-weight: 700; margin-bottom: 10px; font-size: 13px; color: #333;">NEW PASSWORD</label>
                <input class="nn-input" type="password" name="password" placeholder="Min. 8 characters" minlength="8" required />
            </div>
            <div style="margin-bottom: 5px;">
                <label style="display: block; font-weight: 700; margin-bottom: 10px; font-size: 13px; color: #333;">CONFIRM NEW PASSWORD</label>
                <input class="nn-input" type="password" name="cnfPassword" placeholder="Repeat new password" minlength="8" required />
            </div>
            <button type="submit" name="changePassword" class="nn-btn" style="width: 100%; padding: 16px; border-radius: 14px; font-size: 16px; margin-top: 20px; font-weight: 800; background: var(--nn-primary);">
                Update Password Now
            </button>
        </form>

        <div class="nn-auth-footer">
            <a href="user_Profile.php" style="color: var(--nn-secondary); font-weight: 700;">← Back to My Profile</a>
        </div>
    </div>
</body>

</html>