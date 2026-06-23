<?php
session_start();
include "../login/db_con.php";


// Delete notification if requested
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM notifications WHERE id = $delete_id");
    header("Location: admin_notification.php");
    exit();
}
include 'main_page.php';
// Fetch all notifications
$result = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All User Notifications</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .notif-container {
      padding: 40px;
      max-width: 1000px;
      margin: 30px auto;
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.08);
    }

    .notif-container h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }

    .notif-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }

    .notif-table th, .notif-table td {
      padding: 14px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    .notif-table th {
      background-color: #2c3e50;
      color: white;
    }

    .notif-table tr:hover {
      background-color: #f1f1f1;
    }

    .delete-btn {
      color: #e74c3c;
      text-decoration: none;
      font-weight: bold;
    }

    .delete-btn:hover {
      text-decoration: underline;
    }

    @media screen and (max-width: 768px) {
      .notif-table th, .notif-table td {
        padding: 10px;
      }
    }
  </style>
</head>
<body>

<div class="notif-container">
  <h2>🔔 All User Notifications</h2>
  <table class="notif-table">
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Message</th>
      <th>Date</th>
      <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['message']) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
          <a class="delete-btn" href="?delete_id=<?= $row['id'] ?>"
             onclick="return confirm('Are you sure you want to delete this notification?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

</body>
</html>
