<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $name, $stored_password);
    $stmt->fetch();

    // Compare plain text passwords
    if ($id && $password === $stored_password) {
        //$_SESSION['student_id'] = $id;
        //$_SESSION['student_name'] = $name;
         header("Location: student_dashboard.php?email=" . urlencode($email));
        exit();
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }


    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Student Login</title>
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
            width: 350px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            font-size: 2em;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 25px;
            background: rgba(12, 10, 10, 0.2);
            color: white;
            font-size: 1em;
            outline: none;
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
        <h2>Student Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required /><br />
            <input type="password" name="password" placeholder="Password" required /><br />
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
