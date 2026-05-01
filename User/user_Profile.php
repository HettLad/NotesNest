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
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>My Profile - NotesNest</title>
    <link href="style.css" rel="stylesheet" />
</head>

<body class="nn-page">
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

    <main class="nn-container" style="padding: 60px 20px;">
        <?php
        $conn = new mysqli("localhost", "root", "", "notesnest_db");
        if ($conn->connect_error) {
            die("Error: Database Not Connected");
        }
        $user_id = $_SESSION['user_id'];
        
        // Fetch user data
        $resUser = $conn->query("SELECT * FROM tbl_user WHERE user_id = $user_id");
        $user = $resUser->fetch_assoc();
        
        // Initial letter
        $initial = strtoupper(substr($user['name'], 0, 1));
        ?>

        <!-- Profile Header -->
        <div style="background-color: #fff; padding: 50px; border-radius: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); border: 1px solid #eee; display: flex; gap: 50px; align-items: center; margin-bottom: 60px; flex-wrap: wrap;">
            <div style="width: 140px; height: 140px; border-radius: 50%; background-color: var(--nn-primary); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 64px; font-weight: 900; box-shadow: 0 10px 20px rgba(180, 8, 8, 0.2);">
                <?php echo $initial; ?>
            </div>
            <div style="flex: 1; min-width: 300px;">
                <h1 style="color: #333; margin: 0 0 10px 0; font-size: 36px; font-weight: 900; font-family: 'Merriweather', serif;"><?php echo htmlspecialchars($user['name']); ?></h1>
                <p style="font-size: 18px; color: #666; margin-bottom: 25px; font-weight: 500;">@<?php echo htmlspecialchars($user['user_name']); ?> • <?php echo htmlspecialchars($user['email']); ?></p>
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <a href="changePassword.php" class="nn-note-btn-download" style="text-decoration: none; background-color: #fff; color: var(--nn-primary); border: 2px solid var(--nn-primary); font-weight: 800;">Change Password</a>
                    <a href="Upload.php" class="nn-note-btn-download" style="text-decoration: none; background-color: var(--nn-primary); color: #fff; font-weight: 800;">Upload New Note</a>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 32px; color: #333; font-weight: 900; font-family: 'Merriweather', serif;">My Published Notes</h2>
            <div style="width: 80px; height: 5px; background-color: var(--nn-primary); margin-top: 15px;"></div>
        </div>

        <div style="background: #fff; border: 1px solid #eee; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: #fafafa; border-bottom: 2px solid #eee;">
                        <tr>
                            <th style="padding: 25px 20px; text-align: left; font-size: 14px; color: var(--nn-primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">DOCUMENT TITLE</th>
                            <th style="padding: 25px 20px; text-align: left; font-size: 14px; color: var(--nn-primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">SUBJECT</th>
                            <th style="padding: 25px 20px; text-align: left; font-size: 14px; color: var(--nn-primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">RATING</th>
                            <th style="padding: 25px 20px; text-align: left; font-size: 14px; color: var(--nn-primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">STATUS</th>
                            <th style="padding: 25px 20px; text-align: center; font-size: 14px; color: var(--nn-primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $qryNotes = "SELECT n.*, s.name AS subject_name 
                                    FROM tbl_notes n 
                                    JOIN tbl_subject s ON n.subject_id = s.subject_id 
                                    WHERE n.user_id = $user_id
                                    ORDER BY n.createdAt DESC";
                        $resNotes = $conn->query($qryNotes);

                        if ($resNotes->num_rows === 0) {
                            echo "<tr><td colspan='5' style='padding: 60px; text-align: center; color: #999; font-size: 18px; font-weight: 500;'>You haven't uploaded any notes yet.</td></tr>";
                        } else {
                            while ($note = $resNotes->fetch_assoc()) {
                                $qryRating = "SELECT ROUND(avg(rating), 1) as avg FROM tbl_rating WHERE note_id = {$note['note_id']}";
                                $rating = $conn->query($qryRating)->fetch_assoc()['avg'] ?: "0.0";
                                
                                $statusBg = $note['isApproved'] ? '#e8f5e9' : '#fff3e0';
                                $statusColor = $note['isApproved'] ? '#2e7d32' : '#ef6c00';
                                $statusText = $note['isApproved'] ? 'APPROVED' : 'PENDING';
                        ?>
                                <tr style="border-bottom: 1px solid #f5f5f5; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fcfcfc'" onmouseout="this.style.backgroundColor='transparent'">
                                    <td style="padding: 20px; font-weight: 700; color: #333;"><?php echo htmlspecialchars($note['title']); ?></td>
                                    <td style="padding: 20px; color: #666; font-weight: 500;"><?php echo htmlspecialchars($note['subject_name']); ?></td>
                                    <td style="padding: 20px; font-weight: 700; color: #fcb900;">★ <?php echo $rating; ?></td>
                                    <td style="padding: 20px;">
                                        <span style="display: inline-block; padding: 6px 15px; border-radius: 50px; font-size: 12px; font-weight: 800; background-color: <?php echo $statusBg; ?>; color: <?php echo $statusColor; ?>;">
                                            <?php echo $statusText; ?>
                                        </span>
                                    </td>
                                    <td style="padding: 20px; text-align: center;">
                                        <div style="display: flex; gap: 10px; justify-content: center;">
                                            <a href="NotesInfo.php?note_id=<?php echo $note['note_id']; ?>" class="nn-note-btn-download" style="padding: 8px 20px; font-size: 12px; border: 1px solid #ddd; background: #fff; color: #555; font-weight: 700;">VIEW</a>
                                            <a href="deleteNotesUser.php?note_id=<?php echo $note['note_id']; ?>" onclick="return confirm('Delete this note permanently?')" class="nn-note-btn-download" style="padding: 8px 20px; font-size: 12px; border: none; font-weight: 700;">REMOVE</a>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
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
</body>

</html>