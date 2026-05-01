<?php
ob_start();
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
    <title>Note Details - NotesNest</title>
    <link href="style.css" rel="stylesheet" />
</head>

<body class="nn-page" style="background-color: #fafafa;">
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

    <main class="nn-container" style="padding: 60px 20px; flex: 1;">
        <?php
        $conn = new mysqli("localhost", "root", "", "notesnest_db");
        if ($conn->connect_error) {
            die("Error: Database Not Connected");
        }
        $note_id = (int)$_REQUEST['note_id'];
        $qry = "SELECT n.*, s.name AS subject_name, s.img AS subject_img, u.user_name 
                FROM tbl_notes n 
                JOIN tbl_subject s ON n.subject_id = s.subject_id 
                JOIN tbl_user u ON n.user_id = u.user_id 
                WHERE n.note_id = $note_id";
        $res = $conn->query($qry);
        if ($row = $res->fetch_assoc()) {
        ?>
            <!-- Breadcrumb-style Back Link -->
            <div style="margin-bottom: 30px;">
                <a href="ExplorePage.php" style="color: #666; font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 15px; text-decoration: none;">
                    <span>←</span> Back to All Notes
                </a>
            </div>

            <div style="display: flex; gap: 50px; flex-wrap: wrap; align-items: flex-start;">
                
                <!-- Left Side: Note Preview & Description -->
                <div style="flex: 1 1 600px;">
                    <div style="background: #fff; padding: 40px; border-radius: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #eee;">
                        <h1 style="color: #333; font-size: 42px; font-weight: 900; margin-bottom: 15px; line-height: 1.2; font-family: 'Merriweather', serif;"><?php echo htmlspecialchars($row['title']); ?></h1>
                        
                        <div style="display: flex; gap: 25px; margin-bottom: 35px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 10px; color: #666; font-size: 15px; font-weight: 600;">
                                <span style="font-size: 20px;">📚</span> <?php echo htmlspecialchars($row['subject_name']); ?>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; color: #666; font-size: 15px; font-weight: 600;">
                                <span style="font-size: 20px;">👤</span> <?php echo htmlspecialchars($row['user_name']); ?>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; color: #666; font-size: 15px; font-weight: 600;">
                                <span style="font-size: 20px;">📅</span> <?php echo date('d/m/Y', strtotime($row['createdAt'])); ?>
                            </div>
                        </div>

                        <div style="position: relative; border-radius: 20px; overflow: hidden; border: 1px solid #f0f0f0; background-color: #f9f9f9; margin-bottom: 40px;">
                            <img src="./<?php echo htmlspecialchars($row['subject_img']); ?>" alt="Note Preview" style="width: 100%; display: block; max-height: 500px; object-fit: contain;" />
                        </div>

                        <div>
                            <h3 style="font-size: 24px; font-weight: 800; color: #333; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                                <span style="width: 4px; height: 24px; background: var(--nn-primary); border-radius: 10px;"></span>
                                Description
                            </h3>
                            <p style="color: #555; line-height: 1.8; font-size: 17px; white-space: pre-line;">
                                <?php echo !empty($row['description']) ? nl2br(htmlspecialchars($row['description'])) : "No detailed description available for this study material. Please refer to the title and subject for content information."; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Sidebar Actions -->
                <div style="flex: 0 0 380px;">
                    
                    <!-- Rating Card -->
                    <div style="background-color: #fff; padding: 40px; border-radius: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #eee; margin-bottom: 30px;">
                        <h3 style="margin: 0 0 25px 0; font-size: 20px; font-weight: 800; color: #333; text-align: center;">Community Rating</h3>
                        
                        <div style="text-align: center; margin-bottom: 25px;">
                            <?php
                            $qryRating = "SELECT ROUND(avg(rating), 1) AS average, count(rating) as total FROM tbl_rating WHERE note_id = $note_id";
                            $resRating = $conn->query($qryRating);
                            $ratingData = $resRating->fetch_assoc();
                            $avgRating = $ratingData['average'] ?: "0.0";
                            ?>
                            <div style="font-size: 64px; font-weight: 900; color: var(--nn-primary); line-height: 1;"><?php echo $avgRating; ?></div>
                            <div style="display: flex; justify-content: center; gap: 4px; margin-top: 15px; font-size: 24px;">
                                <?php
                                $roundedAvg = round($avgRating);
                                for ($i = 1; $i <= 5; $i++) {
                                    $color = ($i <= $roundedAvg) ? '#fcb900' : '#eee';
                                    echo "<span style='color: $color;'>★</span>";
                                }
                                ?>
                            </div>
                            <p style="color: #888; font-size: 14px; margin-top: 10px; font-weight: 600;">Based on <?php echo $ratingData['total']; ?> user reviews</p>
                        </div>

                        <div style="border-top: 1px solid #f0f0f0; padding-top: 25px;">
                            <p style="text-align: center; font-weight: 700; font-size: 14px; color: #555; margin-bottom: 15px;">RATE THIS MATERIAL</p>
                            <form action="" method="post" id="ratingForm">
                                <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 20px;" class="star-rating-container">
                                    <style>
                                        .star-rating { display: flex; flex-direction: row-reverse; justify-content: center; gap: 8px; }
                                        .star-rating input { display: none; }
                                        .star-rating label { font-size: 32px; color: #eee; cursor: pointer; }
                                        .star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: #fcb900; }
                                    </style>
                                    <div class="star-rating">
                                        <input type="radio" id="star5" name="rating" value="5" /><label for="star5">★</label>
                                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4">★</label>
                                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3">★</label>
                                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2">★</label>
                                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1">★</label>
                                    </div>
                                </div>
                                <button type="submit" name="submitRating" class="nn-btn" style="width: 100%; border-radius: 12px; padding: 14px; font-weight: 800;">
                                    SUBMIT MY RATING
                                </button>
                            </form>
                            
                            <?php
                            if (isset($_POST['submitRating'])) {
                                $ratingValue = $_POST['rating'] ?? null;
                                if ($ratingValue) {
                                    $user_id = $_SESSION['user_id'];
                                    $checkQry = "SELECT * FROM tbl_rating WHERE user_id = $user_id AND note_id = $note_id";
                                    if ($conn->query($checkQry)->num_rows > 0) {
                                        echo '<div class="nn-toast" style="position: static; margin-top: 20px; box-shadow: none; border: 1px solid #ffc9c9; background: #fff5f5; color: #e03131;">You have already rated this note.</div>';
                                    } else {
                                        $conn->query("INSERT INTO tbl_rating VALUES($user_id, $note_id, $ratingValue)");
                                        header("Refresh:0");
                                    }
                                } else {
                                    echo '<div class="nn-toast" style="position: static; margin-top: 20px; box-shadow: none; border: 1px solid #ffc9c9; background: #fff5f5; color: #e03131;">Please select a rating.</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <!-- View/Download Action -->
                    <div style="background: #fff; padding: 30px; border-radius: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #eee;">
                        <a href="<?php echo htmlspecialchars($row['file_url']); ?>" target="_blank" class="nn-btn" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 16px 10px; font-size: 15px; font-weight: 800; border-radius: 16px; text-decoration: none; background: linear-gradient(45deg, #b40808, #ff4d4d); box-shadow: 0 10px 20px rgba(180, 8, 8, 0.2); white-space: nowrap; border: none; cursor: pointer; color: #fff;">
                            <span>📄 VIEW FULL DOCUMENT</span>
                        </a>
                        
                        <div style="margin-top: 25px; padding: 15px; background-color: #fdf2f2; border-radius: 15px; font-size: 13px; color: #b40808; border: 1px dashed rgba(180, 8, 8, 0.2); line-height: 1.5;">
                            <strong>Note:</strong> All materials are verified by the community. Please report any issues to our support team.
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>
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