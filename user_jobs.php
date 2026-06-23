<!DOCTYPE html>
<html>
<head>
    <title>Available Jobs</title>
</head>
<body>
    <h2>Available Jobs</h2>
    <?php
    // DB connection
    $conn = new mysqli("localhost", "root", "", "jobwave");

    $sql = "SELECT * FROM jobs ORDER BY posted_on DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
       while ($job = $result->fetch_assoc()) {
    echo "<div style='border:1px solid #ccc; margin:10px; padding:10px; display:flex; align-items:flex-start; gap:15px;'>";

    // ✅ Left: Image (if available)
    if (!empty($job['image']) && file_exists("uploads/" . $job['image'])) {
        echo "<div style='flex-shrink:0;'>";
        echo "<img src='uploads/" . htmlspecialchars($job['image']) . "' alt='Job Image' style='width:150px; height:auto;'>";
        echo "</div>";
    }

    // ✅ Right: Job details
    echo "<div>";
    echo "<h3>" . htmlspecialchars($job['title']) . " (ID: " . htmlspecialchars($job['id']) . ")</h3>";
    echo "<p><strong>Location:</strong> " . htmlspecialchars($job['location']) . "</p>";
    echo "<p>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
    echo "<small>Posted on: " . $job['posted_on'] . "</small>";
    echo "</div>";

    echo "</div>";

        }
    } else {
        echo "<p>No jobs available.</p>";
    }
    ?>
</body>
</html>