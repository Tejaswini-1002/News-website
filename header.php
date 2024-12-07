<?php
  include "config.php";
  session_start();

  // Redirect if not logged in
  if (!isset($_SESSION["username"])) {
    header("Location: index.php"); // Redirects to 'index.php'
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Custom CSS -->
    <style>
        .logo {
            height: 50px;
        }
        .admin-logout {
            font-size: 14px;
            color: #fff;
            background: #f44336;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .admin-logout:hover {
            background: #d32f2f;
            text-decoration: none;
        }
        .admin-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }
        .admin-menu li {
            margin-right: 15px;
        }
        .admin-menu a {
            text-decoration: none;
            font-size: 16px;
            color: #333;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .admin-menu a:hover {
            background: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header id="header-admin" class="bg-dark py-3">
        <div class="container">
            <div class="row align-items-center">
                <!-- LOGO -->
                <div class="col-md-2">
                    <a href="post.php">
                        <img class="logo" src="images/news.jpg" alt="Logo">
                    </a>
                </div>
                <!-- /LOGO -->

                <!-- Logout -->
                <div class="col-md-10 text-right">
                    <a href="logout.php" class="admin-logout">
                        Hello, <?php echo htmlspecialchars($_SESSION["username"]); ?> | Logout
                    </a>
                </div>
                <!-- /Logout -->
            </div>
        </div>
    </header>
    <!-- /HEADER -->

    <!-- Menu Bar -->
    <nav id="admin-menubar" class="bg-light py-2">
        <div class="container">
            <ul class="admin-menu">
                <li>
                    <a href="post.php">Post</a>
                </li>
                <?php
                // Show additional menu options if user is admin
                if ($_SESSION["user_role"] == '1') {
                    ?>
                    <li>
                        <a href="category.php">Category</a>
                    </li>
                    <li>
                        <a href="users.php">Users</a>
                    </li>
                    <li>
                        <a href="settings.php">Settings</a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </nav>
    <!-- /Menu Bar -->

    <!-- Add remaining content here -->

    <!-- Bootstrap and JS CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
