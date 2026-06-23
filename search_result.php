<?php
include("login/db_con.php");

// Get search filters
$title = $_GET['title'] ?? '';
$location = $_GET['location'] ?? '';
$experience = $_GET['experience'] ?? '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Base query
$sql = "FROM jobs WHERE 1=1";

if (!empty($title)) {
    $safe_title = $conn->real_escape_string($title);
    $sql .= " AND title LIKE '%$safe_title%'";
}
if (!empty($location)) {
    $safe_location = $conn->real_escape_string($location);
    $sql .= " AND location LIKE '%$safe_location%'";
}
if (!empty($experience)) {
    $safe_exp = (int)$experience;
    $sql .= " AND experience_range <= $safe_exp";
}

// Count total jobs
$total_jobs = $conn->query("SELECT COUNT(*) AS cnt $sql")->fetch_assoc()['cnt'];

// Fetch jobs
$jobs = $conn->query("SELECT * $sql ORDER BY posted_on DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Job Search Results</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f7fa;
      margin: 0;
      padding: 20px;
    }
    .job-card {
      display: flex;
      align-items: center;
      background: #fff;
      border-radius: 16px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      justify-content: space-between;
    }
    .job-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .company-logo {
      width: 60px;
      height: 60px;
      border-radius: 8px;
      background: #eee;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      color: #777;
    }
    .job-info h3 {
      margin: 0;
      text-transform: capitalize;
    }
    .job-info p {
      margin: 4px 0;
      color: #555;
    }
    .tags {
      display: flex;
      gap: 12px;
      font-size: 14px;
      color: #333;
    }
    .badge {
      display: inline-block;
      background: #e7f1ff;
      color: #0073ff;
      font-size: 13px;
      padding: 4px 10px;
      border-radius: 6px;
      margin-top: 6px;
    }
    .apply-btn {
      background: #f1e5ff;
      color: #7d3cff;
      border: none;
      padding: 10px 16px;
      border-radius: 10px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
    }
    .apply-btn:hover {
      background: #e4d2ff;
    }
    .pagination {
      margin-top: 20px;
      text-align: center;
    }
    .pagination a {
      display: inline-block;
      padding: 8px 12px;
      margin: 0 5px;
      border-radius: 8px;
      background: #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      color: #0073ff;
      text-decoration: none;
    }
    .pagination a.active {
      background: #0073ff;
      color: #fff;
    }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
<h2>Search Results</h2>

<?php if ($jobs->num_rows > 0): ?>
  <?php while($job = $jobs->fetch_assoc()): ?>
    <div class="job-card">
      <div class="job-left">
        <div class="company-logo">
          <?php if(!empty($job['logo'])): ?>
            <img src="admin/uploads/<?= htmlspecialchars($job['logo']) ?>" alt="Logo" style="max-width:100%; max-height:100%; border-radius:8px;">
          <?php else: ?>
            Company Logo
          <?php endif; ?>
        </div>
        <div class="job-info">
          <h3><?= htmlspecialchars($job['title']) ?></h3>
          <p><?= htmlspecialchars($job['company']) ?></p>
          <div class="tags">
            <span>🧳 <?= htmlspecialchars($job['experience_range']) ?> </span>
            <span>💰 <?= htmlspecialchars($job['salary_range']) ?></span>
            <span>📍 <?= htmlspecialchars($job['location']) ?></span>
          </div>
          <?php if($job['is_early_applicant']): ?>
            <div class="badge">Early Applicant</div>
          <?php endif; ?>
          <p style="font-size:13px; color:#777;">Posted <?= round((time() - strtotime($job['posted_on'])) / 86400) ?> days ago</p>
        </div>
      </div>
      <div class="job-right">
        <a href="login/login.php?">
          <button class="apply-btn">🚀 Apply Now</button>
        </a>
      </div>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p>No jobs found matching your criteria.</p>
<?php endif; ?>

<!-- Pagination -->
<div class="pagination">
  <?php
  $total_pages = ceil($total_jobs / $limit);
  for ($i = 1; $i <= $total_pages; $i++):
  ?>
    <a href="?title=<?= urlencode($title) ?>&location=<?= urlencode($location) ?>&experience=<?= urlencode($experience) ?>&page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
  <?php endfor; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
