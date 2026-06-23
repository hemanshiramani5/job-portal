<?php
session_start();
include '../login/db_con.php';

$id = $_GET['id'] ?? 0;
$result = $conn->query("SELECT * FROM mock_questions WHERE id=$id");
$question = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $q = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = $_POST['correct_option'];

    $stmt = $conn->prepare("UPDATE mock_questions SET category=?, question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=? WHERE id=?");
    $stmt->bind_param("sssssssi", $category, $q, $a, $b, $c, $d, $correct, $id);
    $stmt->execute();
    header("Location: admin_mock.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Mock Question</title>
  <style>
    body {font-family: Arial; background:#f4f5f7; padding:30px;}
    .form-box {background:#fff; padding:20px; border-radius:10px; max-width:600px; margin:auto;}
    input, textarea, select {width:100%; padding:8px; margin:8px 0; border:1px solid #ccc; border-radius:5px;}
    button {background:#2196F3; color:white; padding:10px 15px; border:none; border-radius:5px; cursor:pointer;}
  </style>
</head>
<body>
<div class="form-box">
  <h2>Edit Question</h2>
  <form method="post">
    <label>Category:</label>
    <input type="text" name="category" value="<?= htmlspecialchars($question['category']) ?>" required>

    <label>Question:</label>
    <textarea name="question" required><?= htmlspecialchars($question['question']) ?></textarea>

    <label>Option A:</label>
    <input type="text" name="option_a" value="<?= htmlspecialchars($question['option_a']) ?>" required>

    <label>Option B:</label>
    <input type="text" name="option_b" value="<?= htmlspecialchars($question['option_b']) ?>" required>

    <label>Option C:</label>
    <input type="text" name="option_c" value="<?= htmlspecialchars($question['option_c']) ?>" required>

    <label>Option D:</label>
    <input type="text" name="option_d" value="<?= htmlspecialchars($question['option_d']) ?>" required>

    <label>Correct Option:</label>
    <select name="correct_option" required>
      <option value="A" <?= $question['correct_option']=="A"?"selected":"" ?>>A</option>
      <option value="B" <?= $question['correct_option']=="B"?"selected":"" ?>>B</option>
      <option value="C" <?= $question['correct_option']=="C"?"selected":"" ?>>C</option>
      <option value="D" <?= $question['correct_option']=="D"?"selected":"" ?>>D</option>
    </select>

    <button type="submit">Update Question</button>
  </form>
</div>
</body>
</html>
