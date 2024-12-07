<?php
include "config.php";
session_start();

// Redirect to admin dashboard if already logged in
if (isset($_SESSION["username"])) {
    header("Location:post.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Login</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .body-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 100px;
        }
        .heading {
            text-align: center;
            margin-bottom: 20px;
        }
        .alert {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div id="wrapper-admin" class="body-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <img class="logo" src="D:\xampp\htdocs\23mmci96\project\images\logo.png" alt="Logo">
                    <h3 class="heading">Admin Login</h3>


                    <!-- Form Start -->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                    </form>
                    <!-- /Form End -->


                    <?php
                    if (isset($_POST['login'])) {
                        if (empty($_POST['username']) || empty($_POST['password'])) {
                            echo '<div class="alert alert-danger">All fields must be entered.</div>';
                        } else {
                            // Secure prepared statement for login
                            $username = $_POST['username'];
                            $password = md5($_POST['password']); // Ensure consistent hashing

                            $stmt = $conn->prepare("SELECT user_id, username, role FROM user WHERE username = ? AND password = ?");
                            $stmt->bind_param("ss", $username, $password);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();

                                // Set session variables
                                $_SESSION["username"] = $row['username'];
                                $_SESSION["user_id"] = $row['user_id'];
                                $_SESSION["user_role"] = $row['role'];

                                // Redirect to admin dashboard
                                header("Location: {$hostname}/admin/post.php");
                                exit();
                            } else {
                                echo '<div class="alert alert-danger">Username and password do not match.</div>';
                            }

                            $stmt->close();
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
