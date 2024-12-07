<?php
// include "header.php";

if ($_SESSION["user_role"] == '0') {
    header("Location: {$hostname}/admin/post.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | All Users</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div id="admin-content" class="my-4">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h1 class="admin-heading">All Users</h1>
            </div>
            <div class="col-md-2 text-right">
                <a class="btn btn-success btn-sm" href="add-user.php"><i class="fas fa-user-plus"></i> Add User</a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <?php
                include "config.php"; // database configuration
                /* Pagination variables */
                $limit = 3;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                /* Prepared statement to fetch user data */
                $stmt = $conn->prepare("SELECT * FROM user ORDER BY user_id DESC LIMIT ?, ?");
                $stmt->bind_param("ii", $offset, $limit); // Bind offset and limit
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                ?>
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>S.No.</th>
                                <th>Full Name</th>
                                <th>User Name</th>
                                <th>Role</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $serial = $offset + 1;
                            while ($row = $result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $serial; ?></td>
                                    <td><?php echo htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo ($row['role'] == 1) ? "Admin" : "Normal"; ?></td>
                                    <td class="text-center">
                                        <a href="update-user.php?id=<?php echo $row["user_id"]; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="delete-user.php?id=<?php echo $row["user_id"]; ?>" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                $serial++;
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                } else {
                    echo "<div class='alert alert-warning text-center'>No Results Found.</div>";
                }

                // Pagination logic using prepared statements
                $stmt1 = $conn->prepare("SELECT COUNT(*) as total_records FROM user");
                $stmt1->execute();
                $result1 = $stmt1->get_result();
                $row1 = $result1->fetch_assoc();

                if ($row1['total_records'] > 0) {
                    $total_records = $row1['total_records'];
                    $total_page = ceil($total_records / $limit);

                    echo '<nav><ul class="pagination justify-content-center">';
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="users.php?page=' . ($page - 1) . '">Prev</a></li>';
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        $active = ($i == $page) ? "active" : "";
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="users.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                    if ($total_page > $page) {
                        echo '<li class="page-item"><a class="page-link" href="users.php?page=' . ($page + 1) . '">Next</a></li>';
                    }
                    echo '</ul></nav>';
                }

                // Close prepared statements
                $stmt->close();
                $stmt1->close();
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap and JS CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
