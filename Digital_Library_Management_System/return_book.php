<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow_id = $_POST['borrow_id'];

    $stmt = $conn->prepare("UPDATE borrowings SET status = 'returned', return_date = CURDATE() WHERE id = ?");
    $stmt->bind_param("i", $borrow_id);
    $stmt->execute();

    $success = "Book returned successfully!";
}

// Fetch borrowed books (not yet returned)
$query = "SELECT borrowings.id, students.name AS student_name, books.title AS book_title, borrowings.borrow_date
          FROM borrowings
          JOIN students ON borrowings.student_id = students.id
          JOIN books ON borrowings.book_id = books.id
          WHERE borrowings.status = 'borrowed'";
$result = $conn->query($query);
?>

<div style="max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <h2 style="text-align:center; font-size: 26px; margin-bottom: 20px;">Return Book</h2>

    <?php if (isset($success)) echo "<p style='color: green; font-weight: bold; text-align: center;'>$success</p>"; ?>

    <?php if ($result->num_rows > 0): ?>
        <form method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            <label for="borrow_id" style="font-weight: 600;">Select Borrowed Book:</label>
            <select name="borrow_id" required style="padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                <option value="">-- Select a Book to Return --</option>
                <?php while($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row['id']; ?>">
                        <?= htmlspecialchars($row['student_name']) ?> borrowed "<?= htmlspecialchars($row['book_title']) ?>" on <?= $row['borrow_date']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" style="padding: 12px; background-color: #38bdf8; border: none; color: white; border-radius: 8px; font-size: 16px; cursor: pointer;">
                Return Book
            </button>
        </form>
    <?php else: ?>
        <p style="text-align:center; color: #888;">No books are currently borrowed.</p>
    <?php endif; ?>
</div>
