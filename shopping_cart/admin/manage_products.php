<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];

        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $stmt = $mysqli->prepare("INSERT INTO products (name, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $name, $description, $price, $category_id, $target_file);
        $stmt->execute();
    } elseif (isset($_POST['delete_product'])) {
        $id = $_POST['id'];
        $stmt = $mysqli->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['update_product'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];

        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        // Update query
        $stmt = $mysqli->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssdisi", $name, $description, $price, $category_id, $target_file, $id);
        $stmt->execute();
    }
}

$result = $mysqli->query("SELECT * FROM products");
$products = $result->fetch_all(MYSQLI_ASSOC);
$categories = $mysqli->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f2f4f8;
        }

        header {
            background-color: #6c757d;
        }


        .container {
            margin-top: 30px;
        }

        .product-list {
            margin-top: 20px;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <header class="text-white text-center p-4">
        <h1>Manage Products</h1>
    </header>

    <div class="container">
        <a href="index.php" class="btn btn-info mb-3">Back to Index</a>
        <form method="POST" enctype="multipart/form-data" class="mb-4">
            <div class="form-group">
                <input type="text" name="name" class="form-control" required placeholder="Product Name">
            </div>
            <div class="form-group">
                <textarea name="description" class="form-control" required placeholder="Product Description"></textarea>
            </div>
            <div class="form-group">
                <input type="number" step="0.01" name="price" class="form-control" required placeholder="Price">
            </div>
            <div class="form-group">
                <select name="category_id" class="form-control" required>
                    <option value="" disabled selected>Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-success btn-lg btn-block">Add Product</button>
        </form>

        <h2 class="mt-4">Existing Products</h2>
        <div class="row product-list">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="card-text"><strong>Price: Rs.<?php echo number_format($product['price'], 2); ?></strong></p>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updateModal<?php echo $product['id']; ?>">Update</button>
                                <button type="submit" name="delete_product" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Update Modal -->
                <div class="modal fade" id="updateModal<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" required placeholder="Product Name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="description" class="form-control" required placeholder="Product Description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" step="0.01" name="price" class="form-control" required placeholder="Price" value="<?php echo $product['price']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <select name="category_id" class="form-control" required>
                                            <option value="" disabled>Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Update Image (optional)</label>
                                        <input type="file" name="image" class="form-control" id="image">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>

    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>&copy; 2024 Shopping Cart Admin. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>