<!-- dashboard_card.php -->
<style>
  .dashboard-header {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 24px;
  }

  .card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
  }

  .card-box {
    background-color: white;
    padding: 16px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    text-decoration: none;
    transition: transform 0.2s, box-shadow 0.2s;
    display: block;
  }

  .card-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .card-box p.label {
    color: #374151;
    font-size: 16px;
    margin-bottom: 8px;
  }

  .card-box p.value {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
  }

  .text-blue { color: #2563eb; }
  .text-green { color: #059669; }
  .text-yellow { color: #ca8a04; }
  .text-purple { color: #7c3aed; }
</style>

<h1 class="dashboard-header">Hi, <?php echo htmlspecialchars($name); ?>!</h1>
<div class="card-grid">
    <a href="dashboard.php?page=update_book" class="card-box">
        <p class="label">Total Books</p>
        <p class="value text-blue">
            <?php
            include 'db.php';
            $book_count = $conn->query("SELECT COUNT(*) as total FROM books")->fetch_assoc();
            echo $book_count['total'];
            ?>
        </p>
    </a>
    <a href="dashboard.php?page=manage_student" class="card-box">
        <p class="label">Total Students</p>
        <p class="value text-green">
            <?php
            $student_count = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc();
            echo $student_count['total'];
            ?>
        </p>
    </a>
    <a href="dashboard.php?page=borrowed_books_list" class="card-box">
        <p class="label">Borrowed Books</p>
        <p class="value text-yellow">
            <?php
            $borrowed_count = $conn->query("SELECT COUNT(*) as total FROM borrowings WHERE status='borrowed'")->fetch_assoc();
            echo $borrowed_count['total'];
            ?>
        </p>
    </a>
    <a href="dashboard.php?page=returned_books_list" class="card-box">
        <p class="label">Returned Books</p>
        <p class="value text-purple">
            <?php
            $returned_count = $conn->query("SELECT COUNT(*) as total FROM borrowings WHERE status='returned'")->fetch_assoc();
            echo $returned_count['total'];
            ?>
        </p>
    </a>
</div>
