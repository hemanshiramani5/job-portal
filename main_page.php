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
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      color: #333;
      background: #f1f2f6;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: auto;
    }

    header {
      background:  #2C3E50;
      padding: 20px 0;
      color: white;
      position: sticky;
      top: 0;
      z-index: 1000;
      width: 100%;
    }

    .nav-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 24px;
      align-items: center;
    }

    nav ul li {
      position: relative;
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

    /* Dropdown styles */
    .dropdown:hover .dropdown-menu {
      display: block;
    }

    .dropdown-menu {
      position: absolute;
      top: 110%;
      left: 0;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      padding: 10px 0;
      display: none;
      width: 220px;
      z-index: 999;
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

    /* Main content area below header */
    main.content-area {
      padding: 40px 0;
    }
  </style>
</head>
<body>

  <!-- Navigation -->
  <header>
    <div class="container nav-container">
      <h1 class="logo">JobWave</h1>
      <nav>
        <ul>
          <li class="dropdown">
          <a href="mainpage1.php">Home</a>
          
          
  </li>

          <li class="dropdown">
            <a href="#">Jobs</a>
            <ul class="dropdown-menu">
              <li><a href="admin_add_job.php">Insert Jobs</a></li>
              <li><a href="update_job.php">Update jobs</a></li>
              <li><a href="delete_job.php">Delete jobs</a></li>
              <li><a href="job_list.php">View Jobs</a></li>

            </ul>
          </li>
          <li class="dropdown">
          <a href="#">Notifications</a>
          <ul  class="dropdown-menu">
          <li> <a href="admin_send_notification.php">📢 Send Notification</a></li>
           <li><a href="admin_notification.php">📬 All User Notifications</a></li>
  </ul>
  </li>

          <li class="dropdown">
            <a href="#">Resume Makers</a>
            <ul class="dropdown-menu">
              <li><a href="add_template.php">add templates</a></li>
              <li><a href="view_template.php">View templates</a></li>
            </ul>
            
          </li> <li class="dropdown">
            <a href="#">preparation</a>
            <ul class="dropdown-menu">
              <li><a href="add_mock_question.php">add  question</a></li>
              <li><a href="admin_mock.php">view  question</a></li>
  </ul>
  </li>

          <li class="dropdown">
            <a href="#"> View Users</a>
            <ul class="dropdown-menu">
              <li><a href="user_submit_application.php">Apply Jobs</a></li>
              <li><a href="review_manage.php">Review Management</a></li>
            </ul>
          </li>

          <li class="dropdown">
            <a href="#">Login</a>
            <ul class="dropdown-menu">
              <li><a href="admin.php">View Users</a></li>
            </ul>
          </li>
          <li>
            <a href="../login/logout.php">Logout</a>
          </li>
           <li>
          <a href="admin_contact.php">&#128172;</a></li>

        </ul>
      </nav>
    </div>
  </header>
