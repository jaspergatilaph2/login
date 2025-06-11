<?php
include 'config.php';
$_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['email'], $_POST['password']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) ? registerUser($_POST['username'], $_POST['email'], $_POST['password']) : null;
function registerUser($username, $email, $password)
{
    global $connection;

    // Check if username or email already exists
    $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists.');</script>";
        return;
    }

    // Insert new user into the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Save user data to users.json (without password)
        $userData = [
            'username' => $username,
            'email' => $email,
            'registered_at' => date('Y-m-d H:i:s')
        ];
        $jsonFile = __DIR__ . '/users.json';
        $users = [];
        if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
            $jsonContent = file_get_contents($jsonFile);
            $users = json_decode($jsonContent, true) ?: [];
        }
        $users[] = $userData;
        file_put_contents($jsonFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        echo "<script>alert('Registration successful!');</script>";
        header("Location: register.php");
        exit();
    } else {
        echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/assets/css/register_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="shortcut icon" href="/assets/icons/icons8-register-100.png" type="image/x-icon">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <form action="" method="POST" class="bg-white p-4 rounded shadow" style="width: 350px;">
        <h3 class="mb-4 text-center">Register</h3>
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
        <a href="/login.php" class="btn btn-link">Already have an account? Login here</a>
    </form>
    <script src="/assets/js/eye_button.js"></script>
</body>

</html>