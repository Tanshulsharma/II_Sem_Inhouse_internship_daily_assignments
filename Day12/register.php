<?php
session_start();
require_once 'db_connect.php';
include 'header.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = trim($_POST['user_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    $fullName = trim($_POST['full_name'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $role = trim($_POST['role'] ?? 'student');

    if ($userName === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $message = "Please fill all required fields.";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE user_name = ? OR email = ?");
        $check->bind_param("ss", $userName, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Username or email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (user_name, email, password, full_name, skills, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $userName, $email, $hashedPassword, $fullName, $skills, $role);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $message = "Registration failed.";
            }
        }
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4">
            <h2 class="mb-3">Register</h2>
            <?php if ($message !== ''): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="user_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Skills</label>
                    <input type="text" name="skills" class="form-control" placeholder="PHP, MySQL, Bootstrap">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <p class="mt-3 mb-0">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>