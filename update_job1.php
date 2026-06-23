<?php
// Start output buffering to avoid header issues (optional but safe)
ob_start();

$conn = new mysqli("localhost", "root", "", "jobwave");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid job ID.");
}

$job_id = intval($_GET['id']);

// Initialize variables
$title = $company = $experience_range = $salary_range = $location = $logo = "";
$is_early_applicant = 0;
$error = "";

// Fetch current job data
$sql = "SELECT * FROM jobs WHERE id = $job_id";
$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    die("Job not found.");
}

$row = $result->fetch_assoc();
$current_logo = $row['logo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $title = $conn->real_escape_string(trim($_POST['title']));
    $company = $conn->real_escape_string(trim($_POST['company']));
    $experience_range = $conn->real_escape_string(trim($_POST['experience_range']));
    $salary_range = $conn->real_escape_string(trim($_POST['salary_range']));
    $location = $conn->real_escape_string(trim($_POST['location']));
    $is_early_applicant = isset($_POST['is_early_applicant']) ? 1 : 0;

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $file_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $new_logo_name = "logo_" . time() . "_" . uniqid() . "." . $file_extension;
        $target_file = $target_dir . $new_logo_name;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            // Delete old logo if exists
            if (!empty($current_logo) && file_exists($target_dir . $current_logo)) {
                unlink($target_dir . $current_logo);
            }
            $logo = $new_logo_name;

            $sql_update = "UPDATE jobs SET
                            title='$title',
                            company='$company',
                            experience_range='$experience_range',
                            salary_range='$salary_range',
                            location='$location',
                            is_early_applicant=$is_early_applicant,
                            logo='$logo'
                        WHERE id=$job_id";
        } else {
            $error = "Failed to upload logo.";
        }
    } else {
        // Update without changing the logo
        $sql_update = "UPDATE jobs SET
                        title='$title',
                        company='$company',
                        experience_range='$experience_range',
                        salary_range='$salary_range',
                        location='$location',
                        is_early_applicant=$is_early_applicant
                    WHERE id=$job_id";
    }

    if (empty($error)) {
        if ($conn->query($sql_update) === TRUE) {
            // Redirect on success
            header("Location: job_list.php");
            exit();
        } else {
            $error = "Error updating job: " . $conn->error;
        }
    }
} else {
    // Pre-fill form with existing data on GET
    $title = $row['title'];
    $company = $row['company'];
    $experience_range = $row['experience_range'];
    $salary_range = $row['salary_range'];
    $location = $row['location'];
    $is_early_applicant = $row['is_early_applicant'];
}

// Include your header AFTER redirect logic to avoid header errors
include 'main_page.php';

// End output buffering and flush output (optional)
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Job</title>
    <style>
        /* your styles here, same as before */
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            height: 100%;
        }

        main {
            padding: 100px 30px 30px 30px;
            max-width: 700px;
            margin: 0 auto;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            background: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .checkbox-label {
            font-weight: normal;
            margin-top: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        button {
            margin-top: 25px;
            padding: 14px 0;
            border-radius: 8px;
            border: none;
            background-color: #7a2ff2;
            color: white;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            width: 100%;
        }

        button:hover {
            background-color: #601bd9;
        }

        .message {
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
        }

        .error {
            color: #e74c3c;
        }

        img.preview {
            margin-top: 10px;
            max-height: 60px;
        }
    </style>
</head>
<body>

<main>
    <h1>Update Job</h1>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="title">Job Title</label>
        <input type="text" id="title" name="title" required value="<?= htmlspecialchars($title) ?>">

        <label for="company">Company</label>
        <input type="text" id="company" name="company" required value="<?= htmlspecialchars($company) ?>">

        <label for="experience_range">Experience Range</label>
        <input type="text" id="experience_range" name="experience_range" required value="<?= htmlspecialchars($experience_range) ?>">

        <label for="salary_range">Salary Range</label>
        <input type="text" id="salary_range" name="salary_range" required value="<?= htmlspecialchars($salary_range) ?>">

        <label for="location">Location</label>
        <input type="text" id="location" name="location" required value="<?= htmlspecialchars($location) ?>">

        <label class="checkbox-label">
            <input type="checkbox" name="is_early_applicant" <?= $is_early_applicant ? "checked" : "" ?>>
            Early Applicant
        </label>

        <label for="logo">Company Logo</label>
        <input type="file" id="logo" name="logo" accept="image/*">
        <?php if (!empty($current_logo)): ?>
            <img src="uploads/<?= htmlspecialchars($current_logo) ?>" alt="Current Logo" class="preview">
        <?php endif; ?>

        <button type="submit">Update Job</button>
    </form>
</main>

</body>
</html>
