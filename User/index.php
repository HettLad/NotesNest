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
    <title>NotesNest - Home</title>
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

    <!-- Main Content -->
    <main style="flex: 1;">
        <!-- Hero Section (Optimized MERN Replica - Better & Compact) -->
        <section class="nn-hero" style="background: linear-gradient(135deg, #b40808 0%, #7a0606 100%); padding: 90px 0; color: #fff; position: relative; overflow: hidden; text-align: center;">
            <!-- Decorative circles - Fixed visibility and scaling -->
            <div style="position: absolute; top: -60px; right: -60px; width: 280px; height: 280px; border-radius: 50%; background: rgba(255,255,255,0.07); pointer-events: none;"></div>
            <div style="position: absolute; bottom: -100px; left: -100px; width: 320px; height: 320px; border-radius: 50%; background: rgba(255,255,255,0.04); pointer-events: none;"></div>
            
            <div class="nn-container" style="position: relative; z-index: 1;">
                <h1 style="color: #fff; font-size: 48px; font-weight: 900; margin-bottom: 20px; text-shadow: 0 4px 15px rgba(0,0,0,0.15); font-family: 'Merriweather', serif; letter-spacing: -1px;">
                    Empowering Your Academic Journey
                </h1>
                <p style="color: rgba(255,255,255,0.95); font-size: 20px; max-width: 750px; margin: 0 auto 40px auto; line-height: 1.6; font-weight: 500;">
                    Access a vast library of high-quality handwritten notes and subject-specific resources curated for students, by students.
                </p>
                <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                    <a href="ExplorePage.php" class="nn-btn" style="background-color: #fff; color: #b40808; padding: 16px 45px; font-size: 17px; border-radius: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); font-weight: 800;">
                        Browse Notes
                    </a>
                    <a href="Upload.php" class="nn-btn" style="background-color: transparent; color: #fff; border: 2.5px solid rgba(255,255,255,0.9); padding: 14px 45px; font-size: 17px; border-radius: 50px; font-weight: 800;">
                        Upload Notes
                    </a>
                </div>
            </div>
        </section>

        <!-- Trust & Stats Section -->
        <section style="padding: 80px 0; background-color: #fff;">
            <div class="nn-container">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; text-align: center;">
                    <div style="padding: 30px; border-radius: 20px; background: #fdf2f2; border: 1px solid rgba(180, 8, 8, 0.05);">
                        <div style="font-size: 40px; margin-bottom: 10px;">👥</div>
                        <h3 style="font-size: 36px; color: #b40808; margin: 0; font-weight: 900;">10K+</h3>
                        <p style="font-weight: 700; color: #666; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px; font-size: 13px;">Active Learners</p>
                    </div>
                    <div style="padding: 30px; border-radius: 20px; background: #fdf2f2; border: 1px solid rgba(180, 8, 8, 0.05);">
                        <div style="font-size: 40px; margin-bottom: 10px;">📚</div>
                        <h3 style="font-size: 36px; color: #b40808; margin: 0; font-weight: 900;">5K+</h3>
                        <p style="font-weight: 700; color: #666; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px; font-size: 13px;">Digital Resources</p>
                    </div>
                    <div style="padding: 30px; border-radius: 20px; background: #fdf2f2; border: 1px solid rgba(180, 8, 8, 0.05);">
                        <div style="font-size: 40px; margin-bottom: 10px;">📖</div>
                        <h3 style="font-size: 36px; color: #b40808; margin: 0; font-weight: 900;">50+</h3>
                        <p style="font-weight: 700; color: #666; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px; font-size: 13px;">Subjects Covered</p>
                    </div>
                    <div style="padding: 30px; border-radius: 20px; background: #fdf2f2; border: 1px solid rgba(180, 8, 8, 0.05);">
                        <div style="font-size: 40px; margin-bottom: 10px;">⭐</div>
                        <h3 style="font-size: 36px; color: #b40808; margin: 0; font-weight: 900;">4.8/5</h3>
                        <p style="font-weight: 700; color: #666; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px; font-size: 13px;">Top User Rating</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section style="padding: 100px 0; background-color: #111; color: #fff;">
            <div class="nn-container">
                <div style="text-align: center; margin-bottom: 70px;">
                    <h2 style="font-size: 36px; font-weight: 900; margin: 0; color: #fff; text-transform: uppercase; font-family: 'Merriweather', serif;">How It Works</h2>
                    <div style="width: 60px; height: 4px; background-color: #ff4d4d; margin: 20px auto;"></div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 50px;">
                    <div style="text-align: center;">
                        <div style="background: rgba(255,255,255,0.05); padding: 50px 30px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.1); height: 100%;">
                            <div style="background: #ff4d4d; color: #fff; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; margin: 0 auto 25px auto;">1</div>
                            <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 15px; color: #fff;">Create Account</h3>
                            <p style="color: #aaa; font-size: 15px; line-height: 1.7;">Join our community of students and educators in just a few clicks.</p>
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <div style="background: rgba(255,255,255,0.05); padding: 50px 30px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.1); height: 100%;">
                            <div style="background: #ff4d4d; color: #fff; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; margin: 0 auto 25px auto;">2</div>
                            <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 15px; color: #fff;">Upload Resources</h3>
                            <p style="color: #aaa; font-size: 15px; line-height: 1.7;">Share your handwritten notes, PDFs, or guides with the community.</p>
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <div style="background: rgba(255,255,255,0.05); padding: 50px 30px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.1); height: 100%;">
                            <div style="background: #ff4d4d; color: #fff; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; margin: 0 auto 25px auto;">3</div>
                            <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 15px; color: #fff;">Download & Learn</h3>
                            <p style="color: #aaa; font-size: 15px; line-height: 1.7;">Access quality materials from top students and excel in your exams.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Subjects Section -->
        <section style="padding: 100px 0;">
            <div class="nn-container">
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 60px;">
                    <div style="text-align: left;">
                        <h2 style="font-size: 36px; color: #333; font-weight: 900; margin: 0; font-family: 'Merriweather', serif;">EXPLORE BY SUBJECT</h2>
                        <div style="width: 80px; height: 5px; background-color: #b40808; margin-top: 20px;"></div>
                    </div>
                    <a href="ExplorePage.php" style="color: #b40808; font-weight: 700; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                        View All Subjects <span>→</span>
                    </a>
                </div>

                <div class="nn-grid">
                    <?php
                    $conn = new mysqli("localhost", "root", "", "notesnest_db");
                    if ($conn->connect_error) {
                        die("Error: Database Not Connected");
                    }
                    $qry = "SELECT * FROM tbl_subject";
                    $res = $conn->query($qry);
                    while ($fetch = $res->fetch_assoc()) {
                        $qry2 = "SELECT count(note_id) as total FROM tbl_notes WHERE subject_id = {$fetch['subject_id']} AND isApproved = 1";
                        $res2 = $conn->query($qry2);
                        $fetch2 = $res2->fetch_assoc();

                        echo "
                        <a href='ExplorePage.php?id={$fetch['subject_id']}' class='nn-card'>
                            <div class='nn-card-img'>
                                <img alt='Subject thumbnail' src='./{$fetch['img']}' />
                            </div>
                            <div class='nn-card-content'>
                                <h3 class='nn-card-title'>{$fetch['name']}</h3>
                                <p class='nn-card-meta'>
                                    <span style='font-size: 18px;'>📄</span> {$fetch2['total']} Resources Available
                                </p>
                                <div style='margin-top: 20px; text-align: center;'>
                                    <span class='nn-note-btn-download' style='width: 100%; display: block;'>Explore Notes</span>
                                </div>
                            </div>
                        </a>";
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section style="padding: 100px 0; background: linear-gradient(45deg, #b40808, #ff4d4d); text-align: center; color: #fff;">
            <div class="nn-container">
                <h2 style="font-size: 42px; font-weight: 900; margin-bottom: 20px; color: #fff; font-family: 'Merriweather', serif;">Ready to elevate your learning?</h2>
                <p style="font-size: 20px; margin-bottom: 40px; opacity: 0.9; max-width: 700px; margin-left: auto; margin-right: auto;">Join our growing community and start sharing your academic resources today.</p>
                <a href="Upload.php" class="nn-btn" style="background: #fff; color: #b40808; padding: 18px 50px; font-size: 18px; border-radius: 50px; font-weight: 900;">
                    Get Started Now
                </a>
            </div>
        </section>
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