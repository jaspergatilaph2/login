<?php
include 'config.php';
session_start();

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    // Redirect to login if not logged in
    header('Location: login.php');
    exit();
}

$stmt = $connection->prepare('SELECT username, e_userName, password, e_password, log_date, e_log_date, log_time, e_log_time FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body style="background: #f8f9fa; font-family: Arial, sans-serif;">
    <div style="max-width: 700px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2 style="margin: 0 0 6px 0; color: #343a40;">Dashboard</h2>
                <h4 style="margin: 0; color:#343a40;">Welcome <?= htmlspecialchars($row['username']) ?></h4>
            </div>
            <a href="/logout.php" style="text-decoration: none;">
                <button style="background: #dc3545; color: #fff; border: none; padding: 8px 18px; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background 0.2s;">
                    Log Out
                </button>
            </a>
        </div>
        <table style="width: 100%; border-collapse: collapse; background: #f1f3f4; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <thead>
                <tr style="background: linear-gradient(90deg, #343a40 60%, #495057 100%); color: #fff;">
                    <th style="padding: 16px; font-size: 18px; border-radius: 8px 0 0 0; letter-spacing: 1px;">Field</th>
                    <th style="padding: 16px; font-size: 18px; letter-spacing: 1px;">Not Encrypted</th>
                    <th style="padding: 16px; font-size: 18px; border-radius: 0 8px 0 0; letter-spacing: 1px;">Encrypted</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($row): ?>
                    <tr style="background: #fff;">
                        <td style="padding: 14px 10px; font-weight: bold;">Username</td>
                        <td style="padding: 14px 10px; color: #212529;"><?= htmlspecialchars($row['username']) ?></td>
                        <td style="padding: 14px 10px; color: #495057;"><?= htmlspecialchars($row['e_userName']) ?></td>
                    </tr>
                    <tr style="background: #f8f9fa;">
                        <td style="padding: 14px 10px; font-weight: bold;">Password</td>
                        <td style="padding: 14px 10px; color: #212529;"><?= htmlspecialchars($row['password']) ?></td>
                        <td style="padding: 14px 10px; color: #495057; word-break: break-all;"><?= htmlspecialchars($row['e_password']) ?></td>
                    </tr>
                    <tr style="background: #fff;">
                        <td style="padding: 14px 10px; font-weight: bold;">Log Date</td>
                        <td style="padding: 14px 10px; color: #212529;"><?= htmlspecialchars($row['log_date']) ?></td>
                        <td style="padding: 14px 10px; color: #495057;"><?= htmlspecialchars($row['e_log_date']) ?></td>
                    </tr>
                    <tr style="background: #f8f9fa;">
                        <td style="padding: 14px 10px; font-weight: bold;">Log Time</td>
                        <td style="padding: 14px 10px; color: #212529;"><?= htmlspecialchars($row['log_time']) ?></td>
                        <td style="padding: 14px 10px; color: #495057;"><?= htmlspecialchars($row['e_log_time']) ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="padding: 18px; text-align: center; color: #dc3545; font-weight: bold;">No user data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>