<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Job Portal</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
      background: #f1f2f6;
      padding-top:0px;
    }

    * {
      box-sizing: inherit;
    }

    header {
      background: #2C3E50;
      padding: 20px 0;
      color: white;
      width: 100%;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      margin: 0;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 24px;
      align-items: center;
      margin: 0;
    }

    nav ul li a {
      text-decoration: none;
      color: white;
      font-weight: 600;
      padding: 8px 15px;
      font-size: 16px;
      border-radius: 6px;
      transition: background-color 0.3s;
    }

    nav ul li a:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    .dropdown {
      position: relative;
      cursor: pointer;
    }

    .dropdown-menu {
      position: absolute;
      top: 100%;
      left: 0;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      display: none;
      min-width: 200px;
      z-index: 999;
    }

    /* Show dropdown when hovering over dropdown parent */
    .dropdown:hover .dropdown-menu {
      display: block;
    }

    .dropdown-menu li a {
      color: #333;
      background: transparent;
      display: block;
      padding: 12px 20px;
      font-size: 15px;
      text-decoration: none;
    }

    .dropdown-menu li a:hover {
      background-color: #f7f7f7;
    }
  </style>
</head>
<body>

<header>
  <div class="container">
    <h1 class="logo">JobWave</h1>
    <nav>
      <ul>
        <li><a href="/jobwave/main_page.php">Home</a></li>
        <li><a href="/jobwave/jobs/job_list.php">Jobs</a></li>
        <li class="dropdown" id="resumeDropdown">
          <a href="#" id="resumeToggle">Resume Makers</a>
          <ul class="dropdown-menu">
            <li><a href="/jobwave/login/login.php">Resume</a></li>
            <li><a href="/jobwave/resume/resume_builder.php">Live Resume</a></li>
          </ul>
        </li>
        <li><a href="/jobwave/login/login.php">Login</a></li>
        <li><a href="/jobwave/login/register.php">Register</a></li>
      </ul>
    </nav>
  </div>
</header>

</body>
</html>
