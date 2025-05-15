<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'password') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $password_upper = strtoupper($password);
        $sql = "SELECT * FROM students WHERE id = ? AND last_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password_upper);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['student_id'] = $username;
            header("Location: student_dashboard.php");
            exit();
        } else {
            $error = "Invalid login credentials!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- FontAwesome for Eye Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- External JS -->
    <script src="js/passwordToggle.js" defer></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
        }

        form {
            max-width: 400px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .password-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #eye-icon {
            cursor: pointer;
            color: #555;
            font-size: 18px;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h2>Login</h2>

<?php if (isset($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="login.php">
    <div class="form-group">
        <label for="username">Username (Student ID or Admin):</label>
        <input type="text" id="username" name="username" required value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
    </div>

    <div class="form-group">
        <label for="password">Password (Last Name for Students):</label>
        <div class="password-wrapper">
            <input type="password" id="password" name="password" required placeholder="Enter password">
            <span id="eye-icon"><i class="fas fa-eye"></i></span>
        </div>
    </div>

    <button type="submit">Login</button>
</form>

</body>
</html>
