<?php

ob_start();

?>



<?php
// Include database connection file
include_once 'conn.php';

// Fetch total number of books
$totalBooksQuery = "SELECT COUNT(*) AS total_books FROM books";
$stmt = $pdo->query($totalBooksQuery);
$totalBooksData = $stmt->fetch();
$totalBooks = $totalBooksData['total_books'];

// Fetch total number of publishers
$totalPublishersQuery = "SELECT COUNT(*) AS total_publishers FROM publishers";
$stmt = $pdo->query($totalPublishersQuery);
$totalPublishersData = $stmt->fetch();
$totalPublishers = $totalPublishersData['total_publishers'];

// Fetch total number of categories
$totalCategoriesQuery = "SELECT COUNT(*) AS total_categories FROM categories";
$stmt = $pdo->query($totalCategoriesQuery);
$totalCategoriesData = $stmt->fetch();
$totalCategories = $totalCategoriesData['total_categories'];

// Add this after the existing statistics queries in dashboard.php
// Fetch total number of reviews
$totalReviewsQuery = "SELECT COUNT(*) AS total_reviews FROM reviews";
$stmt = $pdo->query($totalReviewsQuery);
$totalReviewsData = $stmt->fetch();
$totalReviews = $totalReviewsData['total_reviews'];

// Fetch pending reviews count
$pendingReviewsQuery = "SELECT COUNT(*) AS pending_reviews FROM reviews WHERE status = 'pending'";
$stmt = $pdo->query($pendingReviewsQuery);
$pendingReviewsData = $stmt->fetch();
$pendingReviews = $pendingReviewsData['pending_reviews'];


// Add this after the existing statistics queries in dashboard.php
// Fetch total contact messages
$totalMessagesQuery = "SELECT COUNT(*) AS total_messages FROM contact_messages";
$stmt = $pdo->query($totalMessagesQuery);
$totalMessagesData = $stmt->fetch();
$totalMessages = $totalMessagesData['total_messages'];

// Fetch unread messages count
$unreadMessagesQuery = "SELECT COUNT(*) AS unread_messages FROM contact_messages WHERE status = 'unread'";
$stmt = $pdo->query($unreadMessagesQuery);
$unreadMessagesData = $stmt->fetch();
$unreadMessages = $unreadMessagesData['unread_messages'];


// Fetch donated books statistics
$totalDonatedBooksQuery = "SELECT COUNT(*) AS total_donated FROM donated_books";
$stmt = $pdo->query($totalDonatedBooksQuery);
$totalDonatedBooksData = $stmt->fetch();
$totalDonatedBooks = $totalDonatedBooksData['total_donated'];

// Fetch pending donated books count
$pendingDonatedBooksQuery = "SELECT COUNT(*) AS pending_donated FROM donated_books WHERE status = 'pending'";
$stmt = $pdo->query($pendingDonatedBooksQuery);
$pendingDonatedBooksData = $stmt->fetch();
$pendingDonatedBooks = $pendingDonatedBooksData['pending_donated'];

// Fetch approved donated books count
$approvedDonatedBooksQuery = "SELECT COUNT(*) AS approved_donated FROM donated_books WHERE status = 'approved'";
$stmt = $pdo->query($approvedDonatedBooksQuery);
$approvedDonatedBooksData = $stmt->fetch();
$approvedDonatedBooks = $approvedDonatedBooksData['approved_donated'];

// Use your existing $pdo connection
$stmt = $pdo->query("SELECT SUM(downloads) as total_downloads FROM books");
$row = $stmt->fetch();
$totalDownloads = $row['total_downloads'] ?? 0; // Fallback if null
?>

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <!-- styles links -->
    <?php include_once "./includes/style.php"; ?>
    <!-- /.styles links -->
  </head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Preloader -->

    <!-- Navbar -->
    <?php include_once "includes/header.php" ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include_once "includes/sidebar.php" ?>
    <!-- ./ Main Sidebar Container -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php echo $totalBooks; ?></h3>
                  <p>Books</p>
                </div>
                <div class="icon">
                  <i class="ion ion-filing"></i>
                </div>
                <a href="./book.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?php echo $totalPublishers; ?><sup style="font-size: 20px"></sup></h3>
                  <p>Publishers</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-stalker"></i>
                </div>
                <a href="./publishers_list.php" class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?php echo $totalCategories; ?></h3>
                  <p>Categories</p>
                </div>
                <div class="icon">
                  <i class="ion ion-navicon-round"></i>
                </div>
                <a href="./categories_list.php" class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

         


            <?php
// demo

    

//- demo end -




// Use your existing $pdo connection
$stmt = $pdo->query("SELECT SUM(downloads) as total_downloads FROM books");
$row = $stmt->fetch();
$totalDownloads = $row['total_downloads'] ?? 0; // Fallback if null
?>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
  <!-- small box -->
  <div class="small-box bg-primary">
    <div class="inner">
      <h3><?= $totalDownloads ?></h3>
      <p>Downloads</p>
    </div>
    <div class="icon">
      <i class="ion ion-arrow-down-a"></i>
    </div>
    <a href="#" class="small-box-footer"> Nothing</a>
  </div>
</div>
<!-- Add this in the statistics row in dashboard.php -->
<div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box  bg-purple">
        <div class="inner">
            <h3><?php echo $totalReviews; ?></h3>
            <p>Total Reviews</p>
        </div>
        <div class="icon">
            <i class="ion ion-chatbubbles"></i>
        </div>
        <a href="./reviews_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>






   <!-- Donated Books Cards -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-pink">
                <div class="inner">
                  <h3><?php echo $totalDonatedBooks; ?></h3>
                  <p>Total Donated Books</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-book"></i>
                </div>
                <a href="./donated_books_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-cyan">
                <div class="inner">
                  <h3><?php echo $pendingDonatedBooks; ?></h3>
                  <p>Pending Donations</p>
                </div>
                <div class="icon">
                  <i class="ion ion-clock"></i>
                </div>
                <a href="./donated_books_list.php?status=pending" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-lime">
                <div class="inner">
                  <h3><?php echo $approvedDonatedBooks; ?></h3>
                  <p>Approved Donations</p>
                </div>
                <div class="icon">
                  <i class="ion ion-checkmark"></i>
                </div>
                <a href="./donated_books_list.php?status=approved" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <!-- Add this in the statistics row in dashboard.php -->
           

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3><?php echo $pendingReviews; ?></h3>
                        <p>Pending Reviews</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-alert"></i>
                    </div>
                    <a href="./reviews_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Add this in the statistics row in dashboard.php -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?php echo $totalMessages; ?></h3>
                        <p>Contact Messages</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-email"></i>
                    </div>
                    <a href="./contact_messages.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $unreadMessages; ?></h3>
                        <p>Unread Messages</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-email-unread"></i>
                    </div>
                    <a href="./contact_messages.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
          </div>
          <!-- /.row -->

            <!-- ./col -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <?php include_once "includes/script.php" ?>
  <!-- ./ jQuery -->

  <?php

ob_flush();

?>