<?php
include "config.php";

if (isset($_POST['submit'])) {
    // Get form data
    $post_title = $_POST['post_title'];
    $postdesc = $_POST['postdesc'];
    $category = $_POST['category'];

    // Image upload logic
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a valid image
    if (getimagesize($_FILES["fileToUpload"]["tmp_name"])) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // Insert post into database using prepared statement
            $sql = "INSERT INTO posts (post_title, postdesc, category, post_image) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $post_title, $postdesc, $category, $target_file);

            if ($stmt->execute()) {
                echo "New post added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}

$conn->close();
?>
