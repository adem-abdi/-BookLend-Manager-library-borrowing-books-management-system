<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT username, role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $role);
$stmt->fetch();
$stmt->close();

$totalBooks = $conn->query("SELECT COUNT(*) FROM books")->fetch_row()[0];
$totalStudents = $conn->query("SELECT COUNT(*) FROM students")->fetch_row()[0];
$totalBorrowed = $conn->query("SELECT COUNT(*) FROM borrowings WHERE status = 'borrowed'")->fetch_row()[0];
$totalReturned = $conn->query("SELECT COUNT(*) FROM borrowings WHERE status = 'returned'")->fetch_row()[0];

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Library Management Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: sans-serif; }
    body { display: flex; height: 100vh; background-color: #f9fafb; }
    .sidebar {
      width: 250px;
      background: #fff;
      border-right: 1px solid #e5e7eb;
      display: flex;
      flex-direction: column;
      padding: 20px;
    }
    .sidebar .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 30px;
    }
    .sidebar .logo img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .sidebar .logo h2 {
      font-size: 20px;
      font-weight: bold;
      color: #1f2937;
    }
    .sidebar nav ul {
      list-style: none;
    }
    .sidebar nav ul li a {
      display: flex;
      padding: 10px;
      border-radius: 8px;
      color: #374151;
      text-decoration: none;
      transition: background 0.2s, transform 0.2s;
    }
    .sidebar nav ul li a:hover {
      background-color: #f1f5f9;
      transform: translateX(5px);
    }
    .sidebar nav ul li a.active {
      background-color: #e0f2fe;
      color: #0284c7;
      font-weight: bold;
      border-left: 4px solid #0284c7;
    }
    .sidebar .user {
      margin-top: auto;
      padding-top: 20px;
      border-top: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .sidebar .user div {
      width: 32px;
      height: 32px;
      background: #e5e7eb;
      border-radius: 50%;
    }
    .sidebar .user-info p {
      font-size: 14px;
      margin-bottom: 2px;
      font-weight: 600;
    }
    .sidebar .user-info small {
      font-size: 12px;
      color: #6b7280;
    }

    .main {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
    }
    .main h1 {
      font-size: 28px;
      font-weight: bold;
      color: #1f2937;
      margin-bottom: 20px;
    }
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }
    .card {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .card h2 {
      font-size: 16px;
      color: #374151;
      font-weight: 600;
    }
    .card p {
      font-size: 24px;
      font-weight: bold;
      margin-top: 10px;
    }
    .blue { color: #2563eb; }
    .green { color: #059669; }
    .yellow { color: #ca8a04; }
    .purple { color: #7c3aed; }
    .error { color: red; font-weight: bold; }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="logo.jpg" alt="Library Logo">
      <h2>Library System</h2>
    </div>
    <nav>
      <ul>
        <li><a href="dashboard.php" class="<?= $page == 'home' ? 'active' : ''; ?>">Dashboard</a></li>
        <li><a href="dashboard.php?page=add_book" class="<?= $page == 'add_book' ? 'active' : ''; ?>">Add Book</a></li>
        <li><a href="dashboard.php?page=add_student" class="<?= $page == 'add_student' ? 'active' : ''; ?>">Add Student</a></li>
        <li><a href="dashboard.php?page=borrowed_books" class="<?= $page == 'borrowed_books' ? 'active' : ''; ?>">Borrow Book</a></li>
        <li><a href="dashboard.php?page=return_book" class="<?= $page == 'return_book' ? 'active' : ''; ?>">Return Book</a></li>
        <?php if ($role === 'admin'): ?>
        <li><a href="dashboard.php?page=update_book" class="<?= $page == 'update_book' ? 'active' : ''; ?>">View/Update Book Info</a></li>
        <?php endif; ?>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
    <div class="user">
      <div></div>
      <div class="user-info">
        <p><?= htmlspecialchars($username); ?></p>
        <small><?= htmlspecialchars(ucfirst($role)); ?></small>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="content">
      <?php
      if ($page == 'home') {
          echo "
          <h1>Hi, " . htmlspecialchars($username) . "!</h1>
          <div class='cards'>
  <a href='dashboard.php?page=update_book' style='text-decoration:none;'>
    <div class='card'><h2>Total Books</h2><p class='blue'>{$totalBooks}</p></div>
  </a>
  <a href='dashboard.php?page=manage_student' style='text-decoration:none;'>
    <div class='card'><h2>Total Students</h2><p class='green'>{$totalStudents}</p></div>
  </a>
  <a href='dashboard.php?page=borrowed_books_list' style='text-decoration:none;'>
    <div class='card'><h2>Borrowed Books</h2><p class='yellow'>{$totalBorrowed}</p></div>
  </a>
  <a href='dashboard.php?page=returned_books_list' style='text-decoration:none;'>
    <div class='card'><h2>Returned Books</h2><p class='purple'>{$totalReturned}</p></div>
  </a>
</div>";
      } else {
          $file = "{$page}.php";
          if (file_exists($file)) {
              if ($page === 'update_book' && $role !== 'admin') {
                  echo "<p class='error'>Access denied. Admin only.</p>";
              } else {
                  include $file;
              }
          } else {
              echo "<p class='error'>Page not found.</p>";
          }
      }
      ?>
    </div>
  </div>

</body>
</html>
