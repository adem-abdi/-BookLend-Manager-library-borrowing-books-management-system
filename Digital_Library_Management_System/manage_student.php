<?php
require_once 'db.php';

// 🔐 Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='access-denied'>Access Denied. Admin only.</div>";
    exit;
}

$feedback = "";

// ✅ Update Student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_student'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $class = $_POST['class'];

    $stmt = $conn->prepare("UPDATE students SET name = ?, gender = ?, email = ?, class = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $gender, $email, $class, $student_id);
    $feedback = $stmt->execute() ? "✅ Student updated successfully!" : "❌ Error updating student.";
}

// ✅ Delete Student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student'])) {
    $student_id = $_POST['student_id'];
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $feedback = $stmt->execute() ? "🗑️ Student deleted successfully!" : "❌ Error deleting student.";
}

// ✅ Fetch All Students
$students = $conn->query("SELECT * FROM students ORDER BY id DESC");
?>

<style>
    body {
        font-family: sans-serif;
        background-color: #f8fafc;
    }

    .access-denied {
        padding: 30px;
        text-align: center;
        color: red;
        font-weight: bold;
    }

    .container {
        max-width: 1000px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        font-size: 26px;
        margin-bottom: 20px;
    }

    .feedback {
        padding: 12px;
        background-color: #e0f7fa;
        color: #00796b;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead tr {
        background-color: #f1f5f9;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }

    tbody tr {
        border-top: 1px solid #e2e8f0;
    }

    input[type="text"],
    input[type="email"],
    select {
        width: 100%;
        padding: 6px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    button {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        color: white;
        cursor: pointer;
    }

    .btn-update {
        background-color:rgb(185, 249, 7);
    }

    .btn-delete {
        background-color: #ef4444;
    }

    form {
        display: inline;
    }
</style>

<div class="container">
    <h2>🧑‍🎓 Manage Students</h2>

    <?php if (!empty($feedback)): ?>
        <div class="feedback"><?= $feedback ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Class</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($student = $students->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td>
                        <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
                    </td>
                    <td>
                        <select name="gender">
                            <option value="Male" <?= $student['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </td>
                    <td>
                        <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
                    </td>
                    <td>
                        <input type="text" name="class" value="<?= htmlspecialchars($student['class']) ?>" required>
                    </td>
                    <td>
                        <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                        <button type="submit" name="update_student" class="btn-update">Update</button>
                    </td>
                </form>
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                    <td>
                        <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                        <button type="submit" name="delete_student" class="btn-delete">Delete</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
