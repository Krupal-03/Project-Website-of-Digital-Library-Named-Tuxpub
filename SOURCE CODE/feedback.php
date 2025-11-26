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

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    
    // Basic validation
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO feedback (name, email, subject, message, rating, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
        $stmt->execute([$name, $email, $subject, $message, $rating]);
        
        $success_message = "Thank you for your feedback! We appreciate your input and will review it shortly.";
    } else {
        $error_message = "Please fill all required fields.";
    }
}
?>

<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3>Feedback</h3>
        <span class="breadcrumb"><a href="index.php">Home</a> > Feedback</span>
      </div>
    </div>
  </div>
</div>

<div class="contact-page section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="left-content">
                            <h2>We Value Your Feedback</h2>
                            <p class="lead">Your opinions and suggestions help us improve Tuxpub and serve you better.</p>
                            <p>Whether you have suggestions for new features, found a bug, want to recommend books, or just want to share your experience, we'd love to hear from you!</p>
                            
                            <div class="contact-info mt-5">
                                <div class="info-item mb-4">
                                    <div class="icon">
                                        <i class="fa fa-envelope text-primary"></i>
                                    </div>
                                    <div class="content">
                                        <h5>Email Us</h5>
                                        <p>support@tuxpub.com</p>
                                    </div>
                                </div>
                                
                                <div class="info-item mb-4">
                                    <div class="icon">
                                        <i class="fa fa-clock text-success"></i>
                                    </div>
                                    <div class="content">
                                        <h5>Response Time</h5>
                                        <p>We typically respond within 24-48 hours</p>
                                    </div>
                                </div>
                                
                                <div class="info-item mb-4">
                                    <div class="icon">
                                        <i class="fa fa-lightbulb text-warning"></i>
                                    </div>
                                    <div class="content">
                                        <h5>Suggestions Welcome</h5>
                                        <p>Have ideas to improve Tuxpub? Let us know!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="right-content">
                            <div class="contact-form">
                                <h4>Send Us Your Feedback</h4>
                                
                                <?php if (isset($success_message)): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?= $success_message ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php elseif (isset($error_message)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?= $error_message ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" id="feedback-form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Your Name *</label>
                                                <input type="text" class="form-control" id="name" name="name" required 
                                                       value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Your Email *</label>
                                                <input type="email" class="form-control" id="email" name="email" required
                                                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="subject">Subject *</label>
                                        <input type="text" class="form-control" id="subject" name="subject" required
                                               value="<?= isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '' ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="rating">How would you rate your experience? (Optional)</label>
                                        <div class="rating-input">
                                            <div class="stars">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <input type="radio" id="rate<?= $i ?>" name="rating" value="<?= $i ?>" 
                                                           <?= (isset($_POST['rating']) && $_POST['rating'] == $i) ? 'checked' : '' ?>>
                                                    <label for="rate<?= $i ?>"><i class="fas fa-star"></i></label>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="message">Your Message *</label>
                                        <textarea class="form-control" id="message" name="message" rows="6" required 
                                                  placeholder="Please share your feedback, suggestions, or any issues you've encountered..."><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="submit" name="submit_feedback" class="btn btn-primary btn-block">
                                            <i class="fa fa-paper-plane"></i> Send Feedback
                                        </button>
                                    </div>
                                    
                                    <small class="text-muted">* Required fields</small>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FAQ Section -->
       <div class="row mt-5">
    <div class="col-lg-12">
        <div class="faq-section">
            <h3 class="text-center mb-4">Frequently Asked Questions</h3>
            <div class="accordion" id="feedbackFAQ">
                
                <!-- FAQ 1 -->
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fas fa-chevron-down mr-2"></i>
                                How long does it take to get a response?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#feedbackFAQ">
                        <div class="card-body">
                            We typically respond to all feedback within 24-48 hours. For urgent matters, please include "URGENT" in your subject line.
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-chevron-down mr-2"></i>
                                Can I suggest new books to add to the library?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#feedbackFAQ">
                        <div class="card-body">
                            Absolutely! We welcome book suggestions. Please provide as much detail as possible about the book (title, author, ISBN if available) and why you think it would be valuable to our community.
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 3 -->
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fas fa-chevron-down mr-2"></i>
                                What type of feedback is most helpful?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#feedbackFAQ">
                        <div class="card-body">
                            All feedback is valuable! However, specific feedback about features you'd like to see, bugs you've encountered, or suggestions for improving user experience are particularly helpful.
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 4 -->
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <i class="fas fa-chevron-down mr-2"></i>
                                Can I report inappropriate content?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#feedbackFAQ">
                        <div class="card-body">
                            Yes, please! If you encounter any content that violates our terms of service or seems inappropriate, please report it immediately through this form. Include the book title and specific details about the issue.
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Add this CSS for better styling -->
<style>
.accordion .card-header .btn-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
}

