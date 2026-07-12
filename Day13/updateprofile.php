<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';
include 'header.php';

$message = "";
$uploadDir = dirname(__DIR__) . '/uploads'; // /opt/lampp/htdocs/uploads
$uploadBaseUrl = '/uploads';

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
        $message = "Upload folder could not be created. Please run: mkdir -p /opt/lampp/htdocs/uploads && chmod -R 777 /opt/lampp/htdocs/uploads";
    } else {
        chmod($uploadDir, 0777);
    }
}

if ($message === '' && !is_writable($uploadDir)) {
    $message = "Upload folder is not writable. Please run: chmod -R 777 " . $uploadDir;
}

$stmt = $conn->prepare("SELECT full_name, profile_image, skills, role FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $role = trim($_POST['role'] ?? 'student');
    $profileImage = $user['profile_image'] ?? '';

    if (isset($_FILES['profile_image']) && is_array($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        if ($message === '') {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $fileType = $_FILES['profile_image']['type'] ?? '';

            if (!in_array($fileType, $allowedTypes, true)) {
                $message = "Only JPG, PNG, WEBP, and GIF images are allowed.";
            } else {
                $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                if ($ext === '') {
                    $ext = 'jpg';
                }

                $fileName = uniqid('profile_', true) . '.' . $ext;
                $target = $uploadDir . '/' . $fileName;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
                    $profileImage = $fileName;
                } else {
                    $message = "Image upload failed.";
                }
            }
        }
    }

    if ($message === '') {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, profile_image = ?, skills = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $fullName, $profileImage, $skills, $role, $_SESSION['user_id']);

        if ($stmt->execute()) {
            $_SESSION['full_name'] = $fullName;
            $_SESSION['profile_image'] = $profileImage;
            $_SESSION['role'] = $role;

            $message = "Profile updated successfully.";
            $user['full_name'] = $fullName;
            $user['profile_image'] = $profileImage;
            $user['skills'] = $skills;
            $user['role'] = $role;
        } else {
            $message = "Profile update failed: " . $stmt->error;
        }
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4">
            <h2 class="mb-3">Update Profile</h2>

            <?php if ($message !== ''): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <?php if (!empty($user['profile_image'])): ?>
                    <div class="mb-3">
                        <img src="<?= htmlspecialchars($uploadBaseUrl . '/' . $user['profile_image']) ?>" alt="Current Profile Image" class="img-thumbnail" style="max-height: 120px;">
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Skills</label>
                    <input type="text" name="skills" class="form-control" value="<?= htmlspecialchars($user['skills'] ?? '') ?>" placeholder="PHP, MySQL, Bootstrap">
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="student" <?= (($user['role'] ?? 'student') === 'student') ? 'selected' : '' ?>>Student</option>
                        <option value="teacher" <?= (($user['role'] ?? 'student') === 'teacher') ? 'selected' : '' ?>>Teacher</option>
                        <option value="admin" <?= (($user['role'] ?? 'student') === 'admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="super_admin" <?= (($user['role'] ?? 'student') === 'super_admin') ? 'selected' : '' ?>>Super Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>