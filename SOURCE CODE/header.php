<?php



?>
<!DOCTYPE php>
<php lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <title>Tuxpub Library</title>

    <!-- Bootstrap core CSS -->


<link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">

    <!-- Additional CSS Files -->
    <?php

    include_once 'style.php';
    ?>




  </head>

  <body>
<style>
/* Hover animations for all items */
.item {
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.item:hover {
    transform: scale(1.05);
    z-index: 10;
}

/* Specific hover effects for different sections */

/* Trending Books & Top Books hover */
.trending .item:hover {
    transform: scale(1.08);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.trending .thumb {
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 8px;
}

.trending .thumb img {
    transition: all 0.3s ease;
}

.trending .item:hover .thumb img {
    transform: scale(1.1);
}

/* Publishers hover */
.features .item,
.section.trending .item {
    transition: all 0.3s ease;
    border-radius: 10px;
    padding: 15px;
}

.features .item:hover,
.section.trending .item:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Categories hover */
.categories .card {
    transition: all 0.4s ease;
    border: none !important;
}

.categories .card:hover {
    transform: scale(1.15);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    z-index: 100;
}

.categories .card img {
    transition: all 0.4s ease;
}

.categories .card:hover img {
    transform: scale(1.1);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

/* Features section hover */
.features .item {
    transition: all 0.3s ease;
    padding: 20px 15px;
    border-radius: 15px;
}

.features .item:hover {
    transform: translateY(-8px) scale(1.05);
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    box-shadow: 0 20px 35px rgba(0,0,0,0.1);
}

.features .item .image img {
    transition: all 0.3s ease;
}

.features .item:hover .image img {
    transform: scale(1.2);
}

/* Main banner book image hover */
.right-image img {
    transition: all 0.4s ease;
    border-radius: 15px !important;
}

.right-image:hover img {
    transform: scale(1.05);
    box-shadow: 0 25px 50px rgba(0,0,0,0.25);
}

/* Button hover effects */
.main-button a {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.main-button a:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.main-button a:active {
    transform: translateY(-1px);
}

/* Search input hover */
.search-input form {
    transition: all 0.3s ease;
}

.search-input:hover form {
    transform: scale(1.02);
}

/* Section heading hover */
.section-heading h2,
.section-heading h6 {
    transition: all 0.3s ease;
}

.section-heading:hover h2 {
    transform: translateX(10px);
    color: #007bff;
}

.section-heading:hover h6 {
    transform: translateX(5px);
}

/* Ensure smooth transitions for all interactive elements */
a, button, .btn, .form-control {
    transition: all 0.3s ease !important;
}

/* Prevent layout shift with proper spacing */
.trending .col-lg-3,
.categories .col-lg {
    margin-bottom: 30px;
}

/* Add some breathing room between items */
.row {
    margin: 0 -10px;
}

.row > [class*="col-"] {
    padding: 0 10px;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .item:hover {
        transform: scale(1.03);
    }
    
    .categories .card:hover {
        transform: scale(1.08);
    }
    
    .trending .item:hover {
        transform: scale(1.05);
    }
}

/* Add a subtle pulse animation on load */
@keyframes subtlePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

.item, .card {
    animation: subtlePulse 0.5s ease-in-out;
}

/* Stagger the animations for better visual effect */
.item:nth-child(1) { animation-delay: 0.1s; }
.item:nth-child(2) { animation-delay: 0.2s; }
.item:nth-child(3) { animation-delay: 0.3s; }
.item:nth-child(4) { animation-delay: 0.4s; }
.item:nth-child(5) { animation-delay: 0.5s; }




/* Features list hover animations */
.features-list {
    position: relative;
}

.feature-item {
    transition: all 0.3s ease;
    padding: 15px 20px;
    border-radius: 10px;
    border-left: 4px solid transparent;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    position: relative;
    overflow: hidden;
}

.feature-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s ease;
}

.feature-item:hover::before {
    left: 100%;
}

.feature-item:hover {
    transform: translateX(15px) scale(1.02);
    border-left: 4px solid #007bff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #ffffff 0%, #f1f8ff 100%);
}

.feature-item h5 {
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.feature-item:hover h5 {
    color: #007bff;
    transform: translateX(5px);
}

.feature-item:hover h5 i {
    transform: scale(1.2) rotate(5deg);
}

.feature-item i {
    transition: all 0.3s ease;
    font-size: 1.2em;
}

.feature-item p {
    transition: all 0.3s ease;
    margin-bottom: 0;
    padding-left: 30px;
}

.feature-item:hover p {
    transform: translateX(5px);
    color: #495057;
}

/* Specific color effects for each feature item */
.feature-item:nth-child(1):hover {
    border-left-color: #007bff;
    background: linear-gradient(135deg, #ffffff 0%, #e3f2fd 100%);
}

.feature-item:nth-child(1):hover h5 {
    color: #007bff;
}

.feature-item:nth-child(2):hover {
    border-left-color: #28a745;
    background: linear-gradient(135deg, #ffffff 0%, #e8f5e8 100%);
}

.feature-item:nth-child(2):hover h5 {
    color: #28a745;
}

.feature-item:nth-child(3):hover {
    border-left-color: #17a2b8;
    background: linear-gradient(135deg, #ffffff 0%, #e3f2f8 100%);
}

.feature-item:nth-child(3):hover h5 {
    color: #17a2b8;
}

.feature-item:nth-child(4):hover {
    border-left-color: #dc3545;
    background: linear-gradient(135deg, #ffffff 0%, #fde8e8 100%);
}

.feature-item:nth-child(4):hover h5 {
    color: #dc3545;
}

/* Icon specific animations */
.feature-item:nth-child(1):hover .fa-book {
    transform: scale(1.3) rotate(10deg);
    color: #007bff;
}

.feature-item:nth-child(2):hover .fa-download {
    transform: scale(1.3) translateY(-3px);
    color: #28a745;
}

.feature-item:nth-child(3):hover .fa-users {
    transform: scale(1.3);
    color: #17a2b8;
}

.feature-item:nth-child(4):hover .fa-heart {
    transform: scale(1.3) rotate(10deg);
    color: #dc3545;
    animation: heartbeat 1s ease-in-out;
}

@keyframes heartbeat {
    0% { transform: scale(1.3); }
    25% { transform: scale(1.5); }
    50% { transform: scale(1.3); }
    75% { transform: scale(1.5); }
    100% { transform: scale(1.3); }
}

/* Staggered animation delays for feature items */
.feature-item:nth-child(1) { transition-delay: 0.1s; }
.feature-item:nth-child(2) { transition-delay: 0.15s; }
.feature-item:nth-child(3) { transition-delay: 0.2s; }
.feature-item:nth-child(4) { transition-delay: 0.25s; }

.feature-item:hover:nth-child(1) { transition-delay: 0s; }
.feature-item:hover:nth-child(2) { transition-delay: 0.05s; }
.feature-item:hover:nth-child(3) { transition-delay: 0.1s; }
.feature-item:hover:nth-child(4) { transition-delay: 0.15s; }

/* Mobile responsiveness */
@media (max-width: 768px) {
    .feature-item:hover {
        transform: translateX(8px) scale(1.01);
    }
    
    .feature-item {
        padding: 12px 15px;
    }
}

/* Add a subtle background pattern on hover */
.feature-item {
    background-image: 
        radial-gradient(circle at 10% 20%, rgba(255,255,255,0.8) 0%, transparent 20%),
        radial-gradient(circle at 90% 80%, rgba(255,255,255,0.6) 0%, transparent 20%);
    background-size: 50px 50px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.feature-item:hover {
    background-image: 
        radial-gradient(circle at 10% 20%, rgba(255,255,255,0.9) 0%, transparent 20%),
        radial-gradient(circle at 90% 80%, rgba(255,255,255,0.7) 0%, transparent 20%);
}

/* Add a subtle border animation */
.feature-item {
    position: relative;
}

.feature-item::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 10px;
    padding: 2px;
    background: linear-gradient(135deg, #007bff, #28a745, #17a2b8, #dc3545);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask-composite: subtract;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.feature-item:hover::after {
    opacity: 1;
}
/* Navigation link hover magnification */
.nav li a {
  display: inline-block;
  transition: transform 0.25s ease, color 0.25s ease, text-shadow 0.25s ease;
}

.nav li a:hover {
  transform: scale(1.15);
  color: #00bcd4; /* cool cyan color */
  text-shadow: 0 0 8px rgba(0, 188, 212, 0.6);
}
.nav li a i,
.nav li a small {
  transition: transform 0.25s ease;
}

.nav li a:hover i,
.nav li a:hover small {
  transform: scale(1.2);
}
/* ===== Logo Hover Animation ===== */
.logo img {
  transition: transform 0.4s ease, filter 0.4s ease;
}

.logo:hover img {
  transform: scale(1.15);
  filter: drop-shadow(0 0 10px rgba(0, 188, 212, 0.6));
}


</style>


    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <nav class="main-nav">
              <!-- ***** Logo Start ***** -->
              <a href="index.php" class="logo">
                <img src="assets/images/logo.png" alt="" style="width: 158px;">
              </a>
              <!-- ***** Logo End ***** -->
              <!-- ***** Menu Start ***** -->
              <!-- In your header.php file, update the navigation menu -->
<ul class="nav">
    <li><a href="index.php" class="">Home</a></li>
    <li><a href="library.php">Library</a></li>
    <li><a href="category-list.php">Category</a></li>
    <li><a href="publisher-list.php">Publishers</a></li>
    <li><a href="about.php">About</a></li> <!-- Add this line -->
    <li><a href="feedback.php">Feedback</a></li> <!-- Add this line -->
    <li><a href="contact.php">Contact Us</a></li>
    <li><a href="donate-book.php"><i class="fa fa-cloud-upload"></i><small>Donate Books</small></a></li>
</ul>
              <a class='menu-trigger'>
                <span>Menu</span>
              </a>
              <!-- ***** Menu End ***** -->
            </nav>
          </div>
        </div>
      </div>
    </header>