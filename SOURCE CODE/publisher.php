<!-- header  start  -->
<?php
include_once 'header.php';
?>

<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3>Publisher Details</h3>
        <span class="breadcrumb">
          <a href="index.php">Home</a> > 
          <a href="publisher-list.php">Publishers</a> > 
          <?php
          include_once 'conn.php';
          $publisherId = isset($_GET['id']) ? intval($_GET['id']) : 0;

          if ($publisherId > 0) {
              $stmtPubName = $pdo->prepare("SELECT name FROM publishers WHERE id = ?");
              $stmtPubName->execute([$publisherId]);
              $pubName = $stmtPubName->fetchColumn();
              echo htmlspecialchars($pubName ?: 'Unknown');
          } else {
              echo "Invalid Publisher";
          }
          ?>
        </span>
      </div>
    </div>
  </div>
</div>


<!-- content Start  -->












<?php
include_once 'conn.php';

// Get publisher ID from URL
$publisherId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($publisherId <= 0) {
    echo "<p class='text-center mt-5'>Invalid publisher.</p>";
    include_once 'footer.php';
    exit;
}

// Fetch publisher details
$stmtPub = $pdo->prepare("SELECT * FROM publishers WHERE id = ?");
$stmtPub->execute([$publisherId]);
$publisher = $stmtPub->fetch();

if (!$publisher) {
    echo "<p class='text-center mt-5'>Publisher not found.</p>";
    include_once 'footer.php';
    exit;
}

// Fetch books linked to this publisher
$stmtBooks = $pdo->prepare("SELECT * FROM books WHERE publisher_id = ?");
$stmtBooks->execute([$publisherId]);
$books = $stmtBooks->fetchAll();
?>

<div class="container mt-5">
  <div class="text-center mb-4">
    <?php
    // Set publisher icon path with fallback
    $iconPath = !empty($publisher['icon'])
        ? './admin/' . htmlspecialchars($publisher['icon'])
        : './admin/uploads/publishers/default-thumbnail.png';
    ?>
    
    <!-- Publisher Thumbnail -->
    <img src="<?= $iconPath ?>" 
         alt="<?= htmlspecialchars($publisher['name']) ?>" 
         class="img-fluid rounded-circle shadow-sm mb-3" 
         style="max-height: 200px; max-width:200px;">

    <!-- Publisher Name -->
    <h2><?= htmlspecialchars($publisher['name']) ?></h2>
  </div>


    <div class="row">
        <?php if ($books): ?>
            <?php foreach ($books as $book): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="item">
                        <div class="thumb">
                            <a href="book-details.php?id=<?= $book['id'] ?>">
                                <img src="<?= !empty($book['thumbnail']) ? './admin/' . htmlspecialchars($book['thumbnail']) : 'assets/images/default-thumbnail.jpg' ?>"
                                    alt="<?= htmlspecialchars($book['title']) ?>" class="img-fluid">
                            </a>
                        </div>
                        <div class="down-content">
                            <span class="category"><?= htmlspecialchars($book['language']) ?></span>
                            <h4><?= htmlspecialchars($book['title']) ?></h4>
                           
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center">No books found for this publisher.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- content over  -->



<!-- footer start  -->
<?php
include_once 'footer.php';
?>
<!-- footer over   -->






<!-- script start  -->
<?php
include_once 'script.php';
?>
<!-- script over  -->