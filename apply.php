<?php
$conn = new mysqli("localhost", "root", "", "jobwave");

if (!isset($_GET['id'])) {
    echo "Job not found.";
    exit;
}

$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM jobs WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Job not found.";
    exit;
}

$job = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for <?= htmlspecialchars($job['title']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
        }
        .container {
            max-width: 700px;
            background: white;
            padding: 25px;
            border-radius: 14px;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 10px;
        }
        .details {
            font-size: 15px;
            color: #555;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 12px 0 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            padding: 10px 18px;
            background-color: #7a2ff2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        .job-summary {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Apply for: <?= htmlspecialchars($job['title']) ?></h2>
    <div class="job-summary">
        <p><strong>Company:</strong> <?= htmlspecialchars($job['company']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
        <p><strong>Experience:</strong> <?= htmlspecialchars($job['experience_range']) ?></p>
        <p><strong>Salary:</strong> <?= htmlspecialchars($job['salary_range']) ?></p>
    </div>

    <form action="submit_application.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="job_id" value="<?= $job['id'] ?>">

        <label>Your Name</label>
        <input type="text" name="name" required>

        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Resume (PDF only)</label>
        <input type="file" name="resume" accept=".pdf" required>

        <label>Why should we hire you?</label>
        <textarea name="message" rows="4" placeholder="Write a brief note..."></textarea>

        <button type="submit">Submit Application</button>
    </form>
</div>

</body>
</html>
