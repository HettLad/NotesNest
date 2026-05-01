<?php
session_start();

if (isset($_REQUEST["submit"])) {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "notesnest_db");
    if ($conn->connect_error) {
        die("Error: Database Not Connected");
    }

    // Get form data
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];

    // Query to fetch user by email
    $query = "SELECT user_id, email, user_name, password FROM tbl_user WHERE email = '$email' AND role_id = 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Check password
        if (password_verify($password, $row["password"])) {
            $_SESSION["email"] = $row["email"];
            $_SESSION["userName"] = $row["user_name"];
            $_SESSION["user_id"] = $row["user_id"];

            // ✅ REDIRECT (use correct path and fix slashes)
            header("Location: dasboard.php");
            exit;
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('Incorrect email or user not found.');</script>";
    }

    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>NotesNest - Admin Login</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&amp;display=swap" rel="stylesheet" />
    <style type="text/tailwindcss">
        :root {
        --primary-color: #f3e6dc;
        --background-color: #fbfaf8;
        --text-color: #1c1917;
        --border-color: #e3e0dd;
        --placeholder-color: #81746a;
        --accent-color: #d1c0b1;
      }
      body {
        font-family: 'Newsreader', serif;
      }
    </style>
</head>

<body class="bg-[var(--background-color)] text-[var(--text-color)]">
    <div class="flex min-h-screen items-center justify-center">
        <div class="w-full max-w-md rounded-2xl border border-[var(--border-color)] bg-white p-8 shadow-sm">
            <div class="text-center">
                <div class="mb-4 inline-flex items-center gap-3">
                    <svg class="h-8 w-8 text-[var(--text-color)]" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
                    </svg>
                    <h1 class="text-3xl font-bold tracking-tight">NotesNest</h1>
                </div>
                <h2 class="text-2xl font-semibold tracking-tight">Admin Login</h2>
                <p class="mt-2 text-base text-[var(--placeholder-color)]">Welcome back. Please log in to your account.</p>
            </div>
            <form class="mt-8 space-y-6" method="post" action="Login.php">
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="email">Email Address</label>
                    <input autocomplete="email" class="w-full rounded-xl border border-[var(--border-color)] bg-white p-4 text-base placeholder:text-[var(--placeholder-color)] focus:border-[var(--accent-color)] focus:outline-none focus:ring-1 focus:ring-[var(--accent-color)]" id="email" name="email" placeholder="you@example.com" required="" type="email" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="password">Password</label>
                    <input autocomplete="current-password" class="w-full rounded-xl border border-[var(--border-color)] bg-white p-4 text-base placeholder:text-[var(--placeholder-color)] focus:border-[var(--accent-color)] focus:outline-none focus:ring-1 focus:ring-[var(--accent-color)]" id="password" name="password" placeholder="••••••••" required="" type="password" />
                </div>
                <div>
                    <input class="w-full cursor-pointer rounded-full bg-[var(--primary-color)] py-3.5 text-base font-bold text-[var(--text-color)] transition-colors hover:bg-[var(--accent-color)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-color)] focus:ring-offset-2" type="submit" value="Log In" name="submit">
                </div>
            </form>
    </div>

</body>

</html>