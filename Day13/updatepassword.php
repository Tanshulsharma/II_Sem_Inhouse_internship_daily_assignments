<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';
include 'header.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
        $message = "Please fill all password fields.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "New passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($currentPassword, $user['password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->bind_param("si", $hashedPassword, $_SESSION['user_id']);

            if ($updateStmt->execute()) {
                $message = "Password updated successfully.";
            } else {
                $message = "Password update failed.";
            }
        } else {
            $message = "Current password is incorrect.";
        }
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h2 class="mb-3">Update Password</h2>
            <?php if ($message !== ''): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php' ?>