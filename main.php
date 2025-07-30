<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Placement System</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Rubik', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            text-align: center;
            margin-top: 60px;
        }

        header h1 {
            font-size: 3.5em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
        }

        .portals {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 60px;
            gap: 40px;
        }

        .portal-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            width: 300px;
            text-align: center;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease;
        }

        .portal-card:hover {
            transform: translateY(-10px);
        }

        .portal-card h2 {
            font-size: 2em;
            margin-bottom: 15px;
        }

        .portal-card p {
            font-size: 1em;
            margin-bottom: 20px;
            color: #f0f0f0;
        }

        .portal-actions a {
            display: inline-block;
            margin: 10px;
            padding: 12px 25px;
            font-size: 1em;
            font-weight: bold;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            transition: transform 0.2s ease;
        }

        .portal-actions a:hover {
            transform: scale(1.05);
        }

        footer {
            margin-top: auto;
            padding: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #ddd;
        }

        @media (max-width: 600px) {
            header h1 {
                font-size: 2.5em;
            }

            .portal-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>🎓 College Placement System</h1>
    </header>

    <section class="portals">
        <!-- Student Portal -->
        <div class="portal-card">
            <h2>Student Portal</h2>
            <p>Upload resumes, view companies, and apply for jobs.</p>
            <div class="portal-actions">
                <a href="student_login.php">Student Login</a>
                <a href="student_signup.php">Student Sign Up</a>
            </div>
        </div>

        <!-- Admin Portal -->
        <div class="portal-card">
            <h2>Admin Portal</h2>
            <p>Manage company postings and review student applications.</p>
            <div class="portal-actions">
                <a href="admin_login.php">Admin Login</a>
                
            </div>
        </div>
    </section>

    <footer>
        &copy; <?php echo date('Y'); ?> College Placement Cell. All rights reserved.
    </footer>

</body>
</html>
