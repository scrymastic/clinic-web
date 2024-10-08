<!-- footer.php -->
<footer class="text-center text-lg-start" style="margin-top: 100px;">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase">CLINXME</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-dark">About</a></li>
                    <li><a href="#" class="text-dark">Features</a></li>
                    <li><a href="#" class="text-dark">Careers</a></li>
                    <li><a href="#" class="text-dark">Help Center</a></li>
                    <li><a href="#" class="text-dark">Support</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase">Resources</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-dark">FAQ</a></li>
                    <li><a href="#" class="text-dark">Security</a></li>
                    <li><a href="#" class="text-dark">Blog</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase">Contact Us</h5>
                <ul class="list-unstyled">
                    <li>
                        <a href="https://www.facebook.com" class="text-dark" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.google.com" class="text-dark" target="_blank"><i class="fab fa-google"></i></a>
                        <a href="https://www.twitter.com" class="text-dark" target="_blank"><i class="fab fa-twitter"></i></a>
                    </li>
                    <?php // Query to get the contact information
                    $sql = 'SELECT * FROM clinic_info';
                    $stmt = $conn->query($sql);
                    $clinic_info = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <li><?php echo htmlspecialchars($clinic_info['phone']); ?></li>
                    <li><a href="mailto:<?php echo htmlspecialchars($clinic_info['email']); ?>"><?php echo htmlspecialchars($clinic_info['email']); ?></a></li>
                    <li><?php echo htmlspecialchars($clinic_info['address']); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="text-center p-3">
        © 2024 CLINXME Sdn Bhd
        <br>
        <a href="#" class="text-dark">Privacy</a> · 
        <a href="#" class="text-dark">Accessibility</a> · 
        <a href="#" class="text-dark">Terms</a>
    </div>
</footer>

