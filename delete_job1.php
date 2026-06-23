<?php
include 'main_page.php'; // include header/navigation

$conn = new mysqli("localhost", "root", "", "jobwave");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get job ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid job ID.");
}

$job_id = intval($_GET['id']);
$error = "";
$success = "";

// Handle delete confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "DELETE FROM jobs WHERE id = $job_id";

    if ($conn->query($sql) === TRUE) {
        $success = "Job deleted successfully.";
    } else {
        $error = "Error deleting job: " . $conn->error;
    }
}

// Fetch job details to show on confirmation
$sql = "SELECT * FROM jobs WHERE id = $job_id";
$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Redirecting...</title>
        <meta http-equiv='refresh' content='3;url=job_list.php'>
        <style>
            .message-box {
                background: #fff;
                padding: 30px 50px;
                border: 1px solid #ffeeba;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                text-align: center;
            }
            .message-box h2 {
                color: #856404;
            }
            .message-box p {
                margin-top: 10px;
                color: #856404;
            }
        </style>
    </head>
    <body>
        <div class='message-box'>
            <h2>⚠️ Job Not Found</h2>
            <p>Redirecting you to job listings in 3 seconds...</p>
        </div>
    </body>
    </html>";
    exit;
}


$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Job</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            height: 100%;
            box-sizing: border-box;
        }

        header {
            height: 80px;
            width: 100%;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            font-size: 20px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 2px;
            font-weight: 600;
            font-size: 16px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        main {
            padding: 100px 30px 30px 30px;
            max-width: 700px;
            margin: 0 auto;
            box-sizing: border-box;
            min-height: 100vh;
        }

        h1 {
            margin-bottom: 20px;
        }

        .job-info {
            background: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .job-info p {
            margin: 8px 0;
            font-size: 15px;
        }

        .message {
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: #e74c3c;
        }

        .success {
            color: #2ecc71;
        }

        form {
            text-align: center;
        }

        button {
            margin: 10px 8px;
            padding: 12px 25px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .cancel-btn {
            background-color: #7f8c8d;
            color: white;
        }

        .cancel-btn:hover {
            background-color: #636e72;
        }

        @media (max-width: 768px) {
            main {
                padding: 120px 15px;
                max-width: 100%;
            }
            .job-info {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
<main>
    <h1>Delete Job</h1>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php else: ?>
        <div class="job-info">
            <p><strong>Title:</strong> <?= htmlspecialchars($row['title']) ?></p>
            <p><strong>Company:</strong> <?= htmlspecialchars($row['company']) ?></p>
            <p><strong>Experience:</strong> <?= htmlspecialchars($row['experience_range']) ?></p>
            <p><strong>Salary:</strong> <?= htmlspecialchars($row['salary_range']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
        </div>

        <form method="POST">
            <p>Are you sure you want to delete this job?</p>
            <button type="submit" class="delete-btn">Yes, Delete</button>
            <a href="delete_job.php"><button type="button" class="cancel-btn">Cancel</button></a>
        </form>
    <?php endif; ?>
</main>
</body>
</html>
