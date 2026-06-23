<?php
session_start();
include '../login/db_con.php';

$id = $_GET['id'] ?? 0;
$conn->query("DELETE FROM mock_questions WHERE id=$id");

header("Location: admin_mock.php");
exit;
