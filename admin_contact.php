<?php include 'main_page.php'; ?> <!-- Only for session/auth or header -->
<?php
include __DIR__ . "/../login/db_con.php"; // Database connection

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin_contact.php?msg=deleted");
    exit;
}

// Fetch messages
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin - Contact Messages</title>
  <style>
    /* --- Contact Messages Page Scoped CSS --- */
    .contact-page {
      font-family: Arial, sans-serif;
      max-width: 1200px;
      margin: 50px auto;
      padding: 10px 20px;
      background: #f4f6f8;
      color: #333;
      border-radius: 10px;
    }

    .contact-page h1 {
      text-align: center;
      color: #2C3E50;
      margin-bottom: 30px;
    }

    .contact-page table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }

    .contact-page th, 
    .contact-page td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    .contact-page th {
      background-color: #2C3E50;
      color: white;
    }

    .contact-page tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .contact-page tr:hover {
      background-color: #f1f1f1;
    }

    .contact-page .delete-btn {
      background-color: #dc3545;
      color: white;
      padding: 6px 12px;
      border-radius: 5px;
      text-decoration: none;
      transition: background 0.3s;
    }

    .contact-page .delete-btn:hover {
      background-color: #a71d2a;
    }

    .contact-page .msg {
      text-align: center;
      color: green;
      font-weight: bold;
      margin-bottom: 15px;
    }

    .contact-page .no-messages {
      text-align: center;
      margin-top: 20px;
      font-style: italic;
      color: #777;
    }
  </style>
</head>
<body>
  <div class="contact-page">
    <h1>📬 Contact Messages (Admin Panel)</h1>

    <?php if(isset($_GET['msg']) && $_GET['msg']=="deleted"): ?>
      <p class="msg">✅ Message deleted successfully!</p>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
      <table aria-label="Contact messages table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['subject']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
              <td><?= $row['created_at'] ?></td>
              <td>
                <a class="delete-btn" href="admin_contact.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this message?');">
                  Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="no-messages">No messages found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
