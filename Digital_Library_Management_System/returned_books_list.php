<?php
include 'db.php';

// 👮‍♂️ Optional access check (admin or librarian)
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'librarian'])) {
    echo "<div class='access-denied'>Access Denied.</div>";
    exit;
}

// 📋 Fetch only returned books
$query = "SELECT borrowings.id, students.name AS student_name, books.title AS book_title, borrowings.borrow_date, borrowings.return_date
          FROM borrowings
          JOIN students ON borrowings.student_id = students.id
          JOIN books ON borrowings.book_id = books.id
          WHERE borrowings.status = 'returned'
          ORDER BY borrowings.return_date DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Returned Books</title>
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

        .no-records {
            text-align: center;
            padding: 20px;
            font-weight: bold;
            color: #6b7280;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📖 Returned Books List</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Book Title</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['book_title']) ?></td>
                        <td><?= htmlspecialchars($row['borrow_date']) ?></td>
                        <td><?= htmlspecialchars($row['return_date']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-records">No books have been returned yet.</div>
    <?php endif; ?>
</div>

</body>
</html>
