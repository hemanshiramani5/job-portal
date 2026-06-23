<?php
// Start output buffering to avoid header issues
ob_start();
session_start();
include "../login/db_con.php"; // Database connection
$message = "";

// Fetch all usernames from users table
$user_query = "SELECT username FROM users";
$user_result = $conn->query($user_query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $notif_msg = trim($_POST["message"]);

    if (!empty($username) && !empty($notif_msg)) {
        $stmt = $conn->prepare("INSERT INTO notifications (username, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $notif_msg);

        if ($stmt->execute()) {
            // Redirect after successful insertion
            header("Location: admin_notification.php");
            exit();
        } else {
            $message = "❌ Error sending notification: " . $conn->error;
        }
    } else {
        $message = "⚠️ Please fill in all fields.";
    }
}

include 'main_page.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Send Notification - Admin</title>
<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f1f2f6;
  }

  .notification-container {
    max-width: 500px;
    background: #fff;
    margin: 60px auto;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  }

  .notification-container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #2f3542;
  }

  .notification-container label {
    font-weight: 600;
    display: block;
    margin: 10px 0 5px;
  }

  .notification-container input,
  .notification-container textarea,
  .notification-container select {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 15px;
  }

  .notification-container button {
    margin-top: 20px;
    width: 100%;
    padding: 12px;
    border: none;
    background: #4CAF50;
    color: white;
    font-weight: bold;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
  }

  .notification-container button:hover {
    background: #207623ff;
  }

  .message {
    text-align: center;
    margin-top: 15px;
    font-weight: bold;
  }

  .message.success {
    color: green;
  }

  .message.error {
    color: red;
  }

  .message.warning {
    color: orange;
  }
</style>
</head>
<body>
<div class="notification-container">
  <h2>📢 Send Notification</h2>
  <form method="post" action="">
    <label for="username">Select Username:</label>
    <select id="username" name="username" required>
      <option value="">-- Select a user --</option>
      <?php
      if ($user_result && $user_result->num_rows > 0) {
          while ($row = $user_result->fetch_assoc()) {
              echo "<option value='" . htmlspecialchars($row['username']) . "'>" . htmlspecialchars($row['username']) . "</option>";
          }
      } else {
          echo "<option value=''>No users found</option>";
      }
      ?>
    </select>

    <label for="message">Message:</label>
    <textarea id="message" name="message" placeholder="Enter Notification Message" rows="4" required></textarea>

    <button type="submit">Send Notification</button>
  </form>

  <?php if ($message): ?>
  <p class="message
  <?= strpos($message, '✅') !== false ? 'success' :
     (strpos($message, '⚠️') !== false ? 'warning' : 'error') ?>">
    <?= htmlspecialchars($message) ?>
  </p>
  <?php endif; ?>
</div>
</body>
</html>
<?php
// Flush the output buffer
ob_end_flush();
?>
