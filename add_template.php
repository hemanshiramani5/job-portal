<?php
session_start();
include("../login/db_con.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $category = $_POST['category'];
  $color = $_POST['color'];
  $style = $_POST['style'];

  // Upload template file
  $templateFile = $_FILES['template_file']['name'];
  $templatePath = '../templates/' . basename($templateFile);
  move_uploaded_file($_FILES['template_file']['tmp_name'], $templatePath);

  // Upload preview image
  $previewImage = $_FILES['preview_image']['name'];
  $previewPath = '../template_previews/' . basename($previewImage);
  move_uploaded_file($_FILES['preview_image']['tmp_name'], $previewPath);

  // Save to DB
  $stmt = $conn->prepare("INSERT INTO resume_templates (name, category, color, style, template_file, preview_image) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssss", $name, $category, $color, $style, $templatePath, $previewPath);

  if ($stmt->execute()) {
    // Redirect to view_template.php after success
    header("Location: view_template.php");
    exit();
  } else {
    $error = "❌ Error adding template: " . $stmt->error;
  }
}

include("main_page.php");  // Include main page header
?>

<main class="content-area">
  <div class="template-container">
    <h2>Add Resume Template</h2>
    
    <?php if (!empty($error)): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <label>Template Name:</label>
      <input type="text" name="name" required>

      <label>Category:</label>
      <input type="text" name="category" required>

      <label>Color:</label>
      <input type="text" name="color" required>

      <label>Style:</label>
      <input type="text" name="style" required>

      <label>Upload HTML Template:</label>
      <input type="file" name="template_file" accept=".html" required>

      <label>Upload Preview Image:</label>
      <input type="file" name="preview_image" accept="image/*" required>

      <button type="submit">Add Template</button>
    </form>
  </div>
</main>

<style>
  .template-container {
    width: 40%;
    margin: 40px auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }

  .template-container h2 {
    text-align: center;
    color: #2d3436;
    margin-bottom: 25px;
  }

  .template-container label {
    font-weight: 600;
    display: block;
    margin-top: 15px;
    margin-bottom: 5px;
  }

  .template-container input[type="text"],
  .template-container input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #dcdde1;
    border-radius: 5px;
  }

  .template-container button {
    margin-top: 20px;
    padding: 12px 25px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    font-size: 15px;
    display: block;
    margin-left: auto;
    margin-right: auto;
  }

  .template-container button:hover {
    background-color: #2d7130ff;
  }

  .error-message {
    background-color: #fab1a0;
    border: 1px solid #d63031;
    padding: 15px;
    margin: 20px auto;
    border-radius: 6px;
    color: #d63031;
    text-align: center;
    font-weight: bold;
    width: 60%;
  }
</style>
