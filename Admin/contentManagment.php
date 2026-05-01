<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: LoginAdmin.php");
    exit;
}

// Single DB connection
$conn = new mysqli("localhost", "root", "", "notesnest_db");
if ($conn->connect_error) {
    die("Error: Database Not Connected. " . $conn->connect_error);
}

// Handle Add / Update Subject
$subjectMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit']) || isset($_POST['update'])) {
        $subjectName = trim($_POST['SubjectName'] ?? '');
        $updateName = trim($_POST['UpdateName'] ?? '');

        // Validate subject name
        if ($subjectName === '') {
            $subjectMessage = 'Please enter subject name.';
        } else {
            if (!isset($_FILES['SubjectImage']) || $_FILES['SubjectImage']['error'] !== UPLOAD_ERR_OK) {
                $subjectMessage = 'Please upload a valid image.';
            } else {
                $fileTmp = $_FILES['SubjectImage']['tmp_name'];
                $fileName = basename($_FILES['SubjectImage']['name']);
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp','gif'];
                if (!in_array($ext, $allowed)) {
                    $subjectMessage = 'Only image files (jpg, png, webp, gif) are allowed.';
                } else {
                    $newFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
                    $relativePath = 'Image/img/' . $newFileName;
                    $uploadDir = __DIR__ . '/Image/img/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $dest = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmp, $dest)) {
                        if (isset($_POST['submit'])) {
                            $stmt = $conn->prepare("INSERT INTO tbl_subject (name, img) VALUES (?, ?)");
                            $stmt->bind_param('ss', $subjectName, $relativePath);
                            if ($stmt->execute()) $subjectMessage = 'Subject added successfully.'; else $subjectMessage = 'Error adding subject.';
                            $stmt->close();
                        } else {
                            // update
                            $stmt = $conn->prepare("UPDATE tbl_subject SET name = ?, img = ? WHERE name = ?");
                            $stmt->bind_param('sss', $updateName, $relativePath, $subjectName);
                            if ($stmt->execute()) $subjectMessage = 'Subject updated successfully.'; else $subjectMessage = 'Error updating subject.';
                            $stmt->close();
                        }
                    } else {
                        $subjectMessage = 'Failed to move uploaded file.';
                    }
                }
            }
        }
    }
}

// Fetch subjects
$subjects = [];
$subRes = $conn->query("SELECT subject_id, name, img FROM tbl_subject ORDER BY name ASC");
if ($subRes) while ($r = $subRes->fetch_assoc()) $subjects[] = $r;