.accordion .card-header .btn-link:hover {
    color: #0056b3;
    text-decoration: none;
}

.accordion .card-header .btn-link:not(.collapsed) {
    color: #0056b3;
    background-color: #f8f9fa;
}

.accordion .card-header .btn-link:not(.collapsed) i {
    transform: rotate(180deg);
}

.accordion .card-header .btn-link i {
    transition: transform 0.3s ease;
    font-size: 0.8rem;
}

.accordion .card {
    margin-bottom: 10px;
    border: 1px solid #dee2e6;
}

.accordion .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0;
}

.accordion .card-header .btn {
    padding: 15px 20px;
    text-align: left;
    white-space: normal;
}

.accordion .card-body {
    padding: 20px;
    background-color: #fff;
}



/* ===== GENERAL HOVER ANIMATIONS ===== */
.contact-info .info-item {
    display: flex;
    align-items: flex-start;
    transition: all 0.3s ease;
    padding: 15px;
    border-radius: 10px;
    border-left: 4px solid transparent;
}

.contact-info .info-item:hover {
    transform: translateX(10px) scale(1.02);
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    border-left: 4px solid #007bff;
}

.contact-info .info-item .icon {
    margin-right: 15px;
    font-size: 24px;
    min-width: 30px;
    transition: all 0.3s ease;
}

.contact-info .info-item:hover .icon {
    transform: scale(1.2);
}

.contact-info .info-item .content h5 {
    margin-bottom: 5px;
    color: #333;
    transition: all 0.3s ease;
}

.contact-info .info-item:hover .content h5 {
    color: #007bff;
    transform: translateX(5px);
}

.contact-info .info-item .content p {
    margin: 0;
    color: #666;
    transition: all 0.3s ease;
}

.contact-info .info-item:hover .content p {
    transform: translateX(5px);
}

/* ===== CONTACT FORM HOVER EFFECTS ===== */
.contact-form {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.contact-form:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.form-group {
    transition: all 0.3s ease;
}

.form-group:hover {
    transform: translateX(5px);
}

.form-control {
    transition: all 0.3s ease !important;
    border: 2px solid #e9ecef;
}

.form-control:hover {
    border-color: #007bff;
    transform: scale(1.02);
    box-shadow: 0 5px 15px rgba(0,123,255,0.1);
}

.form-control:focus {
    transform: scale(1.02);
    box-shadow: 0 5px 20px rgba(0,123,255,0.2);
}

/* ===== BUTTON HOVER EFFECTS ===== */
.btn-primary {
    transition: all 0.3s ease !important;
    position: relative;
    overflow: hidden;
}

.btn-primary:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 10px 25px rgba(0,123,255,0.3);
}

.btn-primary:active {
    transform: translateY(-1px);
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s ease;
}

.btn-primary:hover::before {
    left: 100%;
}

/* ===== STAR RATING HOVER ENHANCEMENTS ===== */
.rating-input .stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    transition: all 0.3s ease;
}

