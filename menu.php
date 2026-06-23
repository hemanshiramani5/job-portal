<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Job Portal</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: #f1f2f6;
      color: #333;
      padding-top: 100px; /* enough space for fixed navbar */
    }

    header {
      align-items:center;
      background: #a4b0be;
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      padding: 0px 0;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 90%;
      max-width: 1200px;
      margin: auto;
    }

    .logo {
      font-size: 32px;
      font-weight: bold;
      color:white;
      display:flex;
      align-items:center;
      
    }

    .nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
      margin-right: 8px;
    }

    .nav a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      padding: 10px 15px;
      border-radius: 8px;
      transition: background-color 0.3s;
    }

    .nav a:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .nav {
        flex-direction: column;
        align-items: flex-start;
      }

      .nav ul {
        flex-direction: column;
        width: 100%;
        padding: 10px 0;
      }

      .nav a {
        width: 100%;
        text-align: left;
        background: rgba(255,255,255,0.1);
      }
    }
  </style>
</head>
<body>

<?php
echo '
<header>
  <div class="nav">
    <h1 class="logo">JobWave</h1>
    <nav>
      <ul>
        <li><a href="main_page.php">Home</a></li>
        <li><a href="jobs.php">Jobs</a></li>
        <li><a href="#">Resume Makers</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
      </ul>
    </nav>
  </div>
</header>';
?>

</body>
</html>
