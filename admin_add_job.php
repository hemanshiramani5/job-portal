<?php
if (isset($_POST['submit'])) {
    $conn = new mysqli("localhost", "root", "", "jobwave");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $logo = "";
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $logo = basename($_FILES["logo"]["name"]);
        $target_dir = "uploads/";
        $target_file = $target_dir . $logo;
        move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);
    }

    $title = $conn->real_escape_string($_POST['title']);
    $company = $conn->real_escape_string($_POST['company']);
    $experience = $conn->real_escape_string($_POST['experience_range']);
    $salary = $conn->real_escape_string($_POST['salary_range']);
    $location = $conn->real_escape_string($_POST['location']);
    $early_applicant = isset($_POST['early_applicant']) ? 1 : 0;

    $sql = "INSERT INTO jobs (title, company, experience_range, salary_range, location, is_early_applicant, logo)
            VALUES ('$title', '$company', '$experience', '$salary', '$location', '$early_applicant', '$logo')";

    if ($conn->query($sql)) {
        // Redirect to job_list.php after success
        header("Location: job_list.php");
        exit(); // Always call exit after redirect
    } else {
        $message = "❌ Error: " . $conn->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Job - Admin Panel</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f2f6;
            margin: 0;
        }

        main.content-area {
            padding: 40px 0;
        }

        .form-container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }

        input[type="text"], input[type="file"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            color: green;
        }
    </style>
</head>
<body>

    <!-- Header include -->
    <?php include("main_page.php"); ?>  <!-- ✅ replace with correct path -->

    <!-- Main content -->
    <main class="content-area">
        <div class="form-container">
            <h2>Add New Job</h2>
            <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <label>Job Title:</label>
                <input type="text" name="title" required>

                <label>Company Name:</label>
                <input type="text" name="company" required>

                <label>Experience (e.g. 7-15 yrs):</label>
                <input type="text" name="experience_range" required>

                <label>Salary (e.g. ₹ 13 - 20 LPA):</label>
                <input type="text" name="salary_range" required>

                <label>Location:</label>
                <input type="text" name="location" required>

                <label>Company Logo:</label>
                <input type="file" name="logo" accept="image/*">

                <label><input type="checkbox" name="early_applicant"> Early Applicant</label>

                <input type="submit" name="submit" value="Add Job">
            </form>
        </div>
    </main>

</body>
</html>
