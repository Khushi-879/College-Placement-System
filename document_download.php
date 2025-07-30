<?php
$conn = new mysqli("localhost", "root", "", "college_placement");
if ($conn->connect_error) die("Connection failed.");

if (!isset($_GET['id']) || !isset($_GET['email'])) die("Invalid request.");

$id = (int)$_GET['id'];
$email = $_GET['email'];

$stmt = $conn->prepare("SELECT filename, filetype, filedata FROM student_documents WHERE id = ? AND student_email = ?");
$stmt->bind_param("is", $id, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) die("File not found.");

$stmt->bind_result($filename, $filetype, $filedata);
$stmt->fetch();

header("Content-Type: $filetype");
header("Content-Disposition: attachment; filename=\"$filename\"");
echo $filedata;
exit();
