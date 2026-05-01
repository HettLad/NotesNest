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
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Study Materials - NotesNest</title>
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

    <main class="nn-container" style="padding: 80px 20px; flex: 1;">
        <header class="nn-page-header">
            <div>
                <h1 style="color: var(--nn-primary); font-size: 42px; margin: 0; font-weight: 900; font-family: 'Merriweather', serif;">Study Materials</h1>
                <p style="color: var(--nn-light-text); margin: 10px 0 0 0; font-size: 18px; font-weight: 500;">
                    Premium academic resources for your success
                </p>
            </div>

            <div class="nn-search-container">
                <form action="ExplorePage.php" method="GET">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search by title or subject..."
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                        class="nn-search-input"
                    />
                    <?php if(isset($_GET['id'])): ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                    <?php endif; ?>
                </form>
            </div>
        </header>

        <div class="nn-notes-grid">
            <?php
            $conn = new mysqli("localhost", "root", "", "notesnest_db");
            if ($conn->connect_error) {
                die("Error: Database Not Connected");
            }

            $searchQuery = "";
            $whereClauses = ["n.isApproved = 1"];
            
            if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
                $s = $conn->real_escape_string(trim($_GET['search']));
                $whereClauses[] = "(n.title LIKE '%$s%' OR s.name LIKE '%$s%' OR t.name LIKE '%$s%')";
            }

            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int)$_GET['id'];
                $whereClauses[] = "n.subject_id = $id";
            }

            $whereSql = implode(" AND ", $whereClauses);

            $sql = "SELECT n.*, s.name AS subject_name, s.img AS subject_img, u.user_name 
                    FROM tbl_notes n 
                    JOIN tbl_subject s ON n.subject_id = s.subject_id 
                    JOIN tbl_user u ON n.user_id = u.user_id 
                    LEFT JOIN tbl_note_tags nt ON n.note_id = nt.note_id 
                    LEFT JOIN tbl_tags t ON nt.tag_id = t.tag_id 
                    WHERE $whereSql
                    GROUP BY n.note_id
                    ORDER BY n.createdAt DESC";

            $result = $conn->query($sql);

            if ($result->num_rows === 0) {
            ?>
                <div style="text-align: center; padding: 100px; grid-column: 1 / -1; background: #fcfcfc; border-radius: 20px; border: 1px dashed #ddd;">
                    <div style="font-size: 40px; margin-bottom: 20px;">😕</div>
                    <h3 style="margin: 0; font-weight: 800; color: #333;">No notes found</h3>
                    <p style="color: #888; font-weight: 500; margin-top: 10px;">Try a different search term or check another subject.</p>
                    <a href="ExplorePage.php" class="nn-btn" style="margin-top: 20px; border-radius: 12px;">Clear Search</a>
                </div>
            <?php
            } else {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <a href="NotesInfo.php?note_id=<?php echo $row['note_id']; ?>" class="nn-note-card">
                        <div class="nn-note-card-img">
                            <img src="./<?php echo $row['subject_img']; ?>" alt="Subject thumbnail" />
                        </div>
                        <div class="nn-note-card-content">
                            <h3 class="nn-note-card-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <div class="nn-note-card-meta">
                                <div class="nn-note-card-meta-item">
                                    <span>👤</span> <strong><?php echo htmlspecialchars($row['user_name']); ?></strong>
                                </div>
                                <div class="nn-note-card-meta-item">
                                    <span>📚</span> <?php echo htmlspecialchars($row['subject_name']); ?>
                                </div>
                            </div>
                            <div class="nn-note-card-footer">
                                <span class="nn-note-btn-download">View Note</span>
                            </div>
                        </div>
                    </a>
            <?php
                }
            }
            ?>
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