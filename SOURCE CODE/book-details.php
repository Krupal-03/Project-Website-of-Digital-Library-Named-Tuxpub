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

$bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($bookId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
    $stmt->execute(['id' => $bookId]);
    $book = $stmt->fetch();
} else {
    die("Invalid book ID.");
}

if (!$book) {
    die("Book not found.");
}

$extraFiles = json_decode($book['extra_files'], true) ?? [];

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $rating = (int) $_POST['rating'];
    $comment = trim($_POST['comment']);
    
    // Basic validation
    if (!empty($user_name) && !empty($user_email) && $rating >= 1 && $rating <= 5) {
        $stmt = $pdo->prepare("INSERT INTO reviews (book_id, user_name, user_email, rating, comment, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$bookId, $user_name, $user_email, $rating, $comment]);
        
        $success_message = "Thank you for your review! It will be visible after approval.";
    } else {
        $error_message = "Please fill all required fields correctly.";
    }
}

// Fetch approved reviews for this book
$stmt = $pdo->prepare("SELECT * FROM reviews WHERE book_id = ? AND status = 'approved' ORDER BY created_at DESC");
$stmt->execute([$bookId]);
$reviews = $stmt->fetchAll();

// Calculate average rating
$stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as review_count FROM reviews WHERE book_id = ? AND status = 'approved'");
$stmt->execute([$bookId]);
$rating_stats = $stmt->fetch();
$avg_rating = $rating_stats['avg_rating'] ? round($rating_stats['avg_rating'], 1) : 0;
$review_count = $rating_stats['review_count'];

?>
<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3><?= htmlspecialchars($book['title']) ?></h3>
        <span class="breadcrumb">
          <a href="index.php">Home</a> >

          <?php
          include_once 'conn.php';

          $referrer = $_SERVER['HTTP_REFERER'] ?? '';
          $shown = false;

          if (!empty($referrer)) {
              $path = parse_url($referrer, PHP_URL_PATH);
              $pageName = basename($path);
              $query = parse_url($referrer, PHP_URL_QUERY);
              $refUrl = $pageName . ($query ? '?' . $query : '');

              // Handle different pages properly
              switch ($pageName) {

                  // ✅ Category Details Page
                  case 'category.php':
                      parse_str($query, $params);
                      $categoryId = $params['id'] ?? 0;
                      if ($categoryId) {
                          $stmtCat = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
                          $stmtCat->execute([$categoryId]);
                          $catName = $stmtCat->fetchColumn();
                          if ($catName) {
                              echo '<a href="category-list.php">Categories</a> > ';
                              echo '<a href="category.php?id=' . $categoryId . '">' . htmlspecialchars($catName) . '</a> > ';
                              $shown = true;
                          }
                      }
                      break;

                  // ✅ Publisher Details Page
                  case 'publisher.php':
                      parse_str($query, $params);
                      $publisherId = $params['id'] ?? 0;
                      if ($publisherId) {
                          $stmtPub = $pdo->prepare("SELECT name FROM publishers WHERE id = ?");
                          $stmtPub->execute([$publisherId]);
                          $publisherName = $stmtPub->fetchColumn();
                          if ($publisherName) {
                              echo '<a href="publisher-list.php">Publishers</a> > ';
                              echo '<a href="publisher.php?id=' . $publisherId . '">' . htmlspecialchars($publisherName) . '</a> > ';
                              $shown = true;
                          }
                      }
                      break;

                  // ✅ Trending or Popular Books Pages
                  case 'trending.php':
                      echo '<a href="trending.php">Trending Books</a> > ';
                      $shown = true;
                      break;

                  case 'most-popular.php':
                      echo '<a href="most-popular.php">Most Popular Books</a> > ';
                      $shown = true;
                      break;

                  // ✅ Library Page
                  case 'library.php':
                      echo '<a href="library.php">Library</a> > ';
                      $shown = true;
                      break;

                  // ✅ Search Results
                  case 'search-results.php':
                      echo '<a href="search-results.php">Search Results</a> > ';
                      $shown = true;
                      break;

                  // ✅ Home (skip because already printed)
                  case 'index.php':
                      $shown = true;
                      break;
              }
          }

          // Fallback if no referrer (direct access)
          if (!$shown && !empty($book['publisher_id'])) {
              $stmtPub = $pdo->prepare("SELECT name FROM publishers WHERE id = ?");
              $stmtPub->execute([$book['publisher_id']]);
              $publisherName = $stmtPub->fetchColumn();
              if ($publisherName) {
                  echo '<a href="publisher-list.php">Publishers</a> > ';
                  echo '<a href="publisher.php?id=' . $book['publisher_id'] . '">' . htmlspecialchars($publisherName) . '</a> > ';
                  $shown = true;
              }
          }

          // Fallback to category if no publisher breadcrumb
          if (!$shown && !empty($book['category_id'])) {
              $stmtCat = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
              $stmtCat->execute([$book['category_id']]);
              $catName = $stmtCat->fetchColumn();
              if ($catName) {
                  echo '<a href="category-list.php">Categories</a> > ';
                  echo '<a href="category.php?id=' . $book['category_id'] . '">' . htmlspecialchars($catName) . '</a> > ';
                  $shown = true;
              }
          }

          // Default fallback
          if (!$shown) {
              echo '<a href="library.php">Library</a> > ';
          }
          ?>

          <?= htmlspecialchars($book['title']) ?>
        </span>
      </div>
    </div>
  </div>
