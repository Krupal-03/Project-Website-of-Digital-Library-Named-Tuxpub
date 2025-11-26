<?php
include_once 'header.php';

$host = 'localhost';
$db = "tuxdb";
$user = "root";
$pass = "";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3>About Tuxpub</h3>
        <span class="breadcrumb"><a href="index.php">Home</a> > About Us</span>
      </div>
    </div>
  </div>
</div>

<div class="single-product section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="about-content">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="left-image">
                                <div class="custom-graphic">
                                    <div class="book-stack">
                                        <div class="book book-1"></div>
                                        <div class="book book-2"></div>
                                        <div class="book book-3"></div>
                                        <div class="book book-4"></div>
                                    </div>
                                    <div class="floating-elements">
                                        <div class="floating-element element-1">📚</div>
                                        <div class="floating-element element-2">💻</div>
                                        <div class="floating-element element-3">🌍</div>
                                        <div class="floating-element element-4">🎓</div>
                                    </div>
                                    <div class="graphic-text">
                                        <h4>Tuxpub</h4>
                                        <p>Free Digital Library</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 align-self-center">
                            <h2>Welcome to Tuxpub</h2>
                            <p class="lead">Your premier destination for free educational books and resources.</p>
                            <p>Tuxpub is a comprehensive digital library platform dedicated to providing free access to quality educational materials, programming books, technical guides, and literary works for learners worldwide.</p>
                            
                            <div class="features-list mt-4">
                                <div class="feature-item mb-3">
                                    <h5><i class="fa fa-book text-primary mr-2"></i> Extensive Collection</h5>
                                    <p>Access thousands of books across various categories including programming, science, literature, and more.</p>
                                </div>
                                
                                <div class="feature-item mb-3">
                                    <h5><i class="fa fa-download text-success mr-2"></i> Free Downloads</h5>
                                    <p>All books are available for free download in multiple formats including PDF and EPUB.</p>
                                </div>
                                
                                <div class="feature-item mb-3">
                                    <h5><i class="fa fa-users text-info mr-2"></i> Community Driven</h5>
                                    <p>Join our community of readers, authors, and publishers to share knowledge and resources.</p>
                                </div>
                                
                                <div class="feature-item mb-3">
                                    <h5><i class="fa fa-heart text-danger mr-2"></i> Donate Books</h5>
                                    <p>Contribute to our growing library by donating your books and helping others learn.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rest of your about page content remains the same -->
                    <div class="row mt-5">
                        <div class="col-lg-12">
                            <div class="mission-vision">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <i class="fa fa-bullseye fa-3x text-primary mb-3"></i>
                                                <h4>Our Mission</h4>
                                                <p>To make quality educational resources accessible to everyone, everywhere, regardless of their financial situation. We believe knowledge should be free and available to all.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <i class="fa fa-eye fa-3x text-success mb-3"></i>
                                                <h4>Our Vision</h4>
                                                <p>To create the world's largest community-driven digital library where anyone can learn, share, and grow through free access to educational materials.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-lg-12 text-center">
                            <h3>Why Choose Tuxpub?</h3>
                            <div class="row mt-4">
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="feature-box">
                                        <i class="fa fa-shield-alt fa-2x text-warning mb-3"></i>
                                        <h5>100% Safe</h5>
                                        <p>All books are public and safe.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="feature-box">
                                        <i class="fa fa-mobile-alt fa-2x text-info mb-3"></i>
                                        <h5>Mobile Friendly</h5>
                                        <p>Access our library from any device, anywhere.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="feature-box">
                                        <i class="fa fa-search fa-2x text-success mb-3"></i>
                                        <h5>Easy Search</h5>
                                        <p>Find exactly what you need with our powerful search.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="feature-box">
                                        <i class="fa fa-sync fa-2x text-primary mb-3"></i>
                                        <h5>Regular Updates</h5>
                                        <p>New books added regularly to keep you updated.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="feature-box">
                                        <i class="fa fa-globe fa-2x text-danger mb-3"></i>
                                        <h5>Multiple Languages</h5>
                                        <p>Books available in various languages for global readers.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="feature-box">
                                        <i class="fa fa-cloud-download-alt fa-2x text-secondary mb-3"></i>
                                        <h5>Fast Downloads</h5>
                                        <p>Quick and easy download process without restrictions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-lg-12 text-center">
                            <div class="cta-section">
                                <h3>Join Our Community Today!</h3>
                                <p class="lead">Start exploring our vast collection of free books and resources.</p>
                                <div class="mt-4">
                                    <a href="library.php" class="btn btn-primary btn-lg mr-3">
                                        <i class="fa fa-book"></i> Browse Library
                                    </a>
                                    <a href="donate-book.php" class="btn btn-success btn-lg">
                                        <i class="fa fa-cloud-upload"></i> Donate Books
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.about-content {
    line-height: 1.8;
}

