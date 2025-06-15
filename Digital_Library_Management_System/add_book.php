<!-- forms/add_book.php -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $author, $isbn, $quantity);

    if ($stmt->execute()) {
        $message = "<p class='success' >✅ Book added successfully!</p>";
    } else {
        $message = "<p class='error'>❌ Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<style>
  .center-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 70vh;
    background-color: #f3f4f6;
  }

  .form-card {
    background-color: white;
    padding: 30px;
    border-radius: 15px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: 0.3s ease;
  }

  .form-card:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
    transform: scale(1.01);
  }

  .form-card h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #1a73e8;
  }

  form label {
    display: block;
    font-weight: bold;
    margin-top: 15px;
    color: #333;
  }

  form input[type="text"],
  form input[type="number"] {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-top: 5px;
    transition: border-color 0.3s;
  }

  form input:focus {
    border-color: #1a73e8;
    outline: none;
  }

  button {
    background-color: #1a73e8;
    color: white;
    padding: 10px 20px;
    margin-top: 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s ease;
    width: 100%;
    font-size: 16px;
  }

  button:hover {
    background-color: #1558b0;
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
    <h2>📚 Add New Book</h2>

    <?php if (!empty($message)) echo $message; ?>

    <form method="POST">
      <label>📖 Title</label>
      <input type="text" name="title" required>

      <label>✍️ Author</label>
      <input type="text" name="author" required>

      <label>🔢 ISBN</label>
      <input type="text" name="isbn" required>

      <label>📦 Quantity</label>
      <input type="number" name="quantity" required>

      <button type="submit">➕ Add Book</button>
    </form>
  </div>
</div>
