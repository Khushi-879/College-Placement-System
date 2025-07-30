<?php
// Example: dashboard_selector.php
if (!isset($_GET['email'])) {
    die("Email not specified.");
}
$email = $_GET['email'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Dashboard</title>
</head>
<body>
    <h2>Welcome!</h2>
    <p>Select a dashboard:</p>
    <ul>
        <li><a href="student_dashboard.php?email=<?= urlencode($email) ?>">📊 Student Dashboard (Companies)</a></li>
        <li><a href="document_download.php?email=<?= urlencode($email) ?>">📂 Document Download (Upload/Download)</a></li>
    </ul>
</body>
</html>