</div>

<!-- Single Book Display -->
<div class="single-product section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="left-image">
                    <img src="./admin/<?= $book['thumbnail'] ?: './assets/images/default-thumbnail.png' ?>"
                        alt="<?= htmlspecialchars($book['title']) ?>">
                </div>
            </div>
            <div class="col-lg-6 align-self-center book-details">
                <h4><?= htmlspecialchars($book['title']) ?></h4>
                
                <!-- Rating Display -->
                <div class="rating-section mb-3">
                    <div class="d-flex align-items-center">
                        <div class="stars mr-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= round($avg_rating) ? 'text-warning' : 'text-muted' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="text-muted">(<?= $avg_rating ?> • <?= $review_count ?> reviews)</span>
                    </div>
                </div>
                
                <span class="price">Pages: <?= htmlspecialchars($book['paperback']) ?></span>
                <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
                <ul>
                    <li><span>Author:</span> <?= htmlspecialchars($book['author']) ?></li>
                    <li><span>ISBN:</span> <?= htmlspecialchars($book['isbn']) ?></li>
                    <li><span>Publisher:</span> <?= htmlspecialchars($book['publisher']) ?></li>
                    <li><span>Language:</span> <?= htmlspecialchars($book['language']) ?></li>
                    <li><span>Edition:</span> <?= htmlspecialchars($book['edition']) ?></li>
                    <li><span>Publish Date:</span> <?= htmlspecialchars($book['publish_date']) ?></li>
                </ul>
                <br>
                <a href="download.php?id=<?= $book['id'] ?>" class="btn btn-primary" target="_blank"><i
                        class="fa fa-download"></i> Download Book</a>
            </div>

            <div class="col-lg-12 mt-5">
                <div class="row">
                    <h5>Extra Images:</h5>
                    <?php if (!empty($extraFiles)): ?>
                        <?php foreach ($extraFiles as $file): ?>
                            <div class="col-md-3 mb-3">
                                <img src="./admin/<?= $file ?>" class="img-fluid rounded border" alt="Extra Image">
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No extra images available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="reviews-section">
                    <h4>Reader Reviews (<?= $review_count ?>)</h4>
                    
                    <!-- Add Review Form -->
                    <div class="add-review-form mb-5">
                        <h5>Write a Review</h5>
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?= $success_message ?></div>
                        <?php elseif (isset($error_message)): ?>
                            <div class="alert alert-danger"><?= $error_message ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="width:100%;">
                                        <label for="user_name">Your Name *</label></br>
                                        <input type="text" class="form-control" id="user_name" name="user_name" required style="width:100%;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="width:100%;">
                                        <label for="user_email">Your Email *</label></br>
                                        <input type="email" class="form-control" id="user_email" name="user_email" required style="width:100%;">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="rating">Rating *</label>
                                <div class="rating-input">
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                                            <label for="star<?= $i ?>"><i class="fas fa-star"></i></label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="comment">Your Review</label>
                                <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Share your thoughts about this book..."></textarea>
                            </div>
                            <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                    
                    <!-- Reviews List -->
                    <div class="reviews-list">
                        <?php if (!empty($reviews)): ?>
                            <?php foreach ($reviews as $review): ?>
                                <div class="review-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($review['user_name']) ?></h6>
                                            <div class="stars mb-2">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>" style="font-size: 12px;"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <small class="text-muted"><?= date('M j, Y', strtotime($review['created_at'])) ?></small>
                                    </div>
                                    <?php if (!empty($review['comment'])): ?>
                                        <p class="mb-0"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No reviews yet. Be the first to review this book!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    /* 🔹 Book details fade + zoom animation */
