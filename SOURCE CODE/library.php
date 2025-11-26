<?php
include_once 'header.php';

// DB connection
$host = 'localhost';     // your DB host
$db = 'tuxdb';         // your DB name
$user = 'root';          // your DB user
$pass = '';              // your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  exit('Database connection failed: ' . $e->getMessage());
}

// Pagination setup
$itemsPerPage = 6; // number of books per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
  $page = 1;
$offset = ($page - 1) * $itemsPerPage;

// Get total number of books
$stmtTotal = $pdo->query("SELECT COUNT(*) FROM books");
$totalBooks = $stmtTotal->fetchColumn();
$totalPages = ceil($totalBooks / $itemsPerPage);

// Fetch books for current page
$stmt = $pdo->prepare("SELECT * FROM books ORDER BY upload_date DESC LIMIT :offset, :items");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':items', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$books = $stmt->fetchAll();

?>

<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3>Library</h3>
        <span class="breadcrumb"><a href="index.php">Home</a> > Library</span>
      </div>
    </div>
  </div>
</div>

<div class="section trending">
  <div class="container">
    <ul class="trending-filter">
      <li><a class="is_active" href="library.php">Show All</a></li>
      <!-- You can add category filters if needed -->
    </ul>

    <div class="row trending-box">
      <?php if (count($books) > 0): ?>
        <?php foreach ($books as $book): ?>
          <div class="col-lg-4 col-md-6 align-self-center mb-30 trending-items">
            <div class="item">
              <div class="thumb">
                <a href="book-details.php?id=<?= htmlspecialchars($book['id']) ?>">
                  <img
                    src="./admin/<?= !empty($book['thumbnail']) ? htmlspecialchars($book['thumbnail']) : 'assets/images/default-thumbnail.jpg' ?>"
                    alt="<?= htmlspecialchars($book['title']) ?>">
                </a>
                <!-- Placeholder price -->
              </div>
              <div class="down-content">
                <span class="category"><?= htmlspecialchars($book['language']) ?></span>
                <h4><?= htmlspecialchars($book['title']) ?></h4>

  


               
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No books found.</p>
      <?php endif; ?>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <ul class="pagination">
          <!-- Previous Page Link -->
          <li>
            <a href="?page=<?= max(1, $page - 1) ?>">&lt;</a>
          </li>

          <!-- Page Number Links -->
          <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <li>
              <a href="?page=<?= $p ?>" class="<?= $p == $page ? 'is_active' : '' ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>

          <!-- Next Page Link -->
          <li>
            <a href="?page=<?= min($totalPages, $page + 1) ?>">&gt;</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php
include_once 'footer.php';
include_once 'script.php';
?>