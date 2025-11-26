




<nav class="main-header navbar navbar-expand">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="dashboard.php" class="nav-link">Home</a>
      </li>
       <li class="nav-item d-none d-sm-inline-block">
        <a href="../index.php" class="nav-link">View Website</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      </li>
    </ul>
    <?php
include 'session_check.php'; // Protects the page
?>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
 
<li class="nav-item d-none d-sm-inline-block">  
       <a href="#"  class="nav-link"><b>User : </b><?php echo $_SESSION['username'];?></a>
        
      
</li>

<li class="nav-item d-none d-sm-inline-block">  

        <a href="logout.php"  class="nav-link">Logout</a>
      
</li>


  <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
<li class="nav-item">
  <a id="modeToggle" class="nav-link" href="#" title="Toggle Dark/Light Mode">
    <i id="modeIcon" class="fas fa-moon"></i>
  </a>
</li>
    
    
 
    

    </ul>
  </nav>
<script>
// Grab elements
const toggle = document.getElementById('modeToggle');
const icon = document.getElementById('modeIcon');

// Load saved mode
if(localStorage.getItem('mode') === 'dark'){
    document.body.classList.add('dark-mode');
    icon.classList.remove('fa-moon');
    icon.classList.add('fa-sun');
}

// Toggle on click
toggle.addEventListener('click', function(e){
    e.preventDefault();
    document.body.classList.toggle('dark-mode');

    // Swap icon
    if(document.body.classList.contains('dark-mode')){
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
        localStorage.setItem('mode', 'dark');
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
        localStorage.setItem('mode', 'light');
    }
});
</script>
