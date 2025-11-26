<?php
include_once 'header.php';
include_once 'conn.php';
?>

<!-- Breadcrumb Section -->
<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3>Most Popular Books</h3>
        <span class="breadcrumb"><a href="index.php">Home</a> > Most Popular</span>
      </div>
    </div>
  </div>
</div>

<!-- Most Popular Books Section -->
<div class="section most-popular">
  <div class="container mt-5">
    <div class="row mb-4">
      <div class="col-lg-12 text-center">
        <div class="section-heading">
        
        </div>
      </div>
    </div>

    <div class="row">
      <?php
      // Fetch all books ordered by downloads (most popular first)
      $stmtPopular = $pdo->prepare("SELECT * FROM books ORDER BY downloads DESC");
      $stmtPopular->execute();
      $books = $stmtPopular->fetchAll();

      if ($books):
        foreach ($books as $book):
      ?>
          <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <a href="book-details.php?id=<?= $book['id'] ?>">
                <img 
                  src="./admin/<?= !empty($book['thumbnail']) ? htmlspecialchars($book['thumbnail']) : 'assets/images/default-thumbnail.jpg' ?>" 
                  alt="<?= htmlspecialchars($book['title']) ?>" 
                  class="card-img-top img-fluid" 
                  style="height:250px; object-fit:cover;">
              </a>
              <div class="card-body text-center">
                <span class="text-muted small d-block mb-1"><?= htmlspecialchars($book['language']) ?></span>
                <h6 class="fw-semibold mb-2"><?= htmlspecialchars($book['title']) ?></h6>
                <p class="small text-muted mb-2">Downloads: <?= htmlspecialchars($book['downloads']) ?></p>
              
              </div>
            </div>
          </div>
      <?php
        endforeach;
      else:
        echo "<p class='text-center'>No popular books found.</p>";
      endif;
      ?>
    </div>
  </div>
</div>

<?php
include_once 'footer.php';
include_once 'script.php';
?>
