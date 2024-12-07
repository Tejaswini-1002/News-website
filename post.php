<?php include "header.php"; ?>
<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h1 class="admin-heading">All Posts</h1>
            </div>
            <div class="col-md-2">
                <a class="btn btn-primary" href="add-post.php">Add Post</a>
            </div>
            <div class="col-md-12">
                <?php
                include "config.php"; // database configuration

                /* Pagination Logic */
                $limit = 3;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                // Prepared statement for fetching posts
                if ($_SESSION["user_role"] == '1') {
                    $stmt = $conn->prepare(
                        "SELECT post.post_id, post.title, post.description, post.post_date, 
                        category.category_name, user.username, post.category 
                        FROM post
                        LEFT JOIN category ON post.category = category.category_id
                        LEFT JOIN user ON post.author = user.user_id
                        ORDER BY post.post_id DESC LIMIT ?, ?"
                    );
                    $stmt->bind_param("ii", $offset, $limit);
                } else {
                    $stmt = $conn->prepare(
                        "SELECT post.post_id, post.title, post.description, post.post_date, 
                        category.category_name, user.username, post.category 
                        FROM post
                        LEFT JOIN category ON post.category = category.category_id
                        LEFT JOIN user ON post.author = user.user_id
                        WHERE post.author = ? 
                        ORDER BY post.post_id DESC LIMIT ?, ?"
                    );
                    $stmt->bind_param("iii", $_SESSION['user_id'], $offset, $limit);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Author</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $serial = $offset + 1;
                            while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $serial; ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['post_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td>
                                        <a class="btn btn-warning btn-sm" href="update-post.php?id=<?php echo $row['post_id']; ?>">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-sm" href="delete-post.php?id=<?php echo $row['post_id']; ?>&catid=<?php echo $row['category']; ?>">
                                            <i class="fa fa-trash"></i> Delete
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
                    echo "<h3 class='text-center'>No Results Found.</h3>";
                }

                // Pagination Logic
                if ($_SESSION["user_role"] == '1') {
                    $stmt1 = $conn->prepare("SELECT COUNT(*) as total FROM post");
                } else {
                    $stmt1 = $conn->prepare("SELECT COUNT(*) as total FROM post WHERE author = ?");
                    $stmt1->bind_param("i", $_SESSION['user_id']);
                }

                $stmt1->execute();
                $result1 = $stmt1->get_result();
                $row1 = $result1->fetch_assoc();
                $total_records = $row1['total'];
                $total_page = ceil($total_records / $limit);

                if ($total_records > 0) {
                    echo '<ul class="pagination">';
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="post.php?page=' . ($page - 1) . '">Prev</a></li>';
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        $active = ($i == $page) ? "active" : "";
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="post.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                    if ($total_page > $page) {
                        echo '<li class="page-item"><a class="page-link" href="post.php?page=' . ($page + 1) . '">Next</a></li>';
                    }
                    echo '</ul>';
                }

                // Close prepared statements
                $stmt->close();
                $stmt1->close();
                ?>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
