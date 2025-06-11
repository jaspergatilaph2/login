<?php
include 'config.php';
$_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['email'], $_POST['password']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) ? loginUser($_POST['username'], $_POST['email'], $_POST['password']) : null;
function loginUser($username, $email, $password) {
    global $connection;

    // Check if username or email exists
    $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Username or Email does not exist.');</script>";
        return;
    }

    // Fetch user data
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo "<script>alert('Login successful!');</script>";
        // header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Incorrect password.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/css/login_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="shortcut icon" href="/assets/icons/icons8-register-100.png" type="image/x-icon">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <form action="" method="post" class="bg-white p-4 rounded shadow" style="width: 350px;">
         <h3 class="mb-4 text-center">Login</h3>
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
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <a href="/register.php" class="btn btn-link">Don't have an account? Register here</a>
    </form>
    <script src="/assets/js/eye_button.js"></script>
</body>
</html>