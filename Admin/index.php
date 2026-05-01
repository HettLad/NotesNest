<?php
    session_start();

    if (!isset($_SESSION['admin_id'])) {
        // send a quick message then redirect
        header("Location: loginAdmin.php");
        exit;
    }

    // Single shared DB connection to avoid repeating connections
    $conn = new mysqli("localhost", "root", "", "notesnest_db");
    if ($conn->connect_error) {
        die("Error: Database Not Connected: " . $conn->connect_error);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect" />
    <link as="style" href="https://fonts.googleapis.com/css2?display=swap&amp;family=Public+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet" />
    <title>NotesNest - Admin</title>
    <link href="./Image/logo-svg.png" rel="icon" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss">
        :root {
            --background-light: #f5f5dc;
            --background-paper: #fdfbf7;
            --text-brown: #5d4037;
            --text-dark-brown: #3e2723;
            --accent-red: #8B0000;
            --accent-light-red: #a52a2a;
            --border-color: #d7ccc8;
        }

        /* small tweak for mobile scrollable table cells */
        .table-cell-truncate {
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>

<body class="bg-[var(--background-light)]" style='font-family: "Public Sans", sans-serif;'>

    <div class="min-h-screen md:flex">
        <!-- Mobile top bar (visible on small screens) -->
        <div class="md:hidden bg-[var(--background-paper)] border-b border-[var(--border-color)] p-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button id="mobile-open" aria-label="Open menu" class="p-2 rounded-md hover:bg-[#efebe9]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--text-dark-brown)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-md flex items-center justify-center">
                        <svg class="h-6 w-6 text-[var(--primary-color)]" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h1 class="text-[var(--text-dark-brown)] font-bold">NotesNest</h1>
                </div>
            </div>
            <a href="LogOutAdmin.php" class="text-sm text-[var(--accent-red)]">Log out</a>
        </div>

        <!-- Sidebar (desktop) -->
        <aside id="sidebar" class="fixed top-0 left-0 w-72 h-full bg-[var(--background-paper)] border-r border-[var(--border-color)] p-6 flex-col justify-between hidden md:flex z-30">
            <div class="flex flex-col gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-md flex items-center justify-center">
                        <svg class="h-8 w-8 text-[var(--primary-color)]" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h1 class="text-[var(--text-dark-brown)] text-lg font-bold">NotesNest</h1>
                </div>
                <nav class="flex flex-col gap-2">
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-md bg-[#efebe9] text-[var(--accent-red)]" href="#">
                        <svg fill="currentColor" height="20px" viewBox="0 0 256 256" width="20px" xmlns="http://www.w3.org/2000/svg"><path d="M224,115.55V208a16,16,0,0,1-16,16H168a16,16,0,0,1-16-16V168a8,8,0,0,0-8-8H112a8,8,0,0,0-8,8v40a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V115.55a16,16,0,0,1,5.17-11.78l80-75.48.11-.11a16,16,0,0,1,21.53,0,1.14,1.14,0,0,0,.11.11l80,75.48A16,16,0,0,1,224,115.55Z"></path></svg>
                        <p class="text-sm font-semibold">Dashboard</p>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-md text-[var(--text-brown)] hover:bg-[#efebe9] hover:text-[var(--text-dark-brown)] transition-colors" href="userManagment.php">
                        <svg fill="currentColor" height="20px" viewBox="0 0 256 256" width="20px" xmlns="http://www.w3.org/2000/svg"><path d="M117.25,157.92a60,60,0,1,0-66.5,0A95.83,95.83,0,0,0,3.53,195.63a8,8,0,1,0,13.4,8.74,80,80,0,0,1,134.14,0,8,8,0,0,0,13.4-8.74A95.83,95.83,0,0,0,117.25,157.92ZM40,108a44,44,0,1,1,44,44A44.05,44.05,0,0,1,40,108Zm210.14,98.7a8,8,0,0,1-11.07-2.33A79.83,79.83,0,0,0,172,168a8,8,0,0,1,0-16,44,44,0,1,0-16.34-84.87,8,8,0,1,1-5.94-14.85,60,60,0,0,1,55.53,105.64,95.83,95.83,0,0,1,47.22,37.71A8,8,0,0,1,250.14,206.7Z"></path></svg>
                        <p class="text-sm font-medium">User Management</p>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-md text-[var(--text-brown)] hover:bg-[#efebe9] hover:text-[var(--text-dark-brown)] transition-colors" href="contentManagment.php">
                        <svg fill="currentColor" height="20px" viewBox="0 0 256 256" width="20px" xmlns="http://www.w3.org/2000/svg"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216Z"></path></svg>
                        <p class="text-sm font-medium">Content Management</p>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-md text-[var(--text-brown)] hover:bg-[#efebe9] hover:text-[var(--text-dark-brown)] transition-colors" href="LogOutAdmin.php">
                        <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0z" fill="none"/><path d="M13 3c-1.1 0-2 .9-2 2v4h2V5h7v14h-7v-4h-2v4c0 1.1.9 2 2 2h7c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-7zm-2.59 7.41L7 13h8v-2H7l3.41-3.41L9 6l-6 6 6 6 1.41-1.41z"/></svg>
                        <p class="text-sm font-medium">Log Out</p>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Mobile sidebar overlay -->
        <div id="mobile-sidebar" class="fixed inset-0 z-40 md:hidden hidden">
            <div id="mobile-backdrop" class="absolute inset-0 bg-black/40" aria-hidden="true"></div>
            <div class="absolute left-0 top-0 bottom-0 w-64 bg-[var(--background-paper)] p-5 border-r border-[var(--border-color)] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-[var(--text-dark-brown)]">NotesNest</h2>
                    <button id="mobile-close" aria-label="Close menu" class="p-1 rounded-md hover:bg-[#efebe9]"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
                <nav class="flex flex-col gap-2">
                    <a class="px-3 py-2 rounded-md bg-[#efebe9] text-[var(--accent-red)]" href="#">Dashboard</a>
                    <a class="px-3 py-2 rounded-md hover:bg-[#efebe9]" href="userManagment.php">User Management</a>
                    <a class="px-3 py-2 rounded-md hover:bg-[#efebe9]" href="contentManagment.php">Content Management</a>
                    <a class="px-3 py-2 rounded-md hover:bg-[#efebe9]" href="LogOutAdmin.php">Log Out</a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 md:ml-72 p-4 md:p-8">
            <div class="max-w-5xl mx-auto">
                <header class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl md:text-4xl font-bold text-[var(--text-dark-brown)] tracking-tight">Admin Dashboard</h1>
                </header>

                <section class="mb-6">
                    <h2 class="text-2xl font-bold text-[var(--text-dark-brown)] mb-4">Platform Overview</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-[var(--background-paper)] rounded-lg p-4 md:p-6 border border-[var(--border-color)]">
                            <p class="text-[var(--text-brown)] text-sm font-medium mb-2">Total Users</p>
                            <?php
                                $q = "select count(user_id) as count from tbl_user where role_id = 2";
                                $res = $conn->query($q);
                                $count = 0;
                                if ($res) {
                                    $row = $res->fetch_assoc();
                                    $count = $row['count'] ?? 0;
                                }
                            ?>
                            <p class='text-[var(--text-dark-brown)] text-3xl md:text-4xl font-bold'><?php echo $count; ?></p>
                        </div>
                        <div class="bg-[var(--background-paper)] rounded-lg p-4 md:p-6 border border-[var(--border-color)]">
                            <p class="text-[var(--text-brown)] text-sm font-medium mb-2">Total Notes</p>
                            <?php
                                $q = "select count(note_id) as count from tbl_notes";
                                $res = $conn->query($q);
                                $countNotes = 0;
                                if ($res) {
                                    $row = $res->fetch_assoc();
                                    $countNotes = $row['count'] ?? 0;
                                }
                            ?>
                            <p class='text-[var(--text-dark-brown)] text-3xl md:text-4xl font-bold'><?php echo $countNotes; ?></p>
                        </div>
                        <div class="bg-[var(--background-paper)] rounded-lg p-4 md:p-6 border border-[var(--border-color)]">
                            <p class="text-[var(--text-brown)] text-sm font-medium mb-2">Total Subjects</p>
                            <?php
                                $q = "select count(name) as count from tbl_subject";
                                $res = $conn->query($q);
                                $countSubjects = 0;
                                if ($res) {
                                    $row = $res->fetch_assoc();
                                    $countSubjects = $row['count'] ?? 0;
                                }
                            ?>
                            <p class='text-[var(--text-dark-brown)] text-3xl md:text-4xl font-bold'><?php echo $countSubjects; ?></p>
                        </div>
                    </div>
                </section>

                <section class="mb-6">
                    <h2 class="text-2xl font-bold text-[var(--text-dark-brown)] mb-4">New Users</h2>
                    <div class="bg-[var(--background-paper)] rounded-lg border border-[var(--border-color)] overflow-auto">
                        <table class="w-full text-left min-w-[480px] md:min-w-full">
                            <thead class="bg-[#efebe9] border-b border-[var(--border-color)]">
                                <tr>
                                    <th class="px-4 py-3 text-sm font-semibold text-[var(--text-brown)]">User</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-[var(--text-brown)]">Email</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-color)]">
                                <?php
                                    $q = "select user_name,email from tbl_user where role_id = 2 order by createdAt desc limit 5";
                                    $res = $conn->query($q);
                                    if ($res) {
                                        while ($row = $res->fetch_assoc()) {
                                            $userName = htmlspecialchars($row['user_name']);
                                            $email = htmlspecialchars($row['email']);
                                            echo "<tr>\n";
                                            echo "  <td class='px-4 py-3 table-cell-truncate'><p class='text-sm font-medium text-[var(--text-dark-brown)]'>".$userName."</p></td>\n";
                                            echo "  <td class='px-4 py-3 table-cell-truncate text-sm text-[var(--text-brown)]'>".$email."</td>\n";
                                            echo "</tr>\n";
                                        }
                                    } else {
                                        echo "<tr><td class='px-4 py-3' colspan='2'>No users found.</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </section>

            </div>
        </main>
    </div>

    <script>
        // Mobile sidebar toggle
        const mobileOpen = document.getElementById('mobile-open');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const mobileClose = document.getElementById('mobile-close');
        const mobileBackdrop = document.getElementById('mobile-backdrop');

        function openMobile() {
            mobileSidebar.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeMobile() {
            mobileSidebar.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (mobileOpen) mobileOpen.addEventListener('click', openMobile);
        if (mobileClose) mobileClose.addEventListener('click', closeMobile);
        if (mobileBackdrop) mobileBackdrop.addEventListener('click', closeMobile);

        // ensure sidebar visibility on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                closeMobile();
            }
        });
    </script>

</body>

</html>
