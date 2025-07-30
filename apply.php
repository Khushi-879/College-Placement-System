<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_POST['company_id'];

    // Check if already applied
    $check = $conn->prepare("SELECT * FROM applications WHERE student_id = ? AND company_id = ?");
    $check->bind_param("ii", $student_id, $company_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO applications (student_id, company_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $student_id, $company_id);
        $stmt->execute();
        $success = true;
    } else {
        $error = "You've already applied to this company.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Jobs</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            padding: 30px;
        }

        .job-card {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 6px solid #007bff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        h2 {
            color: #333;
        }

        .apply-btn {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .apply-btn:hover {
            background-color: #0056b3;
        }

        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h2>📋 Available Job Postings</h2>

<?php if (!empty($success)) echo "<div class='success'>✅ Application submitted successfully!</div>"; ?>
<?php if (!empty($error)) echo "<div class='error'>⚠️ $error</div>"; ?>

<?php
$result = $conn->query("SELECT * FROM companies ORDER BY id DESC");
while ($row = $result->fetch_assoc()):
?>
    <div class="job-card">
        <h3><?= htmlspecialchars($row['job_title']) ?> at <?= htmlspecialchars($row['name']) ?></h3>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($row['job_description'])) ?></p>
        <p><strong>Minimum CGPA:</strong> <?= $row['min_cgpa'] ?></p>
        <form method="POST">
            <input type="hidden" name="company_id" value="<?= $row['id'] ?>">
            <button type="submit" class="apply-btn">Apply Now</button>
        </form>
    </div>
<?php endwhile; ?>

</body>
</html>
