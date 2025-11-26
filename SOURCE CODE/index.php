<?php
include_once 'header.php';

$host = 'localhost';
$db = "tuxdb";
$user = "root";
$pass = "";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Better error messages
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Fetch the latest book thumbnail
$stmt = $pdo->prepare("SELECT id, thumbnail FROM books WHERE is_latest = 1 ORDER BY upload_date DESC LIMIT 1");
$stmt->execute();
$latestBook = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>

/* ===== Hero Caption Animations ===== */
.caption.header-text {
  animation: fadeInUp 1s ease forwards;
}

.caption.header-text h6,
.caption.header-text h2,
.caption.header-text p {
  transition: transform 0.3s ease, color 0.3s ease;
}

.caption.header-text h6:hover,
.caption.header-text h2:hover,
.caption.header-text p:hover {
  transform: scale(1.05);
  color: #00bcd4;
}

/* ===== Search Button Animation ===== */
.search-input button {
  background: linear-gradient(90deg, #00bcd4, #2196f3);
  color: white;
  border: none;
  padding: 10px 25px;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 30px;
  cursor: pointer;
  transition: all 0.4s ease;
  box-shadow: 0 0 10px rgba(33, 150, 243, 0.4);
}

.search-input button:hover {
  transform: scale(1.1);
  background: linear-gradient(90deg, #2196f3, #00bcd4);
  box-shadow: 0 0 20px rgba(33, 150, 243, 0.7), 0 0 30px rgba(0, 188, 212, 0.5);
}

/* ===== Input Field Style ===== */
.search-input input {
  padding: 10px 15px;
  border-radius: 25px;
  border: 2px solid #00bcd4;
  transition: 0.3s ease;
}

.search-input input:focus {
  border-color: #2196f3;
  box-shadow: 0 0 8px rgba(0, 188, 212, 0.4);
  outline: none;
}

/* ===== Fade In Animation ===== */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}


</style>
<div class="main-banner">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 align-self-center">
        <div class="caption header-text">
          <h6>Welcome to Tuxpub</h6>
          <h2>BEST SITE EVER!</h2>
          <p>Tuxpub is a free book PDF and E-pub downloading website. You can download, donate, and publish your books.
            Please tell your friends about Tuxpub.</p>
          <div class="search-input">
            <form id="search" action="search-results.php" method="get">
              <input type="text" placeholder="Type book title, author..." id="searchText" name="searchKeyword" required />
              <button role="button">Search Now</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-4 offset-lg-2">
        <div class="right-image">
          <?php if ($latestBook && !empty($latestBook['thumbnail'])): ?>
            <a href="book-details.php?id=<?= htmlspecialchars($latestBook['id']) ?>">
              <img src="./admin/<?= htmlspecialchars($latestBook['thumbnail']) ?>" alt="Latest Book" class="img-fluid rounded shadow">
            </a>
          <?php else: ?>
            <img src="./assets/images/default-thumbnail.png" alt="Default Thumbnail" class="img-fluid rounded shadow">
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="features">
  <div class="container0">
    <div class="row">
      <div class="col-lg-3 col-md-6">
        <a href="#">
          <div class="item">
            <div class="image">
              <img src="assets/images/featured-01.png" alt="" style="max-width: 44px;">
            </div>
            <h4>Free Download</h4>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="publisher-list.php">
          <div class="item">
            <div class="image">
              <img src="assets/images/featured-02.png" alt="" style="max-width: 44px;">
            </div>
            <h4>Publishers</h4>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="#">
          <div class="item">
            <div class="image">
              <img src="assets/images/featured-03.png" alt="" style="max-width: 44px;">
            </div>
            <h4>Unlimited</h4>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="#">
          <div class="item">
            <div class="image">
              <img src="assets/images/featured-04.png" alt="" style="max-width: 44px;">
            </div>
            <h4>Easy to Use</h4>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Top Publishers Section -->
<div class="section trending">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="section-heading">
          <h6>TOP PUBLISHERS</h6>
          <h2>Featured Publishers</h2>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="main-button">
          <a href="publisher-list.php">View All</a>
        </div>
      </div>
    </div>

    <div class="row">
      <?php
      // Fetch top publishers (you can modify the query to order by popularity if needed)
      $stmtPublishers = $pdo->prepare("SELECT * FROM publishers ORDER BY name ASC LIMIT 4");
      $stmtPublishers->execute();
      $topPublishers = $stmtPublishers->fetchAll();

      if ($topPublishers):
        foreach ($topPublishers as $publisher):
          $iconPath = !empty($publisher['icon']) ? './admin/'.$publisher['icon'] : './assets/images/default-publisher.png';
      ?>
          <div class="col-lg-3 col-md-6">
            <div class="item">
              <div class="thumb">
                <a href="publisher.php?id=<?= htmlspecialchars($publisher['id']) ?>">
                  <img
                    src="<?= htmlspecialchars($iconPath) ?>"
                    alt="<?= htmlspecialchars($publisher['name']) ?>"
                    style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                </a>
              </div>
              <div class="down-content text-center">
                <h4><?= htmlspecialchars($publisher['name']) ?></h4>
                
              </div>
            </div>
          </div>
      <?php
        endforeach;
      else:
        ?>
        <p class="text-center">No publishers found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
<!-- Top Publishers Section End -->

<div class="section trending">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="section-heading">
          <h6>Trending</h6>
          <h2>Trending Books</h2>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="main-button">
          <a href="trending.php">View All</a>
        </div>
      </div>
    </div>

    <div class="row">
      <?php
      // Query to fetch 4 latest books
      $stmtTrending = $pdo->prepare("SELECT * FROM books ORDER BY upload_date DESC LIMIT 4");
      $stmtTrending->execute();
      $trendingBooks = $stmtTrending->fetchAll();

      if ($trendingBooks):
        foreach ($trendingBooks as $book):
          ?>
          <div class="col-lg-3 col-md-6">
            <div class="item">
              <div class="thumb">
                <a href="book-details.php?id=<?= htmlspecialchars($book['id']) ?>">
                  <img
                    src="./admin/<?= !empty($book['thumbnail']) ? htmlspecialchars($book['thumbnail']) : 'assets/images/default-thumbnail.jpg' ?>"
                    alt="<?= htmlspecialchars($book['title']) ?>">
                </a>
              </div>
              <div class="down-content">
                <span class="category"><?= htmlspecialchars($book['language']) ?></span>
                <h4><?= htmlspecialchars($book['title']) ?></h4>
              
              </div>
            </div>
          </div>
          <?php
        endforeach;
      else:
        ?>
        <p>No trending books found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>


<div class="section trending">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="section-heading">
          <h6>TOP BOOKS</h6>
          <h2>Most Popular</h2>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="main-button">
          <a href="most-popular.php">View All</a>
        </div>
      </div>
    </div>

    <div class="row">
      <?php
      // Fetch top 6 books ordered by downloads (popularity)
      $stmtTopBooks = $pdo->prepare("SELECT * FROM books ORDER BY downloads DESC LIMIT 8");
      $stmtTopBooks->execute();
      $topBooks = $stmtTopBooks->fetchAll();

      if ($topBooks):
        foreach ($topBooks as $book):
      ?>
          <div class="col-lg-3 col-md-6">
            <div class="item">
              <div class="thumb">
                <a href="book-details.php?id=<?= htmlspecialchars($book['id']) ?>">
                  <img
                    src="./admin/<?= !empty($book['thumbnail']) ? htmlspecialchars($book['thumbnail']) : 'assets/images/default-thumbnail.jpg' ?>"
                    alt="<?= htmlspecialchars($book['title']) ?>">
                </a>
              </div>
              <div class="down-content">
                <span class="category"><?= htmlspecialchars($book['language']) ?></span>
                <h4><?= htmlspecialchars($book['title']) ?></h4>
                
              </div>
            </div>
          </div>
      <?php
        endforeach;
      else:
        ?>
        <p>No top books found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>




<div class="section categories">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center mb-4">
        <div class="section-heading">
          <h6>Categories</h6>
          <h2>Top Categories</h2>
        </div>
      </div>

      <?php
      // Fetch categories from database
      $stmtCategories = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC LIMIT 5");
      $stmtCategories->execute();
      $categories = $stmtCategories->fetchAll();

      if ($categories):
        foreach ($categories as $category):
      ?>
          <div class="col-lg col-sm-6 col-xs-12">
            <div class="card border-0 shadow-sm h-100 text-center">
              <div class="mx-auto mb-3" style="width: 200px; height: 200px; border-radius: 50%; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                <a href="category.php?id=<?= $category['id'] ?>">
                  <img 
                    src="<?= !empty($category['icon']) ? './admin/' . htmlspecialchars($category['icon']) : 'assets/images/default-category.jpg' ?>"
                    alt="<?= htmlspecialchars($category['name']) ?>"style="width: 100%; height: 100%; object-fit: cover;">
                </a>
              </div>
              <div class="card-body p-2">
                <h6 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($category['name']) ?></h6>
              </div>
            </div>
          </div>
      <?php
        endforeach;
      else:
        echo '<p class="text-center">No categories found.</p>';
      endif;
      ?>
    </div>
  </div>
</div>


<!-- style="max-height:235px;max-width:220px" -->

<div class="section cta">
  <div class="container">
    <div class="row">
      <div class="col-lg-5">
        <div class="shop">
          <div class="row">
            <div class="col-lg-12">
              <div class="section-heading">
                <h6>Library</h6>
                <h2>Get Best <em>books</em> For You!</h2>
              </div>
              <p>
                Explore a world of knowledge with our digital library. Download, read, and share top tech, programming, and web development books — all in one place. Stay ahead in coding and open-source learning with Tuxpub.</p>
             </p>
              <div class="main-button">
                <a href="library.php">View Books</a>
              </div>
            </div>
          </div>
        </div>
      </div>
       <div class="col-lg-5 offset-lg-2 align-self-end">
  <div class="subscribe">
    <div class="row">
      <div class="col-lg-12">
        <div class="section-heading">
          <h6>NEWSLETTER</h6>
          <h2>Join Our <em>YouTube</em> Community!</h2>
        </div>
        <p>Stay updated with our latest tutorials, tech tips, and book uploads. Subscribe to our YouTube channel for more content!</p>
        <div class="main-button mt-3">
          <a href="https://www.youtube.com/@YOUR_CHANNEL_LINK_HERE" target="_blank">
            <i class="fa fa-youtube-play"></i> Subscribe on YouTube
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

    </div>
  </div>
</div>




<?php
include_once 'footer.php';


?>
<?php
include_once 'script.php';


?>