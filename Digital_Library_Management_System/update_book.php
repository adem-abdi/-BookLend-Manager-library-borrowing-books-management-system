<?php
require_once 'db.php';

// 🔐 Role-based Access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div style='padding: 30px; text-align:center; color: red; font-weight: bold;'>Access Denied. Admin only.</div>";
    exit;
}

$feedback = "";

// ✅ Update Book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, isbn = ?, quantity = ? WHERE id = ?");
    $stmt->bind_param("sssii", $title, $author, $isbn, $quantity, $book_id);
    if ($stmt->execute()) {
        $feedback = "✅ Book updated successfully!";
    } else {
        $feedback = "❌ Error updating book.";
    }
}

// ✅ Delete Book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_book'])) {
    $book_id = $_POST['book_id'];
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    if ($stmt->execute()) {
        $feedback = "🗑️ Book deleted successfully!";
    } else {
        $feedback = "❌ Error deleting book.";
    }
}

// ✅ Fetch All Books
$books = $conn->query("SELECT * FROM books ORDER BY id DESC");
?>

<!-- UI SECTION -->
<div style="max-width: 1000px; margin: auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: sans-serif;">
    <h2 style="text-align:center; font-size: 26px; margin-bottom: 20px;">📚 Manage Books</h2>

    <?php if (!empty($feedback)): ?>
        <div style="padding: 12px; background-color: #e0f7fa; color: #00796b; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold;">
            <?= $feedback ?>
        </div>
    <?php endif; ?>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f1f5f9;">
                <th style="padding: 10px;">Title</th>
                <th style="padding: 10px;">Author</th>
                <th style="padding: 10px;">ISBN</th>
                <th style="padding: 10px;">Quantity</th>
                <th style="padding: 10px;" colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($book = $books->fetch_assoc()): ?>
            <tr style="border-top: 1px solid #e2e8f0;">
                <form method="POST">
                    <td style="padding: 10px;">
                        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #ccc;">
                    </td>
                    <td style="padding: 10px;">
                        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #ccc;">
                    </td>
                    <td style="padding: 10px;">
                        <input type="text" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>" required style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #ccc;">
                    </td>
                    <td style="padding: 10px;">
                        <input type="number" name="quantity" value="<?= htmlspecialchars($book['quantity']) ?>" min="0" required style="width: 80px; padding: 6px; border-radius: 6px; border: 1px solid #ccc;">
                    </td>
                    <td style="padding: 10px;">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <button type="submit" name="update_book" style="padding: 6px 12px; background-color: #38bdf8; color: white; border: none; border-radius: 6px;">Update</button>
                    </td>
                </form>
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');">
                    <td style="padding: 10px;">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <button type="submit" name="delete_book" style="padding: 6px 12px; background-color: #ef4444; color: white; border: none; border-radius: 6px;">Delete</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
