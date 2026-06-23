<?php include 'main_page.php'; ?> <!-- Only for session/auth or header -->
<?php
include("../login/db_con.php");

// ✅ Updated query for the 'applications' table
$sql = "SELECT a.id AS application_id, a.name, a.email, a.message, a.resume_path, a.applied_on,
               j.title AS job_title, j.company AS company_name
        FROM applications a
        JOIN jobs j ON a.job_id = j.id
        ORDER BY a.applied_on DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin - Manage Job Applications</title>
  <style>
    .applications-page {
      font-family: Arial, sans-serif;
      max-width: 1200px;
      margin: 50px auto;
      padding: 10px;
      background: #f4f6f8;
      color: #333;
      border-radius: 10px;
    }

    .applications-page h1 {
      text-align: center;
      color: #2C3E50;
      margin-bottom: 30px;
    }

    .applications-page table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }

    .applications-page th, 
    .applications-page td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    .applications-page th {
      background-color: #2C3E50;
      color: white;
    }

    .applications-page tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .applications-page tr:hover {
      background-color: #f1f1f1;
    }

    .applications-page a {
      color: #007bff;
      text-decoration: none;
    }

    .applications-page a:hover {
      text-decoration: underline;
    }

    .no-applications {
      text-align: center;
      margin-top: 40px;
      color: #777;
      font-style: italic;
    }
  </style>
</head>
<body>
  <div class="applications-page">
    <h1>Admin - Manage Job Applications</h1>

    <?php if($result && $result->num_rows > 0): ?>
      <table aria-label="Job applications table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Applicant Name</th>
            <th>Email</th>
            <th>Job Title</th>
            <th>Company</th>
            <th>Message</th>
            <th>Resume</th>
            <th>Applied On</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['application_id']) ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['job_title']) ?></td>
              <td><?= htmlspecialchars($row['company_name']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
              <td>
                <?php if(!empty($row['resume_path'])): ?>
                  <a href="../admin/uploads/<?= htmlspecialchars($row['resume_path']) ?>" target="_blank">View Resume</a>
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </td>
              <td><?= date("d M Y, h:i A", strtotime($row['applied_on'])) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="no-applications">No job applications found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
