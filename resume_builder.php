<?php

include("../login/db_con.php");

// Show success message if present in GET
$success = $_GET['success'] ?? '';

// Fetch resume templates
$templates = mysqli_query($conn, "SELECT * FROM resume_templates");

// Include header
include("../header.php");
?>

<!-- Page Content -->
<style>
/* Page-specific CSS for resume templates */
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px 15px;
    margin: 20px auto;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
    max-width: 800px;
    text-align: center;
}

.page-title {
    text-align: center;
    color: #333;
    font-size: 28px;
    font-weight: 600;
}

.template-container {
    max-width: 1200px;
    margin: 0 auto 50px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 20px;
    padding: 0 15px;
}

.template-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.template-card img {
    width: 100%;
    height: 220px;
    object-fit: contain;
    background: #f9f9f9;
}

.template-card h4 {
    font-size: 18px;
    margin: 15px;
    color: #222;
    text-transform: capitalize;
}

.template-card p {
    margin: 0 15px 15px;
    font-size: 14px;
    color: #555;
}

.template-card form {
    margin: auto 0 15px;
    text-align: center;
}

.template-card button {
    padding: 8px 15px;
    font-size: 14px;
    border: none;
    border-radius: 5px;
    background: #2C3E50; /* Darker button to match header */
    color: white;
    cursor: pointer;
    transition: background 0.2s ease;
}

.template-card button:hover {
    background: #16124a;
}

/* Adjust top padding to account for fixed header if needed */
.main-content {
    padding-top: 30px;
}
</style>

<div class="main-content">

<?php if (!empty($success)): ?>
  <div class="success-message">
    ✅ <?= htmlspecialchars($success) ?>
  </div>
<?php endif; ?>

<h2 class="page-title">Select Resume Template</h2>

<div class="template-container">
  <?php while ($row = mysqli_fetch_assoc($templates)): ?>
    <div class="template-card">
      <img src="<?= htmlspecialchars($row['preview_image']) ?>" alt="Preview">
      <h4><?= htmlspecialchars($row['name']) ?></h4>
      <p><?= htmlspecialchars($row['category']) ?> | <?= htmlspecialchars($row['style']) ?> | <?= htmlspecialchars($row['color']) ?></p>
      <form method="GET" action="../login/login.php">
        <input type="hidden" name="template_id" value="<?= $row['id'] ?>">
        <button type="submit">Use This Template</button>
      </form>
    </div>
  <?php endwhile; ?>
</div>

</div>
<?php include '../footer.php' ?>