.rating-input:hover .stars {
    transform: scale(1.05);
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    margin-right: 5px;
    transition: all 0.3s ease;
    position: relative;
}

.rating-input label:hover {
    transform: scale(1.3) rotate(10deg);
}

.rating-input label i {
    transition: all 0.3s ease;
    text-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.rating-input label:hover i,
.rating-input label:hover ~ label i,
.rating-input input[type="radio"]:checked ~ label i {
    color: #ffc107;
    text-shadow: 0 3px 10px rgba(255,193,7,0.4);
}

/* ===== ALERT HOVER EFFECTS ===== */
.alert {
    transition: all 0.4s ease !important;
    border: 2px solid transparent;
}

.alert:hover {
    transform: translateX(5px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.alert-success:hover {
    border-color: #28a745;
}

.alert-danger:hover {
    border-color: #dc3545;
}

.alert-dismissible .close {
    transition: all 0.3s ease !important;
}

.alert-dismissible .close:hover {
    transform: scale(1.2) rotate(90deg);
}

/* ===== FAQ SECTION HOVER EFFECTS ===== */
.faq-section .card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.faq-section .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    border-color: #007bff;
}

.faq-section .card-header {
    transition: all 0.3s ease;
}

.faq-section .card:hover .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.faq-section .card-header .btn {
    transition: all 0.3s ease !important;
    position: relative;
}

.faq-section .card-header .btn:hover {
    color: #007bff !important;
    transform: translateX(10px);
}

.faq-section .card-header .btn i {
    transition: all 0.3s ease;
}

.faq-section .card-header .btn:hover i {
    transform: rotate(180deg) scale(1.2);
    color: #007bff;
}

.faq-section .card-body {
    transition: all 0.3s ease;
}

.faq-section .card:hover .card-body {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

/* ===== PAGE HEADING HOVER EFFECTS ===== */
.page-heading {
    transition: all 0.3s ease;
}

.page-heading:hover {
    transform: translateY(-3px);
}

.page-heading h3 {
    transition: all 0.3s ease;
}

.page-heading:hover h3 {
    color: #007bff;
    transform: translateX(10px);
}

.breadcrumb a {
    transition: all 0.3s ease;
}

.breadcrumb a:hover {
    color: #007bff !important;
    transform: translateX(5px);
}

/* ===== LEFT CONTENT HOVER EFFECTS ===== */
.left-content h2 {
    transition: all 0.3s ease;
}

.left-content:hover h2 {
    color: #007bff;
    transform: translateX(5px);
}

.left-content p {
    transition: all 0.3s ease;
}

.left-content:hover p {
    transform: translateX(3px);
}

/* ===== FORM LABEL HOVER EFFECTS ===== */
.form-group label {
    transition: all 0.3s ease;
}

.form-group:hover label {
    color: #007bff;
    transform: translateX(5px);
}

/* ===== TEXTAREA SPECIFIC EFFECTS ===== */
textarea.form-control {
    transition: all 0.3s ease !important;
    resize: vertical;
    min-height: 120px;
}

textarea.form-control:hover {
    border-color: #007bff;
    transform: scale(1.01);
    box-shadow: 0 8px 20px rgba(0,123,255,0.1);
}

textarea.form-control:focus {
    transform: scale(1.01);
    box-shadow: 0 8px 25px rgba(0,123,255,0.15);
}

/* ===== REQUIRED FIELD INDICATOR ===== */
.text-muted {
    transition: all 0.3s ease;
}

.text-muted:hover {
    color: #007bff !important;
    transform: translateX(5px);
}

/* ===== SECTION HEADING ANIMATIONS ===== */
.section-heading h2,
.section-heading h6 {
    transition: all 0.3s ease;
}

.section-heading:hover h2 {
    transform: translateX(10px);
    color: #007bff;
}

.section-heading:hover h6 {
    transform: translateX(5px);
}

/* ===== MOBILE RESPONSIVENESS ===== */
@media (max-width: 768px) {
    .contact-info .info-item:hover {
        transform: translateX(5px) scale(1.01);
    }
    
    .contact-form:hover {
        transform: translateY(-3px);
    }
    
    .form-control:hover {
        transform: scale(1.01);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px) scale(1.03);
    }
    
    .faq-section .card:hover {
        transform: translateY(-3px);
    }
}

/* ===== LOADING ANIMATIONS ===== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.contact-form,
.contact-info,
.faq-section {
    animation: fadeInUp 0.6s ease-out;
}

.contact-info .info-item:nth-child(1) { animation-delay: 0.1s; }
.contact-info .info-item:nth-child(2) { animation-delay: 0.2s; }
.contact-info .info-item:nth-child(3) { animation-delay: 0.3s; }

/* ===== SMOOTH SCROLLING ===== */
html {
    scroll-behavior: smooth;
}

/* ===== ENHANCED FOCUS STATES ===== */
.form-control:focus,
.btn:focus,
.rating-input label:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.25) !important;
}

