<?php
// Admin login processing
session_start();

if (isset($_POST['submitAdmin'])) {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "notesnest_db");
    if ($conn->connect_error) {
        die("Error: Database Not Connected");
    }

    // Get and sanitize form data
    $email = $conn->real_escape_string(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    // Use prepared statement for safety
    $stmt = $conn->prepare("SELECT user_id, email, user_name, password FROM tbl_user WHERE email = ? AND role_id = 1 LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Check password
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['userName'] = $row['user_name'];
            $_SESSION['admin_id'] = $row['user_id'];

            header('Location: index.php');
            exit;
        } else {
            $error = 'Incorrect password.';
        }
    } else {
        $error = 'Incorrect email or user not found.';
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>NotesNest - Admin Login</title>
    <link href="./Image/logo-svg.png" rel="icon" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect" />
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

        body { font-family: 'Newsreader', serif; }
    </style>
</head>

<body class="bg-[var(--background-color)] text-[var(--text-color)] antialiased">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md mx-auto">
            <!-- Login card -->
            <div class="bg-white rounded-2xl border border-[var(--border-color)] p-6 shadow-md">
                <div class="text-center mb-4">
                    <div class="inline-flex items-center gap-3 justify-center">
                        <svg class="h-8 w-8 text-[var(--text-color)]" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
                        </svg>
                        <h2 class="text-2xl font-bold">Admin Login</h2>
                    </div>
                    <p class="mt-2 text-sm text-[var(--placeholder-color)]">Welcome back. Please log in to your account.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="mb-4 rounded p-3 bg-red-50 border border-red-200 text-red-700 text-sm">
                        <strong class="font-semibold">Error:</strong>
                        <span class="block"><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <form method="post" action="" class="space-y-4" novalidate>
                    <div>
                        <label for="email" class="block text-sm font-medium">Email Address</label>
                        <input id="email" name="email" type="email" required autocomplete="email" placeholder="you@example.com" class="mt-1 block w-full rounded-xl border border-[var(--border-color)] bg-white p-3 text-base placeholder:text-[var(--placeholder-color)] focus:border-[var(--accent-color)] focus:outline-none focus:ring-1 focus:ring-[var(--accent-color)]" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium">Password</label>
                        <div class="relative mt-1">
                            <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••" class="block w-full rounded-xl border border-[var(--border-color)] bg-white p-3 pr-12 text-base placeholder:text-[var(--placeholder-color)] focus:border-[var(--accent-color)] focus:outline-none focus:ring-1 focus:ring-[var(--accent-color)]" />
                            <button type="button" id="togglePassword" aria-label="Show password" class="absolute right-3 top-1/2 -translate-y-1/2 text-sm px-2 py-1 rounded-md focus:outline-none">
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.94 17.94A10.94 10.94 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.97 10.97 0 012.46-4.09M3 3l18 18"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.88 9.88A3 3 0 0114.12 14.12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-[var(--text-color)] hover:underline">Forgot password?</a>
                        </div>
                    </div> -->

                    <div>
                        <button type="submit" name="submitAdmin" class="w-full rounded-full bg-[var(--primary-color)] py-3.5 text-base font-bold text-[var(--text-color)] transition hover:bg-[var(--accent-color)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-color)] focus:ring-offset-2">Log In</button>
                    </div>
                </form>

                <div class="mt-4 text-center text-xs text-[var(--placeholder-color)]">
                    <span>© NotesNest</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password toggle
        (function () {
            const pwd = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            toggle?.addEventListener('click', function () {
                if (!pwd) return;
                if (pwd.type === 'password') {
                    pwd.type = 'text';
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                    toggle.setAttribute('aria-label', 'Hide password');
                } else {
                    pwd.type = 'password';
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                    toggle.setAttribute('aria-label', 'Show password');
                }
            });

            // Simple client-side validation hint (keeps server-side authoritative)
            document.querySelector('form')?.addEventListener('submit', function (e) {
                const email = document.getElementById('email');
                if (email && !email.value.trim()) {
                    e.preventDefault();
                    email.focus();
                }
            });
        })();
    </script>
</body>

</html>
