<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Eligible Companies</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            margin: 0;
            padding: 40px 20px;
            color: #ffffff;
            min-height: 100vh;
        }

        h2 {
            text-align: center;
            font-size: 3.5em;
            color: #ffffff;
            margin-bottom: 50px;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
        }

        ul {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 30px;
            max-width: 1300px;
            margin: 0 auto;
        }

        li {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border-left: 12px solid #00f2fe;
            transition: all 0.3s ease;
        }

        li:hover {
            transform: translateY(-12px);
            border-left-color: #ff6ec4;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        strong {
            font-size: 1.8em;
            color: #ffffff;
            display: block;
            margin-bottom: 10px;
        }

        li p {
            font-size: 1.2em;
            line-height: 1.6;
            color: #e0e0e0;
        }

        form {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        button {
            background: linear-gradient(45deg, #ff6ec4, #7873f5);
            border: none;
            color: white;
            padding: 14px 30px;
            border-radius: 30px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
        }

        button:hover {
            transform: scale(1.08);
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.6);
        }

        @media (max-width: 600px) {
            h2 {
                font-size: 2.5em;
            }

            button {
                width: 100%;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <h2>🌟 Eligible Companies</h2>
    <ul>
        <?php foreach ($eligible_companies as $company): ?>
            <li>
                <strong><?php echo $company['name']; ?></strong> - <?php echo $company['job_title']; ?>
                <p><?php echo $company['job_description']; ?></p>
                <form action="apply_company.php" method="POST">
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <input type="hidden" name="company_id" value="<?php echo $company['id']; ?>">
                    <button type="submit">🚀 Apply Now</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
