<?php
include_once 'header.php';
include_once 'conn.php';

// Fetch top categories
$stmtCategories = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC ");
$stmtCategories->execute();
$categories = $stmtCategories->fetchAll();
?>

<!-- Page Header -->
<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3>Categories</h3>
        <span class="breadcrumb"><a href="index.php">Home</a> > Categories</span>
      </div>
    </div>
  </div>
</div>

<!-- Categories Section -->
<div class="section categories">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <div class="section-heading">
         
        </div>
      </div>

      <?php if ($categories): ?>
        <?php foreach ($categories as $cat): ?>
          <div class="col-lg col-sm-6 col-xs-12">
            <div class="item text-center">
              <h4><?= htmlspecialchars($cat['name']) ?></h4>
              <div class="mx-auto mb-3" style="width: 200px; height: 200px; border-radius: 50%; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                <a href="category.php?id=<?= $cat['id'] ?>">
                  <img src="./admin/<?= !empty($cat['icon']) ? htmlspecialchars($cat['icon']) : 'assets/images/default-category.png' ?>" 
                       alt="<?= htmlspecialchars($cat['name']) ?>" 
                      style="width: 100%; height: 100%; object-fit: cover;" >
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center">No categories found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include_once 'footer.php'; ?>
<?php include_once 'script.php'; ?>
