<?php
session_start();
include '../includes/db.php';

$categories = [];
$result = $mysqli->query("SELECT id, name FROM categories");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f2f4f8;
        }

        header {
            background-color: #6c757d;
        }

        .sidebar {
            background-color: #ffffff;
            padding: 15px;
            border-right: 1px solid #dee2e6;
            height: 100vh;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <header class="text-white text-center p-4">
        <h1>User Dashboard</h1>
    </header>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">Shopping Cart User</a>
            <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
        </div>
    </nav>

    <div class="container mt-5 d-flex">
        <div class="sidebar">
            <h5>Categories</h5>
            <ul class="list-group">
            <a href="#" class="category-link" data-id="<?php echo ''; ?>">
                            All Products
                        </a>
                <?php foreach ($categories as $category): ?>
                    <li class="list-group-item">
                        <a href="#" class="category-link" data-id="<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="content flex-fill">
            <h2>All Products</h2>
            <div class="row product-list"></div>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            loadProducts('', 1);

            $('.category-link').click(function(e) {
                e.preventDefault();
                var categoryId = $(this).data('id');
                loadProducts(categoryId, 1);
            });

            function loadProducts(categoryId, page) {
                $.ajax({
                    url: 'fetch_products.php',
                    type: 'GET',
                    data: { category_id: categoryId, page: page },
                    success: function(response) {
                        displayProducts(response.products);
                        setupPagination(response.total_pages, categoryId); 
                    },
                    error: function() {
                        alert('Error loading products. Please try again later.');
                    }
                });
            }

            function displayProducts(products) {
                var productList = $('.product-list');
                productList.empty();

                if (products.length === 0) {
                    productList.append('<p>No products found in this category.</p>');
                    return;
                }

                products.forEach(function(product) {
                    var card = `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="${product.image}" class="card-img-top" alt="${product.name}">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text">${product.description}</p>
                                    <p class="card-text"><strong>Price: Rs.${parseFloat(product.price).toFixed(2)}</strong></p>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="${product.id}">
                                    </form>
                                </div>
                            </div>
                        </div>`;
                    productList.append(card);
                });
            }

            function setupPagination(totalPages, categoryId) {
                var pagination = $('#pagination');
                pagination.empty();

                for (var i = 1; i <= totalPages; i++) {
                    var pageItem = `<li class="page-item"><a class="page-link" href="#" data-page="${i}" data-id="${categoryId}">${i}</a></li>`;
                    pagination.append(pageItem);
                }

                $('.page-link').click(function(e) {
                    e.preventDefault();
                    var page = $(this).data('page');
                    loadProducts(categoryId, page);
                });
            }
        });
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
