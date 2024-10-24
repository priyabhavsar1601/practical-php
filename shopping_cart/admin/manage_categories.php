<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_category'])) {
        $name = $_POST['name'];
        $stmt = $mysqli->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    } elseif (isset($_POST['delete_category'])) {
        $id = $_POST['id'];
        $stmt = $mysqli->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['update_category'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $stmt = $mysqli->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
    }
}

// Fetching categories
$result = $mysqli->query("SELECT * FROM categories");
$categories = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #6c757d;
        }
        footer {
            background-color: #343a40;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .list-group-item {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>

<header class="text-white text-center p-4">
    <h1>Manage Categories</h1>
</header>

<div class="container mt-5">
    <a href="index.php" class="btn btn-info mb-3">Back to Index</a>
    
    <form method="POST" class="mb-3">
        <div class="form-group">
            <input type="text" name="name" class="form-control" required placeholder="Category Name">
        </div>
        <button type="submit" name="add_category" class="btn btn-success btn-lg btn-block">Add Category</button>
    </form>

    <h2 class="mt-4">Existing Categories</h2>
    <ul class="list-group">
        <?php foreach ($categories as $category): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo htmlspecialchars($category['name']); ?>
                <form method="POST" class="mb-0">
                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updateModal<?php echo $category['id']; ?>">Update</button>
                    <button type="submit" name="delete_category" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </li>

            <!-- Update Modal -->
            <div class="modal fade" id="updateModal<?php echo $category['id']; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateModalLabel">Update Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" required placeholder="New Category Name" value="<?php echo htmlspecialchars($category['name']); ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="update_category" class="btn btn-primary">Update Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
