<?php
include 'db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $book_id = $_POST['book_id'];
    $borrow_date = $_POST['borrow_date'];
    $return_date = $_POST['return_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO borrowings (student_id, book_id, borrow_date, return_date, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $student_id, $book_id, $borrow_date, $return_date, $status);

    if ($stmt->execute()) {
        $message = "<p class='success'>✅ Borrow record added!</p>";
    } else {
        $message = "<p class='error'>❌ Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch students and books for dropdowns
$students = $conn->query("SELECT id, name FROM students");
$books = $conn->query("SELECT id, title FROM books");
?>

<style>
  .center-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 85vh;
    background-color: #f0f2f5;
  }

  .form-card {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    width: 100%;
    max-width: 550px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: all 0.3s;
  }

  .form-card:hover {
    transform: scale(1.01);
    box-shadow: 0 8px 18px rgba(0,0,0,0.25);
  }

  .form-card h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #2c3e50;
  }

  label {
    font-weight: bold;
    display: block;
    margin-top: 15px;
    color: #444;
  }

  select, input[type="date"] {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: border 0.3s ease;
  }

  select:focus, input:focus {
    border-color: #2980b9;
    outline: none;
  }

  button {
    background-color: #2980b9;
    color: white;
    padding: 12px;
    margin-top: 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    transition: background-color 0.3s ease;
  }

  button:hover {
    background-color: #1c5980;
  }

  .success {
    text-align: center;
    color: green;
    font-weight: bold;
    margin-bottom: 15px;
  }

  .error {
    text-align: center;
    color: red;
    font-weight: bold;
    margin-bottom: 15px;
  }
</style>

<div class="center-container">
  <div class="form-card">
    <h2>📚 Borrow Book</h2>

    <?php if (!empty($message)) echo $message; ?>

    <form method="POST">
      <label>👨‍🎓 Select Student</label>
      <select name="student_id" required>
        <option value="">-- Select Student --</option>
        <?php while ($row = $students->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
      </select>

      <label>📘 Select Book</label>
      <select name="book_id" required>
        <option value="">-- Select Book --</option>
        <?php while ($row = $books->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></option>
        <?php endwhile; ?>
      </select>

      <label>📅 Borrow Date</label>
      <input type="date" name="borrow_date" value="<?php echo date('Y-m-d'); ?>" required>

      <label>📅 Return Date</label>
      <input type="date" name="return_date" required>

      <label>📌 Status</label>
      <select name="status" required>
        <option value="Borrowed">Borrowed</option>
        <option value="Returned">Returned</option>
      </select>

      <button type="submit">➕ Record Borrowing</button>
    </form>
  </div>
</div>