/* Fixed Height CSS Graphic */
.left-image {
    height: 100%;
    display: flex;
    align-items: center;
}

.custom-graphic {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    height: 500px; /* Increased height */
    width: 100%;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.book-stack {
    position: relative;
    width: 150px;
    height: 180px;
    z-index: 2;
}

.book {
    position: absolute;
    background: white;
    border-radius: 6px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    transition: transform 0.3s ease;
}

.book:hover {
    transform: translateY(-5px);
}

.book-1 {
    width: 120px;
    height: 160px;
    transform: rotate(-8deg);
    background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
}

.book-2 {
    width: 120px;
    height: 170px;
    left: 15px;
    background: linear-gradient(45deg, #4ecdc4, #6de0d8);
    transform: rotate(-2deg);
}

.book-3 {
    width: 120px;
    height: 165px;
    left: 30px;
    background: linear-gradient(45deg, #45b7d1, #67c9e0);
    transform: rotate(4deg);
}

.book-4 {
    width: 120px;
    height: 155px;
    left: 45px;
    background: linear-gradient(45deg, #96ceb4, #b4e0cb);
    transform: rotate(10deg);
}

/* Floating Elements */
.floating-elements {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
}

.floating-element {
    position: absolute;
    font-size: 24px;
    opacity: 0.7;
    animation: float 6s ease-in-out infinite;
}

.element-1 {
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.element-2 {
    top: 70%;
    left: 15%;
    animation-delay: 1.5s;
}

.element-3 {
    top: 30%;
    right: 10%;
    animation-delay: 3s;
}

.element-4 {
    top: 80%;
    right: 15%;
    animation-delay: 4.5s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(10deg);
    }
}

.graphic-text {
    position: absolute;
    bottom: 40px;
    text-align: center;
    color: white;
    z-index: 3;
}

.graphic-text h4 {
    font-size: 32px;
    margin-bottom: 8px;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.graphic-text p {
    font-size: 18px;
    opacity: 0.9;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

/* Responsive Design */
@media (max-width: 991px) {
    .custom-graphic {
        height: 400px;
        margin-bottom: 30px;
    }
    
    .book-stack {
        width: 120px;
        height: 150px;
    }
    
    .book {
        width: 100px !important;
    }
    
    .book-1 { height: 130px; }
    .book-2 { height: 140px; }
    .book-3 { height: 135px; }
    .book-4 { height: 125px; }
}

@media (max-width: 768px) {
    .custom-graphic {
        height: 350px;
    }
    
    .graphic-text h4 {
        font-size: 28px;
    }
    
    .graphic-text p {
        font-size: 16px;
    }
}

/* Existing styles */
.feature-item {
    padding: 15px;
    border-left: 4px solid #007bff;
    background: #f8f9fa;
    border-radius: 0 8px 8px 0;
}

.feature-box {
    padding: 30px 20px;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    transition: transform 0.3s ease;
    height: 100%;
}

.feature-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.mission-vision .card {
    transition: transform 0.3s ease;
}

.mission-vision .card:hover {
    transform: translateY(-5px);
}

.cta-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 50px 30px;
    border-radius: 15px;
    color: white;
}

.cta-section h3 {
    color: white;
}

.cta-section .lead {
    color: rgba(255,255,255,0.9);
}
</style>

<?php include_once 'footer.php'; ?>
<?php include_once 'script.php'; ?>