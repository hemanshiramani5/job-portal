<?php
session_start();
include("../login/db_con.php"); // Database connection

// Get job ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("Invalid job ID.");
}

// Fetch job details
$jobQuery = $conn->prepare("SELECT title, company FROM jobs WHERE id = ? LIMIT 1");
$jobQuery->bind_param("i", $id);
$jobQuery->execute();
$jobResult = $jobQuery->get_result();

if ($jobResult->num_rows === 0) {
    die("Job not found.");
}
$job = $jobResult->fetch_assoc();

// Fetch all applicants for this job (directly from applications)
$sql = "
    SELECT id, name, email, message, resume_path, applied_on
    FROM applications
    WHERE job_id = ?
    ORDER BY applied_on DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applicants for <?= htmlspecialchars($job['title']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        h2 {
            margin-bottom: 20px;
            color: #2C3E50;
        }
        .applicant-card {
            background: #fff;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        .applicant-card p {
            margin: 5px 0;
            font-size: 15px;
            color: #333;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #0073e6;
            font-weight: bold;
        }
        .resume-link {
            color: #0073e6;
            text-decoration: underline;
        }
        .no-applicants {
            font-style: italic;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include 'main_page.php'; ?>
<a href="javascript:history.back()" class="back-link">← Back to Job List</a>

<h2>Applicants for: <?= htmlspecialchars($job['title']) ?> (<?= htmlspecialchars($job['company']) ?>)</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="applicant-card">
            <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
            <?php if (!empty($row['message'])): ?>
                <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($row['message'])) ?></p>
            <?php endif; ?>
            <p><strong>Resume:</strong> 
                <?php if (!empty($row['resume_path'])): ?>
                    <a href="uploads/<?= htmlspecialchars($row['resume_path']) ?>" target="_blank" class="resume-link">View/Download</a>
                <?php else: ?>
                    Not Uploaded
                <?php endif; ?>
            </p>
            <p><strong>Applied On:</strong> <?= date('F j, Y, g:i a', strtotime($row['applied_on'])) ?></p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p class="no-applicants">No applicants have applied for this job yet.</p>
<?php endif; ?>

</body>
</html>
