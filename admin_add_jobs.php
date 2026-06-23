<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Add or Delete Jobs</title>
    <style>
        body { font-family: Arial; margin: 20px; background-color: #f8f8f8; }
        h2 { color: #333; }
        form { background: #fff; padding: 20px; border: 1px solid #ccc; max-width: 400px; margin-bottom: 30px; }
        input[type=text], textarea { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 15px; background-color: #007BFF; color: #fff; border: none; cursor: pointer; }
        .job-box { background: #fff; padding: 15px; margin-bottom: 10px; border: 1px solid #ccc; }
        .delete-btn { color: red; text-decoration: none; margin-top: 5px; display: inline-block; }
        img { max-width: 100%; height: auto; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Add Job</h2>
<form method="POST" action="admin_add_jobs.php" enctype="multipart/form-data">

    <label>ID:</label>
    <input type="text" name="id" required>

    <label>Job Title:</label>
    <input type="text" name="title" required>

    <label>Description:</label>
    <textarea name="description" required></textarea>

    <label>Location:</label>
    <input type="text" name="location">

    <label>Upload Image:</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit" name="submit">Add Job</button>
</form>

<hr>

<h2>Existing Jobs</h2>

<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "jobwave");

// ✅ DELETE job if ID is passed in URL
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $conn->query("DELETE FROM jobs WHERE id = $deleteId");
    echo "<p style='color:green;'>Job deleted successfully.</p>";
}

// ✅ INSERT job
if (isset($_POST['submit'])) {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];

    // Handle image upload
    $imageName = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $uploadDir = "uploads/";
    $imagePath = $uploadDir . basename($imageName);

    // Create uploads folder if not exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move uploaded file
    if (move_uploaded_file($imageTmp, $imagePath)) {
        // Insert into DB
        $sql = "INSERT INTO jobs (id, title, description, location, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $id, $title, $description, $location, $imageName);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Job added successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Failed to upload image.</p>";
    }
}

// ✅ Display all jobs
$result = $conn->query("SELECT * FROM jobs ORDER BY posted_on DESC");

if ($result->num_rows > 0) {
    while ($job = $result->fetch_assoc()) {
        echo "<div class='job-box'>";
        echo "<h3>" . htmlspecialchars($job['id']) . "</h3>";
        echo "<h3>" . htmlspecialchars($job['title']) . "</h3>";
        echo "<p><strong>Location:</strong> " . htmlspecialchars($job['location']) . "</p>";
        echo "<p>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
        echo "<small>Posted on: " . $job['posted_on'] . "</small><br>";

        // Show image
        if (!empty($job['image']) && file_exists("uploads/" . $job['image'])) {
            echo "<img src='uploads/" . htmlspecialchars($job['image']) . "' alt='Job Image'>";
        }

        echo "<br><a class='delete-btn' href='admin_add_job.php?delete=" . $job['id'] . "' onclick='return confirm(\"Are you sure to delete this job?\");'>Delete</a>";
        echo "</div>";
    }
} else {
    echo "<p>No jobs available.</p>";
}
?>

</body>
</html>