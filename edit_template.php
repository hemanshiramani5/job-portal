<?php
include("../login/db_con.php");

// Get template ID from query
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Template ID missing.");
}

$id = intval($_GET['id']);

// Fetch existing template details
$result = mysqli_query($conn, "SELECT * FROM resume_templates WHERE id = $id");
if (mysqli_num_rows($result) == 0) {
    die("❌ Template not found.");
}
$template = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $color = $_POST['color'];
    $style = $_POST['style'];

    $templatePath = $template['template_file'];
    $previewPath = $template['preview_image'];

    // If new template file uploaded
    if (!empty($_FILES['template_file']['name'])) {
        $templateFile = $_FILES['template_file']['name'];
        $templatePath = '../templates/' . basename($templateFile);
        move_uploaded_file($_FILES['template_file']['tmp_name'], $templatePath);
    }

    // If new preview image uploaded
    if (!empty($_FILES['preview_image']['name'])) {
        $previewImage = $_FILES['preview_image']['name'];
        $previewPath = '../template_previews/' . basename($previewImage);
        move_uploaded_file($_FILES['preview_image']['tmp_name'], $previewPath);
    }

    // Update in DB
    $stmt = $conn->prepare("UPDATE resume_templates SET name=?, category=?, color=?, style=?, template_file=?, preview_image=? WHERE id=?");
    $stmt->bind_param("ssssssi", $name, $category, $color, $style, $templatePath, $previewPath, $id);
    $stmt->execute();

    header("Location: view_template.php?success=Template updated successfully");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
        }
        .container {
            width: 50%;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        img {
            max-width: 150px;
            margin-top: 10px;
            display: block;
        }
        button {
            margin-top: 20px;
            padding: 12px;
            background: #00b894;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #55efc4;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Resume Template</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Template Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($template['name']) ?>" required>

        <label>Category:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($template['category']) ?>" required>

        <label>Color:</label>
        <input type="text" name="color" value="<?= htmlspecialchars($template['color']) ?>" required>

        <label>Style:</label>
        <input type="text" name="style" value="<?= htmlspecialchars($template['style']) ?>" required>

        <label>Current Template File:</label>
        <p><?= basename($template['template_file']) ?></p>
        <label>Upload New HTML Template (optional):</label>
        <input type="file" name="template_file" accept=".html">

        <label>Current Preview Image:</label>
        <img src="<?= htmlspecialchars($template['preview_image']) ?>" alt="Preview">
        <label>Upload New Preview Image (optional):</label>
        <input type="file" name="preview_image" accept="image/*">

        <button type="submit">Update Template</button>
    </form>
</div>
</body>
</html>
