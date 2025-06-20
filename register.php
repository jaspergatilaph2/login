<?php
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $log_date = date("Y-m-d");
    $log_time = date("H:i:s");

    $e_username = md5($username);
    $email = $_POST['email'];
    $e_password = md5($password);
    $e_logdate = md5($log_date);
    $e_logtime = md5($log_time);

    // Save to MySQL
    $stmt = $connection->prepare("INSERT INTO users
        (username, e_userName, email, password, e_password, log_date, e_log_date, log_time, e_log_time) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param(
        "sssssssss",
        $username,
        $e_username,
        $email,
        $password,
        $e_password,
        $log_date,
        $e_logdate,
        $log_time,
        $e_logtime
    );

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!');</script>";
        header('Location: login.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();

    $jsonFile = __DIR__ . '/users.json';

    $userData = [
        'username' => $username,
        'e_username' => $e_username,
        'password' => $password,
        'e_password' => $e_password,
        'log_date' => $log_date,
        'e_logdate' => $e_logdate,
        'log_time' => $log_time,
        'e_logtime' => $e_logtime
    ];

    $users = [];
    if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
        $jsonContent = file_get_contents($jsonFile);
        $users = json_decode($jsonContent, true) ?: [];
    }

    $users[] = $userData;

    $jsonContent = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($jsonFile, $jsonContent);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/register_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/icons/icons8-register-100.png" type="image/x-icon">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <form action="" method="POST" class="bg-white p-4 rounded shadow" style="width: 350px;">
        <img src="assets/icons/icons8-register-100.png" alt="Logo" class="mx-auto d-block mb-3" style="width: 100px; height: auto;">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
        <a href="login.php" class="btn btn-link">Already have an account? Login here</a>
        <a hrer="" class="btn btn-link">Please register your google account</a>
    </form>
    <script src="assets/js/eye_button.js"></script>
</body>

</html>