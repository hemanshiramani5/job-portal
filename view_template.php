<?php
include("../login/db_con.php");
$result = $conn->query("SELECT * FROM resume_templates");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Resume Templates</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            padding: 5px;
           
        }l

        /* Popup styles */
        .popup {
            padding: 15px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            position: fixed;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 5px;
            transition: top 0.4s ease;
            z-index: 9999;
            width: 80%;
            max-width: 500px;
        }
        .popup.show {
            top: 20px;
        }
        .popup.success {
            background-color: #2ecc71;
            color: white;
        }
        .popup.error {
            background-color: #e74c3c;
            color: white;
        }

        .template-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .template-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease;
        }
        .template-card:hover {
            transform: translateY(-5px);
        }
        .preview {
            width: 100%;
            height: 300px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .template-details {
            padding: 15px;
            flex-grow: 1;
        }
        .template-details h3 {
            margin: 0;
            font-size: 18px;
            color: #2d3436;
        }
        .template-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #636e72;
        }
        .actions {
            display: flex;
            justify-content: space-around;
            padding: 10px;
            border-top: 1px solid #dfe6e9;
        }
        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-edit {
            background-color: #a99ed7ff;
        }
        .btn-edit:hover {
            background-color: #390b97ff;
        }
        .btn-delete {
            background-color: #d0717fbc;
        }
        .btn-delete:hover {
            background-color: #590909dd;
        }
    </style>
</head>
<body>

<?php include("main_page.php"); ?> <!-- ✅ include your main page -->
<!-- Popup Message -->
<?php if (isset($_GET['success'])): ?>
    <div class="popup success" id="popup"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="popup error" id="popup"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<h1>All Resume Templates</h1>
<div class="template-container">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="template-card">
            <div class="preview">
                <img src="<?php echo $row['preview_image']; ?>" alt="Preview">
            </div>
            <div class="template-details">
                <h3><?php echo ucfirst($row['name']); ?></h3>
                <p><b>Category:</b> <?php echo $row['category']; ?></p>
                <p><b>Color:</b> <?php echo $row['color']; ?></p>
                <p><b>Style:</b> <?php echo $row['style']; ?></p>
            </div>
            <div class="actions">
                <a href="edit_template.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="delete_template.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this template?')">Delete</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script>
    // Show popup animation
    const popup = document.getElementById("popup");
    if (popup) {
        setTimeout(() => {
            popup.classList.add("show");
        }, 100);
        setTimeout(() => {
            popup.classList.remove("show");
        }, 3000);
    }
</script>

</body>
</html>
