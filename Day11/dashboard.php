<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';
include 'header.php';

$stmt = $conn->prepare("SELECT user_name, email, full_name, profile_image, skills, role FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Dashboard</h2>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>

            <?php if (!empty($user['profile_image'])): ?>
                <img src="uploads/<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile" class="img-thumbnail mb-3" style="max-width: 180px;">
            <?php else: ?>
                <div class="alert alert-secondary">No profile image uploaded yet.</div>
            <?php endif; ?>

            <p><strong>Username:</strong> <?= htmlspecialchars($user['user_name'] ?? '') ?></p>
            <p><strong>Full Name:</strong> <?= htmlspecialchars($user['full_name'] ?? '') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '') ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $user['role'] ?? 'student'))) ?></p>
            <p><strong>Skills:</strong> <?= htmlspecialchars($user['skills'] ?? 'No skills added') ?></p>

            <div class="mt-4">
                <a href="updateProfile.php" class="btn btn-primary me-2">Update Profile</a>
                <a href="updatePassword.php" class="btn btn-outline-secondary">Update Password</a>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>