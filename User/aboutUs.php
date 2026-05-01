<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>About Us - NotesNest</title>
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
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="user_Profile.php" class="nn-nav-link" style="font-weight: 700;">Account</a>
                        <a href="LogOut.php" class="nn-btn-logout">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="nn-btn" style="padding: 8px 25px; border-radius: 8px;">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main style="flex: 1;">
        <!-- Page Header -->
        <section style="background-color: #b40808; color: #fff; padding: 80px 0; text-align: center;">
            <div class="nn-container">
                <h1 style="font-size: 48px; color: #fff; margin-bottom: 20px; font-family: 'Merriweather', serif;">About NotesNest</h1>
                <p style="font-size: 20px; max-width: 800px; margin: 0 auto; opacity: 0.9; font-weight: 500;">
                    Empowering students worldwide with high-quality academic resources and a collaborative learning platform.
                </p>
            </div>
        </section>

        <div class="nn-container" style="padding: 80px 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 60px; align-items: center;">
                <div>
                    <h2 style="font-size: 32px; color: #333; margin-bottom: 25px; font-family: 'Merriweather', serif;">Our Mission</h2>
                    <p style="color: #555; line-height: 1.8; font-size: 17px; margin-bottom: 30px;">
                        At NotesNest, we believe that education is the most powerful weapon which you can use to change the world. Our mission is to make high-quality learning materials accessible to every student, regardless of their background or location.
                    </p>
                    <p style="color: #555; line-height: 1.8; font-size: 17px;">
                        We provide a platform where students can share their hard-earned knowledge and help others succeed. By fostering a community of sharing, we aim to elevate the standard of academic preparation globally.
                    </p>
                </div>
                <div style="background: #f9f9f9; padding: 50px; border-radius: 12px; border: 1px solid #eee;">
                    <h3 style="color: #b40808; font-size: 24px; margin-bottom: 20px; font-family: 'Merriweather', serif;">Why Choose Us?</h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <?php
                        $items = [
                            "Curated Handwritten Notes",
                            "Previous Year Question Papers",
                            "Subject-wise Study Guides",
                            "Community Verified Content",
                            "User-friendly Interface",
                            "24/7 Academic Support"
                        ];
                        foreach($items as $item):
                        ?>
                        <li style="margin-bottom: 15px; display: flex; align-items: center; gap: 15px; font-weight: 600; color: #444;">
                            <span style="color: #075606; font-size: 20px;">✔</span> <?php echo $item; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <?php if(!isset($_SESSION['user_id'])): ?>
                <div style="margin-top: 100px; text-align: center;">
                    <h2 style="font-size: 32px; color: #333; margin-bottom: 20px; font-family: 'Merriweather', serif;">Ready to boost your grades?</h2>
                    <p style="color: #666; margin-bottom: 40px; font-size: 18px; font-weight: 500;">Join thousands of students who are already using NotesNest to excel in their studies.</p>
                    <a href="login.php" class="nn-btn" style="padding: 18px 50px; font-size: 18px; border-radius: 50px; font-weight: 800;">Create Your Free Account</a>
                </div>
            <?php endif; ?>
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