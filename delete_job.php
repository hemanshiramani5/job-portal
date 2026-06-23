<?php
$conn = new mysqli("localhost", "root", "", "jobwave");

$sql = "SELECT * FROM jobs ORDER BY posted_on DESC";
$result = $conn->query($sql);

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return $diff . " seconds";
    elseif ($diff < 3600) return floor($diff / 60) . " minutes";
    elseif ($diff < 86400) return floor($diff / 3600) . " hours";
    else return floor($diff / 86400) . " days";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Listings</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            padding: 0;
            margin: 0;
        }
        .content {
            padding: 20px;
            max-width: 900px;
            margin: auto;
        }
        h2 {
            margin-bottom: 30px;
        }
        .job-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .job-info {
            flex-grow: 1;
        }
        .job-title {
            font-weight: bold;
            font-size: 18px;
        }
        .company {
            color: #666;
            font-size: 15px;
            margin-top: 4px;
        }
        .details {
            margin-top: 12px;
            display: flex;
            gap: 20px;
            color: #444;
            font-size: 14px;
        }
        .badge {
            background-color: #e6f4ff;
            color: #0073e6;
            display: inline-block;
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .posted {
            color: #999;
            font-size: 13px;
            margin-top: 12px;
        }
        .actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }
        .apply-btn {
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            background-color: #f0e9ff;
            color: #7a2ff2;
            text-decoration: none;
        }
        .job-logo img {
            max-width: 80px;
            max-height: 80px;
            object-fit: contain;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include 'main_page.php'; ?>

<div class="content">
    <h2>Available Jobs</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="job-card">
                <!-- Company Logo -->
                <?php if (!empty($row['logo'])): ?>
                    <div class="job-logo">
                        <img src="uploads/<?= htmlspecialchars($row['logo']) ?>" alt="Company Logo">
                    </div>
                <?php endif; ?>

                <div class="job-info">
                    <div class="job-title"><?= htmlspecialchars($row['title']) ?></div>
                    <div class="company"><?= htmlspecialchars($row['company']) ?></div>

                    <div class="details">
                        <span>🧳 <?= htmlspecialchars($row['experience_range']) ?></span>
                        <span>💰 <?= htmlspecialchars($row['salary_range']) ?></span>
                        <span>📍 <?= htmlspecialchars($row['location']) ?></span>
                    </div>

                    <?php if (!empty($row['is_early_applicant'])) { ?>
                        <div class="badge">Early Applicant</div>
                    <?php } ?>

                    <div class="posted">
                        Posted <?= timeAgo($row['posted_on']) ?> ago
                    </div>
                </div>

                <div class="actions">
                    <a href='delete_job1.php?id=<?= $row["id"] ?>' class='apply-btn'>🚀 Delete</a>
                </div>
            </div>
        <?php } ?>
    <?php else: ?>
        <p>No jobs found.</p>
    <?php endif; ?>
</div>

</body>
</html>
