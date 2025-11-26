<?php
include_once 'header.php';
include_once 'conn.php';
?>

<!-- Breadcrumb Start -->
<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3>Our Publishers</h3>
        <span class="breadcrumb"><a href="index.php">Home</a> > Publishers</span>
      </div>
    </div>
  </div>
</div>
<!-- Breadcrumb Over -->

<!-- Publishers Section -->
 <div class="section categories">
<div class="container mt-5">
  
  <div class="row">

    <?php
    // Fetch all publishers
    $stmt = $pdo->prepare("SELECT * FROM publishers ORDER BY name ASC");
    $stmt->execute();
    $publishers = $stmt->fetchAll();

    if ($publishers):
      foreach ($publishers as $publisher):
        $iconPath = !empty($publisher['icon']) ? './admin/'.$publisher['icon'] : './admin/uploads/publishers/default-thumbnail.png';

        ?>
        <div class="col-lg col-sm-6 col-xs-12">
          <div class="item text-center">
               <h4><?= htmlspecialchars($publisher['name']) ?></h4>
          <div class="mx-auto mb-3" style="width: 200px; height: 200px; border-radius: 50%; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
 
          <a href="publisher.php?id=<?= $publisher['id'] ?>" title="<?= htmlspecialchars($publisher['name']) ?>">
            <img src="<?= htmlspecialchars($iconPath) ?>" alt="<?= htmlspecialchars($publisher['name']) ?>"
              class="img-fluid rounded mb-2" style="width: 100%; height: 100%; object-fit: cover;">
            <div><?= htmlspecialchars($publisher['name']) ?></div>
          </a>
            </div>
          </div>
        </div>
        <?php
      endforeach;
    else:
      echo '<p class="text-center">No publishers found.</p>';
    endif;
    ?>
  </div>
</div>
</div>
<!-- Publishers Section End -->

<?php include_once 'footer.php'; ?>
<?php include_once 'script.php'; ?>