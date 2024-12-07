<?php
// Include database connection
require_once "config.php";

// Function to add a user
function addUser($fname, $lname, $username, $password, $role) {
    global $conn;
    try {
        $query = "INSERT INTO user (first_name, last_name, username, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$fname, $lname, $username, $password, $role]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password'])); // Password hashed with MD5
    $role = $_POST['role'];

    // Check if username exists
    $query = "SELECT username FROM user WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$username]);

    $stmt->store_result(); // Required to use num_rows
    if ($stmt->num_rows > 0) {
    $error_message = "Username already exists.";
}
 else {
        $isAdded = addUser($fname, $lname, $username, $password, $role);
        $status = $isAdded ? "1" : "0";
        header("Location: {$_SERVER['PHP_SELF']}?status=$status");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Add User</title>
</head>
<body>
    <!-- <?php include_once "navbar.php"; ?> -->

    <div class="container my-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h1 class="text-center">Add New User</h1>
                        <?php
                        if (isset($_GET['status'])) {
                            $response = $_GET['status'];
                            echo $response ? "<p class='text-success text-center'>User added successfully.</p>" : "<p class='text-danger text-center'>Error adding user.</p>";
                        }
                        if (isset($error_message)) {
                            echo "<p class='text-danger text-center'>{$error_message}</p>";
                        }
                        ?>
                    </div>
                    <div class="card-body">
                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                            <div class="form-group mb-3">
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" id="fname" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" id="lname" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="role">User Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="0">Normal User</option>
                                    <option value="1">Admin</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
