<!-- header start -->
<?php
include_once 'header.php';
?>
<!-- header over -->

<div class="page-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Search</h3>
                <span class="breadcrumb"><a href="index.php">Home</a> > search</span>
            </div>
        </div>
    </div>
</div>

<!-- content Start -->
<?php
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

$searchTerm = isset($_GET['searchKeyword']) ? trim($_GET['searchKeyword']) : '';
?>

<div class="container mt-5">
    <h3>Search Results for: <em><?= htmlspecialchars($searchTerm) ?></em></h3>

    <div class="row mt-4">
        <?php
        if ($searchTerm) {
            $likeTerm = '%' . $searchTerm . '%';
            
            // Search Books
            $bookStmt = $pdo->prepare("
                SELECT id, title, author, thumbnail, file_path 
                FROM books 
                WHERE title LIKE :title 
                   OR author LIKE :author 
                   OR isbn LIKE :isbn
            ");
            $bookStmt->execute([
                'title' => $likeTerm,
                'author' => $likeTerm,
                'isbn' => $likeTerm
            ]);
            $bookResults = $bookStmt->fetchAll();

            // Search Categories
            $categoryStmt = $pdo->prepare("
                SELECT id, name, icon 
                FROM categories 
                WHERE name LIKE :name
            ");
            $categoryStmt->execute(['name' => $likeTerm]);
            $categoryResults = $categoryStmt->fetchAll();

            // Search Publishers
            $publisherStmt = $pdo->prepare("
                SELECT id, name, icon 
                FROM publishers 
                WHERE name LIKE :name
            ");
            $publisherStmt->execute(['name' => $likeTerm]);
            $publisherResults = $publisherStmt->fetchAll();

            $hasResults = false;

            // Display Books Results
            if ($bookResults):
                $hasResults = true;
                ?>
                <div class="col-12 mb-4">
                    <h4 class="border-bottom pb-2">Books</h4>
                </div>
                <?php foreach ($bookResults as $book): ?>
                    <div class="col-md-4 mb-4">
                        
                        <div class="card h-100">
                            <a href="book-details.php?id=<?= $book['id'] ?>" >
                            <img src="<?= './admin/'. $book['thumbnail'] ?: './assets/images/default-thumbnail.png' ?>" 
                                 class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>"
                                 style=" object-fit: cover;">
                </a>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                                <p class="card-text">Author: <?= htmlspecialchars($book['author']) ?></p>
                               
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            endif;

            // Display Categories Results
            if ($categoryResults):
                $hasResults = true;
                ?>
                <div class="col-12 mb-4 mt-4">
                    <h4 class="border-bottom pb-2">Categories</h4>
                </div>
                <?php foreach ($categoryResults as $category): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 text-center">
                             <a href="category.php?id=<?= $category['id'] ?>">
                            <div class="card-body">
                                <?php if (!empty($category['icon'])): ?>
                                    <img src="<?= './admin/'. $category['icon'] ?>" 
                                         class="mb-3" alt="<?= htmlspecialchars($category['name']) ?>"
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                         </a>
                                <?php endif; ?>
                                <h5 class="card-title"><?= htmlspecialchars($category['name']) ?></h5>
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            endif;

            // Display Publishers Results
            if ($publisherResults):
                $hasResults = true;
                ?>
                <div class="col-12 mb-4 mt-4">
                    <h4 class="border-bottom pb-2">Publishers</h4>
                </div>
                <?php foreach ($publisherResults as $publisher): ?>
                    <div class="col-md-4 mb-4">
                         <a href="publisher.php?id=<?= $publisher['id'] ?>" >
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <?php if (!empty($publisher['icon'])): ?>
                                    <a href="publisher.php?id=<?= $publisher['id'] ?>" >
                                    <img src="<?= './admin/'. $publisher['icon'] ?>" 
                                         class="mb-3" alt="<?= htmlspecialchars($publisher['name']) ?>"
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                               
                                <?php endif;  ?>
                                <h5 class="card-title"><?= htmlspecialchars($publisher['name']) ?></h5>
                               
                            </div>
                        </div>
                         </a>
                    </div>
                <?php endforeach;
            endif;

            if (!$hasResults):
                echo "<div class='col-12'><p>No results found matching your search.</p></div>";
            endif;

        } else {
            echo "<div class='col-12'><p>Please enter a keyword to search.</p></div>";
        }
        ?>
    </div>
</div>

<!-- content over -->

<!-- footer start -->
<?php
include_once 'footer.php';
?>
<!-- footer over -->

<!-- script start -->
<?php
include_once 'script.php';
?>
<!-- script over -->