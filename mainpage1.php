<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "jobwave");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get counts
$total_jobs = $conn->query("SELECT COUNT(*) AS count FROM jobs")->fetch_assoc()['count'];
$total_templates = $conn->query("SELECT COUNT(*) AS count FROM resume_templates")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];

// Get latest 5 job posts (⚠️ use posted_on instead of created_at if your table has it)
$latest_jobs = $conn->query("SELECT id, title, company, posted_on FROM jobs ORDER BY posted_on DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>JobWave Admin Dashboard</title>

  <!-- Google Fonts & Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

    /* ---------- Header / Navigation ---------- */
    header {
      background: #2C3E50;
      padding: 20px 0;
      color: white;
      position: sticky;
      top: 0;
      z-index: 1000;
      width: 100%;
    }

    .nav-container {
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

    /* Dropdown */
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

    /* ---------- Dashboard Content ---------- */
    main.container {
      width: 90%;
      max-width: 1200px;
      margin: auto;
      padding: 40px 0;
    }

    .dashboard-title {
      font-size: 28px;
      margin-bottom: 30px;
      text-align: center;
    }

    .dashboard-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .stat-card {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.06);
      display: flex;
      align-items: center;
      gap: 20px;
      transition: transform 0.3s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-icon {
      font-size: 40px;
      padding: 15px;
      background: #e1f0ff;
      color: #1e90ff;
      border-radius: 50%;
    }

    .stat-info h3 {
      font-size: 24px;
    }

    .stat-info p {
      font-size: 14px;
      color: #555;
    }

    /* Chart Box */
    canvas {
      margin-top: 40px;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    /* Jobs Table */
    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    table th, table td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    table th {
      background: #2f3542;
      color: white;
    }

    table tr:hover td {
      background: #f8f9fa;
    }
  </style>
</head>
<body>
  <?php include 'main_page.php';?>
<main class="container">
    <h2 class="dashboard-title">Dashboard Overview</h2>

    <div class="dashboard-stats">
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
        <div class="stat-info">
          <h3><?= $total_jobs ?></h3>
          <p>Total Jobs Posted</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
        <div class="stat-info">
          <h3><?= $total_templates ?></h3>
          <p>Resume Templates</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info">
          <h3><?= $total_users ?></h3>
          <p>Registered Users</p>
        </div>
      </div>
    </div>

    <!-- Chart -->
    <canvas id="dashboardChart" height="100"></canvas>
    <script>
      const ctx = document.getElementById('dashboardChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Jobs', 'Templates', 'Users'],
          datasets: [{
            label: 'Count',
            data: [<?= $total_jobs ?>, <?= $total_templates ?>, <?= $total_users ?>],
            backgroundColor: ['#1e90ff', '#ffa502', '#2ed573']
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    </script>

    <!-- Latest Jobs Table -->
    <h3 style="margin-top:40px;">Latest Job Posts</h3>
    <table>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Company</th>
        <th>Posted On</th>
      </tr>
      <?php while($row = $latest_jobs->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['company']) ?></td>
        <td><?= $row['posted_on'] ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
</main>
</body>
</html>
