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

// Handle contact form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (!empty($name) && !empty($surname) && !empty($email) && !empty($subject) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO contact_messages (name, surname, email, subject, message) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $surname, $email, $subject, $message]);
                
                $success_message = "Thank you for your message! We'll get back to you soon.";
                
                // Clear form fields
                $_POST = array();
            } catch (PDOException $e) {
                $error_message = "Sorry, there was an error sending your message. Please try again.";
            }
        } else {
            $error_message = "Please enter a valid email address.";
        }
    } else {
        $error_message = "Please fill all required fields.";
    }
}
?>

<!-- content  -->
<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3>Contact Us</h3>
        <span class="breadcrumb"><a href="index.php">Home</a> > Contact Us</span>
      </div>
    </div>
  </div>
</div>

<div class="contact-page section">
  <div class="container">
    <!-- Success/Error Messages -->
    <?php if ($success_message): ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $success_message ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    </div>
    <?php elseif ($error_message): ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $error_message ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-lg-6 align-self-center">
        <div class="left-text">
          <div class="section-heading">
            <h6>Contact Us</h6>
            <h2>Say Hello!</h2>
          </div>
          <p>Tuxpub is a free book PDF and E-pub downloading website. You can download, donate, and publish your books. Please tell your friends about Tuxpub. Thank you!</p>
          <ul>
            <li><span>Address</span> Sunny Isles Beach, FL 33160, United States</li>
            <li><span>Phone</span> +123 456 7890</li>
            <li><span>Email</span> Tuxpub@contact.com</li>
          </ul>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="right-content">
          <div class="row">
            <div class="col-lg-12">
              <div id="map">
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12469.776493332698!2d-80.14036379941481!3d25.907788681148624!2m3!1f357.26927939317244!2f20.870722720054623!3f0!3m2!1i1024!2i768!4f35!3m3!1m2!1s0x88d9add4b4ac788f%3A0xe77469d09480fcdb!2sSunny%20Isles%20Beach!5e1!3m2!1sen!2sth!4v1642869952544!5m2!1sen!2sth"
                  width="100%" height="325px" frameborder="0" style="border:0; border-radius: 23px;"
                  allowfullscreen=""></iframe>
              </div>
            </div>
            <div class="col-lg-12">
              <form id="contact-form" action="" method="post">
                <div class="row">
                  <div class="col-lg-6">
                    <fieldset>
                      <input type="text" name="name" id="name" placeholder="Your Name..." autocomplete="on" required 
                             value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                    </fieldset>
                  </div>
                  <div class="col-lg-6">
                    <fieldset>
                      <input type="text" name="surname" id="surname" placeholder="Your Surname..." autocomplete="on" required
                             value="<?= isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : '' ?>">
                    </fieldset>
                  </div>
                  <div class="col-lg-6">
                    <fieldset>
                      <input type="email" name="email" id="email" placeholder="Your E-mail..." required
                             value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </fieldset>
                  </div>
                  <div class="col-lg-6">
                    <fieldset>
                      <input type="text" name="subject" id="subject" placeholder="Subject..." autocomplete="on" required
                             value="<?= isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '' ?>">
                    </fieldset>
                  </div>
                  <div class="col-lg-12">
                    <fieldset>
                      <textarea name="message" id="message" placeholder="Your Message" required><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
                    </fieldset>
                  </div>
                  <div class="col-lg-12">
                    <fieldset>
                      <button type="submit" id="form-submit" class="orange-button">Send Message Now</button>
                    </fieldset>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.alert {
    border-radius: 10px;
    margin-bottom: 30px;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

/* Improve form styling */
fieldset {
    margin-bottom: 20px;
}

input, textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

input:focus, textarea:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.orange-button {
    background: #f35525;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.orange-button:hover {
    background: #d84315;
    transform: translateY(-2px);
}
</style>

<?php
include_once 'footer.php';
include_once 'script.php';
?>