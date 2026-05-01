<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// PHP Logic for Upload
if (isset($_POST['submitUpload'])) {
    $conn = new mysqli("localhost", "root", "", "notesnest_db");
    if ($conn->connect_error) {
        die("Error: Database Not Connected");
    }

    $title = $conn->real_escape_string($_POST['title']);
    $subject_id = (int)$_POST['subject'];
    $description = $conn->real_escape_string($_POST['description']);
    $tags = $conn->real_escape_string($_POST['tags']);
    $user_id = $_SESSION['user_id'];

    if (isset($_FILES['note_file']) && $_FILES['note_file']['error'] == 0) {
        $file_name = time() . "_" . $_FILES['note_file']['name'];
        $file_tmp = $_FILES['note_file']['tmp_name'];
        $file_path = "Uploads/" . $file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            $sql = "INSERT INTO tbl_notes (user_id, subject_id, title, description, file_url, isApproved) 
                    VALUES ($user_id, $subject_id, '$title', '$description', '$file_path', 0)";
            
            if ($conn->query($sql)) {
                $note_id = $conn->insert_id;
                // Add tags if provided
                if (!empty($tags)) {
                    $tagArray = explode(',', $tags);
                    foreach ($tagArray as $tagName) {
                        $tagName = trim($tagName);
                        if (!empty($tagName)) {
                            // Check if tag exists
                            $tagCheck = $conn->query("SELECT tag_id FROM tbl_tags WHERE name = '$tagName'");
                            if ($tagCheck->num_rows > 0) {
                                $tag_id = $tagCheck->fetch_assoc()['tag_id'];
                            } else {
                                $conn->query("INSERT INTO tbl_tags (name) VALUES ('$tagName')");
                                $tag_id = $conn->insert_id;
                            }
                            $conn->query("INSERT INTO tbl_note_tags (note_id, tag_id) VALUES ($note_id, $tag_id)");
                        }
                    }
                }
                echo '<div class="nn-toast-container"><div class="nn-toast"><div class="nn-toast-icon">✅</div><div class="nn-toast-message">Note uploaded successfully! It will be visible once approved.</div></div></div>';
                echo '<script>setTimeout(() => window.location="ExplorePage.php", 2500);</script>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Upload Note - NotesNest</title>
    <link href="style.css" rel="stylesheet" />
</head>

<body class="nn-page" style="background-color: #fcfcfc;">
    <!-- Navbar (MERN Replica) -->
    <header class="nn-navbar">
        <div class="nn-container">
            <div class="nn-nav-flex">
                <a href="index.php" class="nn-logo">NotesNest</a>
                
                <nav class="nn-nav-links">
                    <a href="index.php" class="nn-nav-link">Home</a>
                    <a href="ExplorePage.php" class="nn-nav-link">Notes</a>
                    <a href="Upload.php" class="nn-nav-link">Upload</a>
                    <a href="aboutUs.php" class="nn-nav-link">About Us</a>
                </nav>

                <div class="nn-nav-auth">
                    <a href="user_Profile.php" class="nn-nav-link" style="font-weight: 700;">Account</a>
                    <a href="LogOut.php" class="nn-btn-logout">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="nn-container" style="flex: 1;">
        <div class="nn-upload-container">
            <div class="nn-upload-header">
                <h1 style="font-family: 'Merriweather', serif; font-size: 32px; font-weight: 900;">Share Your Knowledge</h1>
                <p>Upload your academic resources and help the student community.</p>
            </div>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="nn-upload-form-group">
                    <label class="nn-upload-label">NOTE TITLE</label>
                    <input class="nn-upload-input" name="title" placeholder="e.g. Computer Networks Unit 1" type="text" required />
                </div>

                <div class="nn-upload-row">
                    <div class="nn-upload-form-group">
                        <label class="nn-upload-label">SUBJECT</label>
                        <select class="nn-upload-select" name="subject" required>
                            <option value="">Choose Subject</option>
                            <?php
                            $conn = new mysqli("localhost", "root", "", "notesnest_db");
                            $resSub = $conn->query("SELECT * FROM tbl_subject");
                            while ($sub = $resSub->fetch_assoc()) {
                                echo "<option value='{$sub['subject_id']}'>{$sub['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="nn-upload-form-group">
                        <label class="nn-upload-label">TAGS</label>
                        <input class="nn-upload-input" name="tags" placeholder="e.g. SEM 6, EXAM" type="text" />
                    </div>
                </div>

                <div class="nn-upload-form-group">
                    <label class="nn-upload-label">DESCRIPTION</label>
                    <textarea class="nn-upload-textarea" name="description" placeholder="Briefly describe what's inside this note..." rows="4" required></textarea>
                </div>

                <div class="nn-upload-form-group" style="margin-bottom: 40px;">
                    <label class="nn-upload-label">UPLOAD PDF</label>
                    <div class="nn-upload-file-wrapper" id="drop-zone">
                        <input accept="application/pdf" id="file-input" name="note_file" type="file" required style="display: none;">
                        <span class="nn-upload-file-icon" id="file-icon">📁</span>
                        <div id="file-text" style="font-weight: 700; color: #888;">CLICK OR DRAG TO SELECT PDF DOCUMENT</div>
                        <p id="file-hint" style="font-size: 13px; color: #aaa; margin-top: 8px;">Only PDF files are supported</p>
                    </div>
                </div>

                <button class="nn-upload-submit-btn" name="submitUpload" type="submit">Publish Your Note</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="nn-footer">
        <div class="nn-container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px;">
                <div>
                    <h4 class="nn-logo" style="margin-bottom: 20px; -webkit-text-fill-color: #fff; background: none; font-size: 28px;">NotesNest</h4>
                    <p style="color: #bbb; line-height: 1.8; font-size: 14px;">Your ultimate destination for high-quality academic notes and resources. Join our community and excel in your studies.</p>
                </div>
                <div>
                    <h4 style="font-family: 'Inter', sans-serif; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">Quick Links</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 12px;"><a href="index.php" style="color: #bbb; font-size: 14px;">Home</a></li>
                        <li style="margin-bottom: 12px;"><a href="ExplorePage.php" style="color: #bbb; font-size: 14px;">Explore Notes</a></li>
                        <li style="margin-bottom: 12px;"><a href="Upload.php" style="color: #bbb; font-size: 14px;">Upload Resources</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-family: 'Inter', sans-serif; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">Support</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 12px;"><a href="aboutUs.php" style="color: #bbb; font-size: 14px;">About Us</a></li>
                        <li style="margin-bottom: 12px;"><a href="#" style="color: #bbb; font-size: 14px;">Privacy Policy</a></li>
                        <li style="margin-bottom: 12px;"><a href="#" style="color: #bbb; font-size: 14px;">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-family: 'Inter', sans-serif; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">Connect</h4>
                    <div style="display: flex; gap: 15px; margin-top: 10px;">
                        <span style="font-size: 20px; color: #fff; cursor: pointer; background: rgba(255,255,255,0.05); width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">📱</span>
                        <span style="font-size: 20px; color: #fff; cursor: pointer; background: rgba(255,255,255,0.05); width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">📧</span>
                        <span style="font-size: 20px; color: #fff; cursor: pointer; background: rgba(255,255,255,0.05); width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">🌐</span>
                    </div>
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255,255,255,0.05); margin-top: 60px; padding-top: 30px; text-align: center;">
                <p style="color: #666; font-size: 13px;">© 2025 NotesNest. All rights reserved. Designed with ❤️ for students.</p>
            </div>
        </div>
    </footer>

    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const fileIcon = document.getElementById('file-icon');
        const fileText = document.getElementById('file-text');
        const fileHint = document.getElementById('file-hint');

        dropZone.onclick = () => fileInput.click();

        fileInput.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                if (file.type !== 'application/pdf') {
                    alert('Only PDF documents are supported.');
                    fileInput.value = '';
                    return;
                }
                fileIcon.textContent = '📄';
                fileText.textContent = file.name;
                fileText.style.color = 'var(--nn-primary)';
                fileHint.textContent = 'PDF Selected Successfully';
                fileHint.style.color = '#28a745';
                fileHint.style.fontWeight = '800';
            }
        };

        // Auto-hide toast
        setTimeout(() => {
            const toast = document.querySelector('.nn-toast-container');
            if(toast) toast.style.display = 'none';
        }, 4000);
    </script>
</body>

</html>