<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $cgpa = floatval($_POST['cgpa']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO students (name, email, cgpa, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $email, $cgpa, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Student registered successfully!'); window.location.href='student_login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Student Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Rubik', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: white;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            width: 380px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            font-size: 2em;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 1em;
            outline: none;
        }

        input::placeholder {
            color: #f0f0f0;
        }

        button {
            margin-top: 20px;
            padding: 12px 30px;
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            color: white;
            font-size: 1em;
            font-weight: bold;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        button:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Student Sign Up</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required /><br />
            <input type="email" name="email" placeholder="Email" required /><br />
            <input type="text" name="cgpa" placeholder="CGPA (e.g. 3.75)" required pattern="\d+(\.\d{1,2})?" title="Enter a valid CGPA" /><br />
            <input type="password" name="password" placeholder="Password" required /><br />
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>
