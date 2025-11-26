<?php

ob_start();

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TuxDB | Admin Login</title>

  <?php include_once "./includes/style.php"; ?>

  <style>
    .register-page {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .register-page::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
      background-size: 50px 50px;
      animation: float 20s infinite linear;
    }

    @keyframes float {
      0% { transform: translate(0, 0) rotate(0deg); }
      100% { transform: translate(-50px, -50px) rotate(360deg); }
    }

    .login-box {
      width: 400px;
      position: relative;
      z-index: 1;
    }

    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      backdrop-filter: blur(10px);
      background: rgba(255,255,255,0.95);
      overflow: hidden;
      transform: translateY(0);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .card-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-bottom: none;
      padding: 30px 20px;
      text-align: center;
    }

    .card-header .h1 {
      color: white;
      font-weight: 700;
      font-size: 2.5rem;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .card-body {
      padding: 30px;
    }

    .input-group {
      margin-bottom: 20px;
      position: relative;
    }

    .form-control {
      border: 2px solid #e9ecef;
      border-radius: 10px;
      padding: 15px 20px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: rgba(255,255,255,0.9);
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
      transform: translateY(-2px);
    }

    .input-group-text {
      background: transparent;
      border: 2px solid #e9ecef;
      border-right: none;
      border-radius: 10px 0 0 10px;
    }

    .form-control:focus + .input-group-append .input-group-text {
      border-color: #667eea;
    }

    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 10px;
      padding: 12px 40px;
      font-size: 16px;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .btn-primary:hover::before {
      left: 100%;
    }

    .pulse {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    .floating {
      animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }

    .alert-message {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
      animation: slideInRight 0.5s ease;
    }

    @keyframes slideInRight {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    .login-logo {
      font-size: 3rem;
      margin-bottom: 10px;
      display: block;
    }
  </style>
</head>

<body class="hold-transition register-page">
  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-message alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-ban"></i> Login Failed!</h5>
      <?php
      switch($_GET['error']) {
        case 1:
          echo "Invalid username or password.";
          break;
        case 2:
          echo "System error. Please try again later.";
          break;
        default:
          echo "An error occurred during login.";
      }
      ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['logout'])): ?>
    <div class="alert alert-success alert-message alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-check"></i> Success!</h5>
      You have been successfully logged out.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['session_expired'])): ?>
    <div class="alert alert-warning alert-message alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-exclamation-triangle"></i> Session Expired!</h5>
      Your session has expired. Please login again.
    </div>
  <?php endif; ?>

  <div class="login-box">
    <div class="card card-outline card-primary floating">
      <div class="card-header text-center">
        <span class="login-logo">🐧</span>
        <a href="./index.php" class="h1"><b>Tux</b>DB</a>
        <p class="mt-2 mb-0 text-light">Admin Portal</p>
      </div>
      <div class="card-body">
        <form action="login.php" method="post" id="loginForm">
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>

          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>

          <div class="row justify-content-center pb-3">
            <div class="col-auto">
              <button type="submit" class="btn btn-primary btn-block pulse">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
              </button>
            </div>
          </div>

          <div class="text-center">
            <small class="text-muted">
              <i class="fas fa-shield-alt mr-1"></i>Secure Admin Access
            </small>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include_once "./includes/script.php"; ?>

  <script>
    $(document).ready(function() {
      // Auto-hide alerts after 5 seconds
      setTimeout(function() {
        $('.alert-message').fadeOut('slow');
      }, 5000);

      // Form submission animation
      $('#loginForm').on('submit', function() {
        $('button[type="submit"]').html('<i class="fas fa-spinner fa-spin mr-2"></i>Logging in...').prop('disabled', true);
      });

      // Add focus effects
      $('.form-control').on('focus', function() {
        $(this).parent().addClass('focused');
      }).on('blur', function() {
        $(this).parent().removeClass('focused');
      });
    });
  </script>
</body>
</html>

<?php

ob_flush();

?>