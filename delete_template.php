<?php
include("../login/db_con.php");

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: template_list.php?success=" . urlencode("Template ID missing."));
    exit();
}

$id = intval($_GET['id']);

// Get template details before deleting (to remove files)
$result = mysqli_query($conn, "SELECT * FROM resume_templates WHERE id = $id");
if (mysqli_num_rows($result) == 0) {
    header("Location: template_list.php?success=" . urlencode("Template not found."));
    exit();
}
$template = mysqli_fetch_assoc($result);

// Delete files from server (if they exist)
if (!empty($template['template_file']) && file_exists($template['template_file'])) {
    unlink($template['template_file']);
}
if (!empty($template['preview_image']) && file_exists($template['preview_image'])) {
    unlink($template['preview_image']);
}

// Delete record from DB
mysqli_query($conn, "DELETE FROM resume_templates WHERE id = $id");

// Redirect back with success popup
header("Location: view_template.php?success=" . urlencode("Template deleted successfully"));
exit();
?>