// Fetch notes with joins
$notes = [];
$noteQry = "SELECT n.*, u.user_name, s.name AS subject_name FROM tbl_notes n LEFT JOIN tbl_user u ON n.user_id = u.user_id LEFT JOIN tbl_subject s ON n.subject_id = s.subject_id ORDER BY n.createdAt DESC";
$noteRes = $conn->query($noteQry);
if ($noteRes) while ($r = $noteRes->fetch_assoc()) $notes[] = $r;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link crossorigin href="https://fonts.gstatic.com/" rel="preconnect" />
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
            --input-bg: #fffbf7;
            --text-secondary: #8c786c;
            --text-primary: #5a4236;
        }
        .truncate-ellipsis { max-width: 240px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>

<body class="bg-[var(--background-light)]" style='font-family: "Public Sans", sans-serif;'>
    <div class="min-h-screen md:flex">
        <!-- Mobile top bar -->
        <div class="md:hidden bg-[var(--background-paper)] border-b border-[var(--border-color)] p-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button id="mobile-open" aria-label="Open menu" class="p-2 rounded-md hover:bg-[#efebe9]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--text-dark-brown)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-md flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h1 class="text-[var(--text-dark-brown)] font-bold">NotesNest</h1>
                </div>
            </div>
            <a href="LogOutAdmin.php" class="text-sm text-[var(--accent-red)]">Log out</a>
        </div>

        <!-- Sidebar -->
        <aside id="sidebar" class="hidden md:flex fixed top-0 left-0 w-72 h-screen bg-[var(--background-paper)] border-r border-[var(--border-color)] p-6 flex-col justify-between overflow-y-auto z-30">
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
    <a class="flex items-center gap-3 px-3 py-2.5 rounded-md hover:bg-[#efebe9] text-[var(--text-brown)]" href="index.php">
        <svg fill="currentColor" height="20" width="20" viewBox="0 0 256 256"><path d="M224,115.55V208a16,16,0,0,1-16,16H168a16,16,0,0,1-16-16V168a8,8,0,0,0-8-8H112a8,8,0,0,0-8,8v40a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V115.55a16,16,0,0,1,5.17-11.78l80-75.48a16,16,0,0,1,21.53,0l80,75.48A16,16,0,0,1,224,115.55Z"></path></svg>
        <p class="text-sm font-semibold">Dashboard</p>
    </a>

    <a class="flex items-center gap-3 px-3 py-2.5 rounded-md hover:bg-[#efebe9] text-[var(--text-brown)]" href="userManagment.php">
        <svg fill="currentColor" height="20" width="20" viewBox="0 0 256 256"><path d="M117.25,157.92a60,60,0,1,0-66.5,0A95.83,95.83,0,0,0,3.53,195.63a8,8,0,1,0,13.4,8.74,80,80,0,0,1,134.14,0,8,8,0,0,0,13.4-8.74A95.83,95.83,0,0,0,117.25,157.92ZM40,108a44,44,0,1,1,44,44A44.05,44.05,0,0,1,40,108Zm210.14,98.7a8,8,0,0,1-11.07-2.33A79.83,79.83,0,0,0,172,168a8,8,0,0,1,0-16,44,44,0,1,0-16.34-84.87,8,8,0,1,1-5.94-14.85,60,60,0,0,1,55.53,105.64,95.83,95.83,0,0,1,47.22,37.71A8,8,0,0,1,250.14,206.7Z"></path></svg>
        <p class="text-sm font-medium">User Management</p>
    </a>

    <a class="flex items-center gap-3 px-3 py-2.5 rounded-md bg-[#efebe9] text-[var(--accent-red)]" href="#">
        <svg fill="currentColor" height="20" width="20" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216Z"></path></svg>
        <p class="text-sm font-medium">Content Management</p>
    </a>

    <a class="flex items-center gap-3 px-3 py-2.5 rounded-md hover:bg-[#efebe9] text-[var(--text-brown)]" href="LogOutAdmin.php">
        <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 24 24" fill="currentColor"><path d="M13 3c-1.1 0-2 .9-2 2v4h2V5h7v14h-7v-4h-2v4c0 1.1.9 2 2 2h7c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-7zM10.41 10.41 7 13h8v-2H7l3.41-3.41L9 6l-6 6 6 6 1.41-1.41z"/></svg>
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
                    <a class="px-3 py-2 rounded-md hover:bg-[#efebe9]" href="index.php">Dashboard</a>
                    <a class="px-3 py-2 rounded-md hover:bg-[#efebe9]" href="userManagment.php">User Management</a>
                    <a class="px-3 py-2 rounded-md bg-[#efebe9] text-[var(--accent-red)]" href="#">Content Management</a>
                    <a class="px-3 py-2 rounded-md hover:bg-[#efebe9]" href="LogOutAdmin.php">Log Out</a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 md:ml-72 p-4 md:p-8">
            <div class="max-w-6xl mx-auto">
                <header class="mb-8 flex justify-between items-center">
                    <h1 class="text-3xl md:text-4xl font-bold text-[var(--text-dark-brown)] tracking-tight">Admin - Content Management</h1>
                    <!-- <div class="flex items-center gap-2">
                        <label for="search" class="sr-only">Search</label>
                        <input id="search" placeholder="Search subjects or notes" class="hidden md:block rounded-lg border border-[var(--border-color)] bg-white p-2 text-sm" />
                        <button id="menu-button" class="md:hidden p-2 rounded-md text-[var(--text-dark-brown)] hover:bg-[#efebe9]"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg></button>
                    </div> -->
                </header>

                <section>
                    <h2 class="text-2xl font-bold text-[var(--text-dark-brown)] mb-6">Subject Management</h2>
                    <div class="flex flex-col md:flex-row items-start gap-8 md:gap-16">
                        <div class="w-full md:w-1/2">
                            <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
                                <p class="text-sm text-[var(--text-secondary)]">*Enter Old Subject Name if you want to Update.</p>
                                <div>
                                    <input name="SubjectName" required placeholder="Enter Subject Name" class="w-full rounded-full border-none bg-[#ffe0cc] py-2.5 pl-4 pr-5 text-sm text-[var(--text-primary)] placeholder:text-[var(--text-secondary)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-color)]" />
                                </div>
                                <div>
                                    <input name="UpdateName" placeholder="Enter Updated Subject Name" class="w-full rounded-full border-none bg-[#ffe0cc] py-2.5 pl-4 pr-5 text-sm text-[var(--text-primary)] placeholder:text-[var(--text-secondary)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-color)]" />
                                </div>
                                <div>
                                    <input type="file" name="SubjectImage" accept="image/*" class="w-full rounded-full border-none bg-[#ffe0cc] py-2.5 pl-4 pr-5 text-sm text-[var(--text-primary)] placeholder:text-[var(--text-secondary)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-color)] file:bg-[#914a44] file:text-white file:border-none file:rounded-full file:px-4 file:py-1 file:mr-4 file:cursor-pointer" required />
                                </div>
                                <div class="flex gap-4">
                                    <button type="submit" name="submit" class="flex-1 h-10 items-center justify-center rounded-full bg-[#914a44] px-4 text-sm font-medium text-white transition-colors hover:bg-opacity-90">Add Subject</button>
                                    <button type="submit" name="update" class="flex-1 h-10 items-center justify-center rounded-full bg-[#914a44] px-4 text-sm font-medium text-white transition-colors hover:bg-opacity-90">Update Subject</button>
                                </div>

                                <?php if (!empty($subjectMessage)): ?>
                                    <div class="rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-yellow-900">
                                        <p class="font-semibold">Notice</p>
                                        <p class="text-sm"><?=htmlspecialchars($subjectMessage)?></p>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>

                        <div class="w-full md:w-1/3 bg-[var(--background-paper)] rounded-lg border border-[var(--border-color)] overflow-auto" style="max-height:320px;">
                            <table class="w-full text-left">
                                <thead class="bg-[#efebe9] sticky top-0">
                                    <tr>
                                        <th class="px-4 py-3 text-sm font-semibold text-[var(--text-brown)]">Subject Name</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border-color)]">
                                    <?php foreach ($subjects as $s): ?>
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap"><p class="text-sm font-medium text-[var(--text-dark-brown)]"><?=htmlspecialchars($s['name'])?></p></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="mt-8">
                    <h2 class="text-2xl font-bold text-[var(--text-dark-brown)] mb-6">Notes Management</h2>
                    <div class="bg-[var(--background-paper)] rounded-lg border border-[var(--border-color)] overflow-auto">
                        <table class="w-full text-left min-w-[640px]">
                            <thead class="bg-[#efebe9] border-b border-[var(--border-color)]">
                                <tr>
                                    <th class="px-4 py-3 text-sm font-semibold text-[var(--text-brown)]">Note Title</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-[var(--text-brown)]">Uploaded By</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-[var(--text-brown)]">Subject</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-[var(--text-brown)]">Status</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-[var(--text-brown)]">Preview</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-[var(--text-brown)] text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-color)]">
                                <?php foreach ($notes as $row):
                                    $isApproved = (int)$row['isApproved'];
                                    $title = htmlspecialchars($row['title']);
                                    $uploader = htmlspecialchars($row['user_name'] ?? 'Unknown');
                                    $subject = htmlspecialchars($row['subject_name'] ?? 'Unknown');
                                    $fileUrl = htmlspecialchars($row['file_url']);
                                    $noteId = (int)$row['note_id'];
                                ?>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap"><p class="text-sm font-medium text-[var(--text-dark-brown)]"><?=$title?></p></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-[var(--text-brown)]"><?=$uploader?></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-[var(--text-brown)]"><?=$subject?></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-[var(--text-brown)]"><?= $isApproved ? 'Approved' : 'Not Approved' ?></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-[var(--text-brown)] font-medium"><a class="text-[var(--accent-red)] hover:text-[var(--accent-light-red)]" href="../User/<?=$fileUrl?>" target="_blank">Show</a></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <?php if ($isApproved): ?>
                                                <a href="#" class="text-[var(--accent-red)] hover:text-[var(--accent-light-red)] mr-4 delete-btn" data-url="deleteAdmin.php?note_id=<?=$noteId?>">Delete</a>
                                            <?php else: ?>
                                                <a href="approve.php?note_id=<?=$noteId?>" class="text-[var(--accent-red)] hover:text-[var(--accent-light-red)] mr-4">Approve</a>
                                                <a href="#" class="text-[var(--accent-red)] hover:text-[var(--accent-light-red)] delete-btn" data-url="deleteAdmin.php?note_id=<?=$noteId?>">Delete</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-xl p-6 w-80 shadow-lg">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Are you sure?</h2>
            <p class="text-sm text-gray-600 mb-6">This action cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button id="cancelBtn" class="px-4 py-2 rounded-md bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button id="okBtn" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">OK</button>
            </div>
        </div>
    </div>

    <script>
        // Modal handling
        const modal = document.getElementById('confirmModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const okBtn = document.getElementById('okBtn');
        let deleteUrl = '';

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                deleteUrl = this.getAttribute('data-url');
                modal.classList.remove('hidden');
            });
        });

        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            deleteUrl = '';
        });

        okBtn.addEventListener('click', () => {
            if (deleteUrl) window.location.href = deleteUrl;
        });

        // Mobile sidebar toggles
        const mobileOpen = document.getElementById('mobile-open');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const mobileClose = document.getElementById('mobile-close');
        const mobileBackdrop = document.getElementById('mobile-backdrop');

        function openMobile() { mobileSidebar.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
        function closeMobile() { mobileSidebar.classList.add('hidden'); document.body.style.overflow = ''; }

        if (mobileOpen) mobileOpen.addEventListener('click', openMobile);
        if (mobileClose) mobileClose.addEventListener('click', closeMobile);
        if (mobileBackdrop) mobileBackdrop.addEventListener('click', closeMobile);

        // menu button for very small screens to toggle desktop sidebar
        const menuButton = document.getElementById('menu-button');
        const sidebar = document.getElementById('sidebar');
        if (menuButton) menuButton.addEventListener('click', () => sidebar.classList.toggle('hidden'));
    </script>
</body>

</html>