/* ===== PULSE ANIMATION FOR IMPORTANT ELEMENTS ===== */
@keyframes gentlePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

.contact-form {
    animation: gentlePulse 2s ease-in-out;
}

.btn-primary {
    animation: gentlePulse 3s ease-in-out 1s;
}

</style>
<script>
// Manual accordion functionality as fallback
document.addEventListener('DOMContentLoaded', function() {
    const accordionButtons = document.querySelectorAll('[data-toggle="collapse"]');
    
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const targetElement = document.querySelector(target);
            
            // Close all other accordion items
            if (this.getAttribute('data-parent')) {
                const parent = this.closest(this.getAttribute('data-parent'));
                const allCollapses = parent.querySelectorAll('.collapse');
                allCollapses.forEach(collapse => {
                    if (collapse !== targetElement) {
                        collapse.classList.remove('show');
                    }
                });
            }
            
            // Toggle current item
            targetElement.classList.toggle('show');
        });
    });
});




// Fallback for Bootstrap functionality
document.addEventListener('DOMContentLoaded', function() {
    // Alert dismiss functionality
    initAlertDismiss();
    
    // Star rating functionality
    initStarRating();
    
    // Form validation
    initFormValidation();
});

// Alert dismiss fallback
function initAlertDismiss() {
    const alertDismissButtons = document.querySelectorAll('.alert-dismissible .close');
    
    alertDismissButtons.forEach(button => {
        button.addEventListener('click', function() {
            const alert = this.closest('.alert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        });
    });
    
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 5000);
    });
}

// Star rating functionality
function initStarRating() {
    const starInputs = document.querySelectorAll('.rating-input input[type="radio"]');
    const starLabels = document.querySelectorAll('.rating-input label');
    
    // Add hover effects
    starLabels.forEach((label, index) => {
        // Hover effect
        label.addEventListener('mouseenter', function() {
            const ratingValue = parseInt(this.htmlFor.replace('rate', ''));
            highlightStars(ratingValue);
        });
        
        // Mouse leave - restore to selected state
        label.addEventListener('mouseleave', function() {
            const checkedInput = document.querySelector('.rating-input input[type="radio"]:checked');
            if (checkedInput) {
                const ratingValue = parseInt(checkedInput.value);
                highlightStars(ratingValue);
            } else {
                resetStars();
            }
        });
        
        // Click event
        label.addEventListener('click', function() {
            const ratingValue = parseInt(this.htmlFor.replace('rate', ''));
            highlightStars(ratingValue);
        });
    });
    
    // Initialize stars based on checked input
    const checkedInput = document.querySelector('.rating-input input[type="radio"]:checked');
    if (checkedInput) {
        const ratingValue = parseInt(checkedInput.value);
        highlightStars(ratingValue);
    }
}

