<?php
session_start();
include '../login/db_con.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $question = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = $_POST['correct_option'];

    $stmt = $conn->prepare("INSERT INTO mock_questions (category, question, option_a, option_b, option_c, option_d, correct_option) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss", $category, $question, $a, $b, $c, $d, $correct);
    $stmt->execute();
    header("Location: admin_mock.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Mock Question</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f5f7;
      margin: 0;
      padding: 0;
    }
    /* Space below header and around form */
    .content-wrapper {
      max-width: 700px;
      margin: 40px auto 60px auto;
      background: #fff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    input, textarea, select {
      width: 100%;
      padding: 10px;
      margin: 12px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 15px;
      resize: vertical;
    }
    button {
      background: #4CAF50;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      margin-top: 15px;
      width: 100%;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background: #45a049;
    }
    h2 {
      margin-top: 0;
      color: #333;
      text-align: center;
    }
  </style>
</head>
<body>

<?php include 'main_page.php'; ?>  <!-- Header included here -->

<div class="content-wrapper">
  <h2>Add New Mock Question</h2>
  <form method="post">
    <label for="category">Category:</label>
    <input type="text" id="category" name="category" required>

    <label for="question">Question:</label>
    <textarea id="question" name="question" rows="4" required></textarea>

    <label for="option_a">Option A:</label>
    <input type="text" id="option_a" name="option_a" required>

    <label for="option_b">Option B:</label>
    <input type="text" id="option_b" name="option_b" required>

    <label for="option_c">Option C:</label>
    <input type="text" id="option_c" name="option_c" required>

    <label for="option_d">Option D:</label>
    <input type="text" id="option_d" name="option_d" required>

    <label for="correct_option">Correct Option (A/B/C/D):</label>
    <select id="correct_option" name="correct_option" required>
      <option value="" disabled selected>Select correct option</option>
      <option value="A">A</option>
      <option value="B">B</option>
      <option value="C">C</option>
      <option value="D">D</option>
    </select>

    <button type="submit">Save Question</button>
  </form>
  </div>
</body>
</html>
