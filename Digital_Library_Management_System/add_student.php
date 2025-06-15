<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $registered_on = $_POST['registered_on'];

    $stmt = $conn->prepare("INSERT INTO students (name, gender, email, class, registered_on) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $gender, $email, $class, $registered_on);

    if ($stmt->execute()) {
        $message = "<p class='success'>✅ Student added successfully!</p>";
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
    min-height: 80vh;
    background-color: #f3f4f6;
  }
  

  .form-card {
    background-color: white;
    padding: 30px;
    border-radius: 15px;
    width: 100%;
    max-width: 550px;
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
  form input[type="email"],
  form input[type="date"] {
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

  .radio-group {
    display: flex;
    gap: 20px;
    margin-top: 10px;
  }

  .radio-option {
    position: relative;
    padding-left: 30px;
    cursor: pointer;
    font-size: 16px;
    user-select: none;
    color: #333;
  }

  .radio-option input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
  }

  .radio-checkmark {
    position: absolute;
    top: 2px;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #ddd;
    border-radius: 50%;
    transition: 0.3s ease;
  }

  .radio-option:hover .radio-checkmark {
    background-color: #ccc;
  }

  .radio-option input:checked ~ .radio-checkmark {
    background-color: #1a73e8;
  }

  .radio-checkmark:after {
    content: "";
    position: absolute;
    display: none;
  }

  .radio-option input:checked ~ .radio-checkmark:after {
    display: block;
  }

  .radio-option .radio-checkmark:after {
    top: 6px;
    left: 6px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
  }

  button {
    background-color: #1a73e8;
    color: white;
    padding: 10px 20px;
    margin-top: 25px;
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
    <h2>👨‍🎓 Add New Student</h2>

    <?php if (!empty($message)) echo $message; ?>

    <form method="POST">
      <label>🧾 Full Name</label>
      <input type="text" name="name" required>

      <label>⚥ Gender</label>
      <div class="radio-group">
        <label class="radio-option">Male
          <input type="radio" name="gender" value="Male" required>
          <span class="radio-checkmark"></span>
        </label>

        <label class="radio-option">Female
          <input type="radio" name="gender" value="Female" required>
          <span class="radio-checkmark"></span>
        </label>
      </div>

      <label>📧 Email</label>
      <input type="email" name="email" required>

      <label>🏫 Class</label>
      <input type="text" name="class" required>

      <label>📅 Registered On</label>
      <input type="date" name="registered_on" value="<?php echo date('Y-m-d'); ?>" required>

      <button type="submit">➕ Add Student</button>
    </form>
  </div>
</div>