function highlightStars(count) {
    const starIcons = document.querySelectorAll('.rating-input label i');
    
    starIcons.forEach((icon, index) => {
        if (index < count) {
            icon.classList.add('active');
            icon.style.color = '#ffc107'; // Gold color for active stars
        } else {
            icon.classList.remove('active');
            icon.style.color = '#e4e5e9'; // Light gray for inactive stars
        }
    });
}

function resetStars() {
    const starIcons = document.querySelectorAll('.rating-input label i');
    starIcons.forEach(icon => {
        icon.classList.remove('active');
        icon.style.color = '#e4e5e9';
    });
}

// Form validation
function initFormValidation() {
    const form = document.getElementById('feedback-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            // Clear previous error states
            clearErrors();
            
            // Validate required fields
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    showError(field, 'This field is required');
                    isValid = false;
                }
            });
            
            // Validate email format
            const emailField = form.querySelector('#email');
            if (emailField && emailField.value.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailField.value)) {
                    showError(emailField, 'Please enter a valid email address');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        // Real-time validation
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    clearError(this);
                }
            });
        });
    }
}

function validateField(field) {
    clearError(field);
    
    if (field.hasAttribute('required') && !field.value.trim()) {
        showError(field, 'This field is required');
        return false;
    }
    
    if (field.type === 'email' && field.value.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            showError(field, 'Please enter a valid email address');
            return false;
        }
    }
    
    return true;
}

function showError(field, message) {
    field.classList.add('is-invalid');
    
    // Create error message element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
    
    // Insert after the field
    field.parentNode.appendChild(errorDiv);
    
    // Add red border
    field.style.borderColor = '#dc3545';
}

function clearError(field) {
    field.classList.remove('is-invalid');
    field.style.borderColor = '';
    
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
}

function clearErrors() {
    const errorFields = document.querySelectorAll('.is-invalid');
    errorFields.forEach(field => {
        clearError(field);
    });
}
</script>
    </div>
</div>

<style>
.contact-info .info-item {
    display: flex;
    align-items: flex-start;
}

.contact-info .info-item .icon {
    margin-right: 15px;
    font-size: 24px;
    min-width: 30px;
}

.contact-info .info-item .content h5 {
    margin-bottom: 5px;
    color: #333;
}

.contact-info .info-item .content p {
    margin: 0;
    color: #666;
}

.contact-form {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.rating-input .stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    margin-right: 5px;
    transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input[type="radio"]:checked ~ label {
    color: #ffc107;
}

.faq-section .card-header .btn {
    color: #333;
    text-decoration: none;
    width: 100%;
    text-align: left;
    padding: 15px 20px;
}

.faq-section .card-header .btn:hover {
    color: #007bff;
}

.faq-section .card {
    margin-bottom: 10px;
    border: 1px solid #e9ecef;
}

/* Alert dismiss styles */
.alert-dismissible {
    position: relative;
}

.alert-dismissible .close {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.alert-dismissible .close:hover {
    opacity: 1;
}

/* Star rating styles */
.rating-input {
    margin: 10px 0;
}

.rating-input .stars {
    display: flex;
    gap: 5px;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 1.5rem;
    transition: color 0.2s;
}

.rating-input label i {
    color: #e4e5e9;
    transition: color 0.2s;
}

.rating-input label i.active {
    color: #ffc107;
}

.rating-input label:hover i,
.rating-input label:hover ~ label i {
    color: #ffc107 !important;
}

/* Form validation styles */
.is-invalid {
    border-color: #dc3545 !important;
}

.invalid-feedback {
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc3545;
    display: block;
}

/* Smooth transitions */
.alert {
    transition: opacity 0.3s ease;
}

.form-control {
    transition: border-color 0.3s ease;
}
</style>

<?php include_once 'footer.php'; ?>
<?php include_once 'script.php'; ?>