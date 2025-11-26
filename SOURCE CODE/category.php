<?php
include_once 'header.php';
include_once 'conn.php';

$categoryId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($categoryId <= 0) {
    die("Invalid category ID.");
}

// Fetch category info
$stmtCat = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
$stmtCat->execute(['id' => $categoryId]);
$category = $stmtCat->fetch();

if (!$category) {
    die("Category not found.");
}

// Fetch books in this category
$stmtBooks = $pdo->prepare("SELECT * FROM books WHERE category_id = :id ORDER BY upload_date DESC");
$stmtBooks->execute(['id' => $categoryId]);
$books = $stmtBooks->fetchAll();
?>

<!-- Category Banner -->
<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3>Category Details</h3>
        <span class="breadcrumb">
          <a href="index.php">Home</a> > 
          <a href="category-list.php">Categories</a> > 
          <?= htmlspecialchars($category['name']) ?>
        </span>
      </div>
    </div>
  </div>
</div>

<!-- Category Details -->
<div class="container mt-5">
  <div class="text-center mb-4">
    <?php
    $iconPath = !empty($category['icon'])
        ? './admin/' . htmlspecialchars($category['icon'])
        : './admin/uploads/categories/default-thumbnail.png';
    ?>
  <div class="mx-auto mb-3" 
     style="width: 200px; height: 200px; border-radius: 50%; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
  <img src="<?= $iconPath ?>" 
       alt="<?= htmlspecialchars($category['name']) ?>" 
       style="width: 100%; height: 100%; object-fit: cover;">
</div>

    <h2><?= htmlspecialchars($category['name']) ?></h2>
  </div>

  <div class="row">
    <?php if ($books): ?>
      <?php foreach ($books as $book): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
          <div class="item border rounded shadow-sm h-100">
            <div class="thumb" 
                 style=" overflow: hidden; border-bottom: 1px solid #eee;">
              <a href="book-details.php?id=<?= $book['id'] ?>">
                <img src="<?= !empty($book['thumbnail']) 
                              ? './admin/' . htmlspecialchars($book['thumbnail']) 
                              : 'assets/images/default-thumbnail.jpg' ?>" 
                     alt="<?= htmlspecialchars($book['title']) ?>" 
                     class="img-fluid w-100 h-100" 
                     style="object-fit: cover;">
              </a>
            </div>
            <div class="down-content p-3">
              <span class="category text-muted small d-block mb-1"><?= htmlspecialchars($book['language']) ?></span>
              <h5 class="fw-bold mb-2" style="min-height: 40px;"><?= htmlspecialchars($book['title']) ?></h5>
              
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <p class="text-center">No books found in this category.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include_once 'footer.php'; ?>
<?php include_once 'script.php'; ?>
