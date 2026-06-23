
<?php
// footer.php
?>
<footer style="background:#2c3e50; color:#ecf0f1; padding:40px 20px; font-family: 'Segoe UI', sans-serif; margin-top:50px;">
    <div style="max-width:1200px; margin:auto; display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:30px;">
        
        <!-- About -->
        <div>
            <h3 style="margin-bottom:15px; color:#1abc9c;">JobWave</h3>
            <p style="line-height:1.8;">
                JobWave is your gateway to better career opportunities. Explore jobs, build resumes, 
                and connect with top companies worldwide.
            </p>
        </div>

        <!-- Quick Links -->
        <div>
            <h4 style="margin-bottom:15px; color:#1abc9c;">Quick Links</h4>
            <ul style="list-style:none; padding:0; line-height:1.8;">
                <li><a href="/jobwave/main_page.php" style="color:#ecf0f1; text-decoration:none;">Home</a></li>
                <li><a href="/jobwave/jobs/job_list.php" style="color:#ecf0f1; text-decoration:none;">Find Jobs</a></li>
                <li><a href="/jobwave/resume/resume_builder.php" style="color:#ecf0f1; text-decoration:none;">Resume Builder</a></li>
                <li><a href="/jobwave/login/login.php" style="color:#ecf0f1; text-decoration:none;">My Profile</a></li>
                <li><a href="/jobwave/login/login.php" style="color:#ecf0f1; text-decoration:none;">Contact Us</a></li>
            </ul>
        </div>

        <!-- Resources -->
        <div>
            <h4 style="margin-bottom:15px; color:#1abc9c;">Resources</h4>
            <ul style="list-style:none; padding:0; line-height:1.8;">
                <li><a href="/jobwave/about.php" style="color:#ecf0f1; text-decoration:none;">About Us</a></li>
                <li><a href="/jobwave/FAQs.php" style="color:#ecf0f1; text-decoration:none;">FAQs</a></li>
                <li><a href="/jobwave/privacy_policy.php" style="color:#ecf0f1; text-decoration:none;">Privacy Policy</a></li>
                <li><a href="/jobwave/terms&conditions.php" style="color:#ecf0f1; text-decoration:none;">Terms & Conditions</a></li>
            </ul>
        </div>

        <!-- Contact -->
        <div>
            <h4 style="margin-bottom:15px; color:#1abc9c;">Contact</h4>
            <p>Email: support@jobwave.com</p>
            <p>Phone: +91 9313207556 | 9879076634
            </p>
            <p>Location: Rajkot, Gujarat</p>

            <!-- Social Media -->
            <div style="margin-top:15px;">
                <a href="#" style="margin-right:10px; color:#ecf0f1; text-decoration:none;">Facebook</a>
                <a href="#" style="margin-right:10px; color:#ecf0f1; text-decoration:none;">LinkedIn</a>
                <a href="#" style="margin-right:10px; color:#ecf0f1; text-decoration:none;">Twitter</a>
                <a href="#" style="color:#ecf0f1; text-decoration:none;">Instagram</a>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div style="text-align:center; border-top:1px solid #7f8c8d; padding-top:20px; margin-top:30px; font-size:14px; color:#bdc3c7;">
        © <?php echo date("Y"); ?> JobWave. All Rights Reserved.
    </div>
</footer>