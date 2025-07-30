<?php
session_start();
$conn = new mysqli("localhost", "root", "", "college_placement");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Check if student is logged in
if (!isset($_SESSION['student_email'])) {
    if (isset($_GET['email'])) {
        $_SESSION['student_email'] = $_GET['email'];
    } else {
        die("Unauthorized access. Please <a href='student_login.php'>login</a>.");
    }
}
$student_email = $_SESSION['student_email'];

// Fetch student info
$stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
$stmt->bind_param("s", $student_email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
if (!$student) die("Student not found.");

$student_id = $student['id'];
$student_cgpa = $student['cgpa'];
$view = $_GET['view'] ?? 'dashboard';
$upload_message = "";

// Document upload logic
if ($view === 'dashboard' && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["document"])) {
    $file = $_FILES["document"];
    $name = $file["name"];
    $type = mime_content_type($file["tmp_name"]);
    $size = $file["size"];
    $data = file_get_contents($file["tmp_name"]);

    $allowed = ['application/pdf', 'image/jpeg', 'image/png'];
    if (in_array($type, $allowed) && $size <= 5 * 1024 * 1024) {
        $upload = $conn->prepare("INSERT INTO student_documents (student_email, filename, filetype, filedata) VALUES (?, ?, ?, ?)");
        $upload->bind_param("ssss", $student_email, $name, $type, $data);
        if ($upload->execute()) {
            $upload_message = "<p style='color:green;'>Upload successful!</p>";
        } else {
            $upload_message = "<p style='color:red;'>Upload failed.</p>";
        }
    } else {
        $upload_message = "<p style='color:red;'>Invalid file type or size (max 5MB).</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Portal - College Placement</title>
    <style>
        body, html { margin:0; padding:0; font-family: Arial,sans-serif; background:#eef2f7; }
        .topnav { background:#007bff; color:#fff; height:60px; display:flex; align-items:center; padding:0 20px; }
        .topnav .logo { font-size:22px; font-weight:bold; margin-right:auto; }
        .topnav a { color:#fff; padding:18px 20px; text-decoration:none; font-weight:bold; border-radius:4px; }
        .topnav a.active, .topnav a:hover { background:#0056b3; }
        .container { padding:40px 60px; background:#fff; max-width:1000px; margin:80px auto; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); min-height:80vh; }
        table { width:100%; border-collapse:collapse; margin-top:20px; font-size:16px; }
        th, td { padding:15px; border:1px solid #ddd; text-align:left; }
        th { background:#007bff; color:#fff; }
        form { margin-top:20px; text-align:center; }
        input[type="file"] { margin-right:10px; font-size:16px; }
        input[type="submit"] { background:#007bff; color:#fff; padding:10px 25px; border:none; border-radius:5px; font-size:16px; cursor:pointer; }
        input[type="submit"]:hover { background:#0056b3; }
        .apply-btn { background:#28a745; color:#fff; padding:8px 16px; border:none; border-radius:5px; cursor:pointer; }
        .apply-btn:hover:not(:disabled) { background:#218838; }
        .apply-btn:disabled { background:#dc3545; }
    </style>
</head>
<body>

<div class="topnav">
    <div class="logo">College Placement</div>
    <a href="?view=dashboard" class="<?= $view === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
    <a href="?view=companies" class="<?= $view === 'companies' ? 'active' : '' ?>">Companies</a>
    <a href="?view=interviews" class="<?= $view === 'interviews' ? 'active' : '' ?>">Interviews</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
<?php if ($view === 'dashboard'): ?>
    <h2>Welcome, <?= htmlspecialchars($student['name']) ?></h2>
    <p><strong>CGPA:</strong> <?= htmlspecialchars($student_cgpa) ?></p>

    <h3>Upload Document</h3>
    <?= $upload_message ?>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="document" required>
        <input type="submit" value="Upload">
    </form>

    <h3>Your Uploaded Documents</h3>
    <?php
    $doc_stmt = $conn->prepare("SELECT id, filename, uploaded_on FROM student_documents WHERE student_email = ?");
    $doc_stmt->bind_param("s", $student_email);
    $doc_stmt->execute();
    $documents = $doc_stmt->get_result();
    ?>
    <?php if ($documents->num_rows > 0): ?>
        <table>
            <tr><th>Filename</th><th>Uploaded On</th><th>Action</th></tr>
            <?php while ($doc = $documents->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($doc['filename']) ?></td>
                    <td><?= htmlspecialchars($doc['uploaded_on']) ?></td>
                    <td><a href="download_document.php?id=<?= $doc['id'] ?>">Download</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No documents uploaded yet.</p>
    <?php endif; ?>

<?php elseif ($view === 'companies'): ?>
    <h2>Eligible Companies</h2>
    <?php
    $company_stmt = $conn->prepare("SELECT * FROM companies WHERE min_cgpa <= ?");
    $company_stmt->bind_param("d", $student_cgpa);
    $company_stmt->execute();
    $companies = $company_stmt->get_result();
    ?>
    <table>
        <tr>
            <th>Name</th><th>Title</th><th>Type</th><th>Location</th><th>Description</th><th>Min CGPA</th><th>Deadline</th><th>Apply</th>
        </tr>
        <?php while ($row = $companies->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['job_title']) ?></td>
                <td><?= htmlspecialchars($row['job_type']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
                <td><?= htmlspecialchars($row['job_description']) ?></td>
                <td><?= htmlspecialchars($row['min_cgpa']) ?></td>
                <td><?= htmlspecialchars($row['deadline']) ?></td>
                <td><button class="apply-btn" onclick="this.innerText='Applied';this.disabled=true;">Apply</button></td>
            </tr>
        <?php endwhile; ?>
    </table>

<?php elseif ($view === 'interviews'): ?>
    <h2>Your Interviews</h2>
    <?php
    $stmt = $conn->prepare("SELECT c.name AS company_name, i.interview_date, i.interview_time, i.mode, i.status, i.remarks FROM interview_schedule i JOIN companies c ON i.company_id = c.id WHERE i.student_id = ? ORDER BY i.interview_date, i.interview_time");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $interviews = $stmt->get_result();
    ?>
    <?php if ($interviews->num_rows > 0): ?>
        <table>
            <tr><th>Company</th><th>Date</th><th>Time</th><th>Mode</th><th>Status</th><th>Remarks</th></tr>
            <?php while ($row = $interviews->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['company_name']) ?></td>
                    <td><?= htmlspecialchars($row['interview_date']) ?></td>
                    <td><?= htmlspecialchars($row['interview_time']) ?></td>
                    <td><?= htmlspecialchars($row['mode']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['remarks'] ?? '-') ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No interview slots scheduled.</p>
    <?php endif; ?>
<?php endif; ?>
</div>

</body>
</html>
