<?php
session_start();
include '../login/db_con.php'; // adjust path
   include 'main_page.php'; 

// Fetch all questions
$result = $conn->query("SELECT * FROM mock_questions ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Manage Mock Questions</title>
  <style>
    body {font-family: Arial, sans-serif;}
    .container { padding:5px; border-radius:10px;}
    h2 {text-align:center;}
    table {width:100%; border-collapse:collapse; margin-top:20px;}
    th, td {border:1px solid #ddd; padding:10px; text-align:left;}
    th {background:#2C3E50; color:white;}
    a.btn {padding:6px 12px; border-radius:5px; text-decoration:none; font-size:14px;}
    .add-btn {background:#4CAF50; color:white;}
    .edit-btn {background:#2196F3; color:white;}
    .delete-btn {background:#f44336; color:white;}
  </style>
</head>
<body>
<div class="container">
  <h2>Admin - Manage Mock Questions</h2>
  <p><a href="add_mock_question.php" class="btn add-btn">+ Add New Question</a></p>
  <table>
    <tr>
      <th>ID</th>
      <th>Category</th>
      <th>Question</th>
      <th>Correct Answer</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td><?= htmlspecialchars($row['question']) ?></td>
        <td><?= $row['correct_option'] ?></td>
        <td>
          <a href="edit_mock_question.php?id=<?= $row['id'] ?>" class="btn edit-btn">Edit</a>
          <a href="delete_mock_question.php?id=<?= $row['id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
</body>
</html>
