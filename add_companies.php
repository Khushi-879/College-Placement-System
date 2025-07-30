<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $min_cgpa = $_POST['min_cgpa'];
    $job_type = $_POST['job_type'];
    $location = $_POST['location'];
    $deadline = $_POST['deadline'];

    $stmt = $conn->prepare("INSERT INTO companies (name, job_title, job_description, min_cgpa, job_type, location, deadline) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdsis", $name, $job_title, $job_description, $min_cgpa, $job_type, $location, $deadline);
    $stmt->execute();
    $stmt->close();

    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Post New Company</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: #fff;
            padding: 50px 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-top: 20px;
            font-size: 1.1em;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border-radius: 10px;
            border: none;
            font-size: 1em;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            margin-top: 30px;
            width: 100%;
            padding: 15px;
            font-size: 1.1em;
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            border: none;
            color: #fff;
            border-radius: 30px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(255, 65, 108, 0.4);
        }

        .success {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            color: #aaffaa;
        }

        @media (max-width: 600px) {
            h2 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🧑‍💼 Post a New Company</h2>
        <?php if (!empty($success)) echo "<div class='success'>✅ Company posted successfully!</div>"; ?>
        <form method="POST" action="">
            <label for="name">Company Name</label>
            <input type="text" name="name" required>

            <label for="job_title">Job Title</label>
            <input type="text" name="job_title" required>

            <label for="job_description">Job Description</label>
            <textarea name="job_description" required></textarea>

            <label for="min_cgpa">Minimum CGPA</label>
            <input type="number" step="0.01" name="min_cgpa" required>

            <label for="job_type">Job Type</label>
            <select name="job_type" required>
                <option value="">-- Select Type --</option>
                <option value="Full-Time">Full-Time</option>
                <option value="Internship">Internship</option>
                <option value="Contract">Contract</option>
                <option value="Part-Time">Part-Time</option>
            </select>

            <label for="location">Location</label>
            <select name="location" required>
                <option value="">-- Select Location --</option>
                <option value="Remote">Remote</option>
                <option value="On-site">On-site</option>
                <option value="Hybrid">Hybrid</option>
            </select>

            <label for="deadline">Application Deadline</label>
            <input type="date" name="deadline" required>


            <button type="submit">🚀 Post Company</button>
        </form>
    </div>
</body>
</html>
