<?php
include 'config.php';
session_start();
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['username'], $_POST['email'], $_POST['password']) &&
    !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])
) {
    loginUser($_POST['username'], $_POST['email'], $_POST['password']);
}

function loginUser($username, $email, $password)
{
    global $connection;

    $e_username = md5($username);
    $stmt = $connection->prepare('SELECT * FROM users WHERE e_userName = ? AND email = ?');
    $stmt->bind_param('ss', $e_username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['e_password'])) {
            session_regenerate_id(true);
            $_SESSION['id'] = $user['id'];
            header('Location: dashboard.php');
            exit();
        } else {
            echo "<script>alert('Invalid credentials');</script>";
        }
    } else {
        echo "<script>alert('User not found!');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/login_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="shortcut icon" href="/assets/icons/icons8-register-100.png" type="image/x-icon">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <form action="" method="post" class="bg-white p-4 rounded shadow" style="width: 350px;">
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
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <a href="register.php" class="btn btn-link">Don't have an account? Register here</a>
    </form>
    <script src="/assets/js/eye_button.js"></script>
</body>

</html>