<?php

// Check if accessing directly as admin/login.php
if(strpos($_SERVER['REQUEST_URI'], '/admin/login.php') !== false) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

// Check if user is already logged in
if(isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" crossorigin="anonymous">
    <title>Login</title>
</head>
<body>
<div class="wrapper">
  <form class="login" action="/admin/model/login.php" method="POST">
    <p class="title">Log in</p>
    <input type="text" name="username" placeholder="Username" autofocus required/>
    <input type="password" name="password" placeholder="Password" required/>
    <?php if(isset($_GET['error'])): ?>
      <div class="error">
        <?php 
          switch($_GET['error']) {
            case 'empty':
              echo "Please fill in all fields";
              break;
            case 'invalid_user':
              echo "User not found";
              break;
            case 'invalid_pass':
              echo "Invalid password";
              break;
          }
        ?>
      </div>
    <?php endif; ?>
    <button type="submit">
      <i class="spinner"></i>
      <span class="state">Log in</span>
    </button>
  </form>
  <footer><a target="blank" href="ghost-ph.cloud">ghost-ph.cloud</a></footer>
  </p>
</div>
<script src="assets/login.js"></script>
</body>
</html>