.book-details {
  opacity: 0;
  transform: scale(0.95);
  animation: fadeZoomIn 1s ease-out forwards;
  animation-delay: 0.2s;
}

@keyframes fadeZoomIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* 🔹 Text hover effect — subtle growth */
.book-details h4,
.book-details p,
.book-details ul li,
.book-details span {
  transition: transform 0.3s ease;
}

.book-details:hover h4,
.book-details:hover p,
.book-details:hover ul li,
.book-details:hover span {
  transform: scale(1.02);
}

/* 🔹 Button animation — gradient shimmer + hover effect */
.book-details .btn-primary {
  background: linear-gradient(120deg, #007bff, #00c6ff);
  background-size: 200% 200%;
  color: #fff;
  border: none;
  padding: 10px 25px;
  font-weight: 600;
  border-radius: 8px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  animation: gradientFlow 4s ease infinite;
}

@keyframes gradientFlow {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

/* Hover state for button */
.book-details .btn-primary:hover {
  transform: scale(1.08);
  box-shadow: 0 8px 20px rgba(0, 198, 255, 0.4);
}
    /* ✨ Book details on-load fade + slide animation */
.book-details {
  opacity: 0;
  transform: translateY(40px);
  animation: fadeSlideUp 1s ease-out forwards;
  animation-delay: 0.3s; /* small delay for smoother entry */
}

@keyframes fadeSlideUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Optional hover lift for the download button */
.book-details .btn-primary {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.book-details .btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15);
}
    /* ✨ Review hover zoom animation */
.review-item {
  background: #fff;
  border-radius: 10px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
  padding: 15px;
}

.review-item:hover {
  transform: scale(1.03);
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
}

/* Optional: add a smooth shadow lift for the form too */
.add-review-form {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.add-review-form:hover {
  transform: scale(1.01);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}
/* 🔍 Hover zoom effect for book and extra images */
.left-image img,
.col-md-3 img {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}

.left-image img:hover,
.col-md-3 img:hover {
  transform: scale(1.08);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* 🖼️ Lightbox overlay */
.image-lightbox {
  display: none;
  position: fixed;
  z-index: 1050;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  align-items: center;
  justify-content: center;
}

/* Large image */
.image-lightbox img {
  max-width: 90%;
  max-height: 90%;
  border-radius: 12px;
  box-shadow: 0 0 25px rgba(255, 255, 255, 0.3);
}

/* Close button */
.image-lightbox .close-btn {
  position: absolute;
  top: 25px;
  right: 35px;
  font-size: 40px;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.image-lightbox .close-btn:hover {
  transform: scale(1.2);
  color: #ff4444;
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

.rating-input input[type="radio"]:checked + label {
    color: #ffc107;
}

.stars.text-warning {
    color: #ffc107;
}

</style>
<!-- 🖼️ Lightbox Popup -->
<div class="image-lightbox" id="imageLightbox">
  <span class="close-btn" id="closeLightbox">&times;</span>
  <img src="" alt="Large View" id="lightboxImg">
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const lightbox = document.getElementById('imageLightbox');
  const lightboxImg = document.getElementById('lightboxImg');
  const closeBtn = document.getElementById('closeLightbox');

  function openLightbox(src) {
    lightboxImg.src = src;
    lightbox.style.display = 'grid';
  }

  function closeLightbox() {
    lightbox.style.display = 'none';
    lightboxImg.src = '';
  }

  document.querySelectorAll('.left-image img, .col-md-3 img').forEach(img => {
    img.addEventListener('click', () => openLightbox(img.src));
  });

  closeBtn.addEventListener('click', closeLightbox);

  // Close when clicking outside the image
  lightbox.addEventListener('click', e => {
    if (e.target === lightbox) closeLightbox();
  });
});
</script>

<?php include_once 'footer.php'; ?>
<?php include_once 'script.php'; ?>