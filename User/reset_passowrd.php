<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - NotesNest</title>
    <link href="style.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #fdf2f2 0%, #fff 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .reset-card {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            padding: 50px;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            text-align: center;
            border: 1px solid rgba(0,0,0,0.02);
        }

        h1 {
            font-size: 32px;
            font-weight: 900;
            color: #b40808;
            margin-bottom: 10px;
            font-family: 'Merriweather', serif;
        }

        p {
            color: #666;
            font-size: 15px;
            margin-bottom: 30px;
        }

        input {
            width: 100%;
            padding: 14px 20px;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            margin-bottom: 20px;
            font-family: inherit;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #fafafa;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #b40808;
            background: #fff;
            box-shadow: 0 8px 20px rgba(180, 8, 8, 0.06);
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: #b40808;
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(180, 8, 8, 0.2);
        }

        .submit-btn:hover {
            background: #9a0707;
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(180, 8, 8, 0.3);
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #666;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #b40808;
        }

        .nn-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: #fff;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            font-weight: 700;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="reset-card">
        <h1>Reset Password</h1>
        <p>Enter your registered email and your new secure password.</p>

        <?php
        if (isset($_POST["resetPassword"])) {
            $conn = new mysqli("localhost", "root", "", "notesnest_db");
            if ($conn->connect_error) {
                die("Error: Database Not Connected");
            }

            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $query = "SELECT * FROM tbl_user WHERE email = '$email' AND role_id = 2";
            $res = $conn->query($query);
            if ($res->num_rows != 1) {
                echo '<div class="nn-toast" style="border-left: 5px solid #b40808;">Error: Invalid email address</div>';
            } else {
                $qryUpdate = "UPDATE tbl_user SET password = '$password' WHERE email = '$email' AND role_id = 2";
                if ($conn->query($qryUpdate)) {
                    echo '<div class="nn-toast" style="border-left: 5px solid #0ca678;">Success: Password reset successfully! Redirecting...</div>';
                    echo '<script>setTimeout(() => window.location.href="login.php", 2500);</script>';
                }
            }
        }
        ?>

        <form action="" method="post">
            <input type="email" placeholder="Email Address" name="email" required />
            <input type="password" placeholder="New Password" name="password" required minlength="8" />
            <button class="submit-btn" type="submit" name="resetPassword">Reset Password</button>
        </form>

        <a class="back-link" href="login.php">← Back to Login</a>
    </div>

    <script>
        // Auto-hide toast
        setTimeout(() => {
            const toast = document.querySelector('.nn-toast');
            if(toast) toast.style.display = 'none';
        }, 4000);
    </script>
</body>

</html>