<?php
session_start();
$conn = new mysqli("localhost", "root", "", "blog");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
echo "Connected successfully!<br>"; // Debug

if (isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];
echo "User ID: " . $user_id . "<br>"; // Debug

// Create: Handle new post submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"], $_POST["content"])) {
    $title = $conn->real_escape_string($_POST["title"]);
    $content = $conn->real_escape_string($_POST["content"]);
    $sql = "INSERT INTO posts (title, content, user_id) VALUES ('$title', '$content', '$user_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Post added successfully!<br>"; // Debug
    } else {
        echo "Error adding post: " . $conn->error . "<br>"; // Debug
    }
}

// Update: Handle post editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_id"])) {
    $edit_id = (int)$_POST["edit_id"];
    $title = $conn->real_escape_string($_POST["title"]);
    $content = $conn->real_escape_string($_POST["content"]);
    $check_sql = "SELECT user_id FROM posts WHERE id = $edit_id";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        $post_user_id = $check_result->fetch_assoc()["user_id"];
        if ($post_user_id == $user_id) {
            $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$edit_id";
            $conn->query($sql);
            header("Location: index.php");
        } else {
            echo "<p>You can only edit your own posts.</p>";
        }
    }
}

// Delete: Handle post deletion
if (isset($_GET["delete"]) && is_numeric($_GET["delete"])) {
    $delete_id = (int)$_GET["delete"];
    $check_sql = "SELECT user_id FROM posts WHERE id = $delete_id";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        $post_user_id = $check_result->fetch_assoc()["user_id"];
        if ($post_user_id == $user_id) {
            $conn->query("DELETE FROM posts WHERE id = $delete_id");
            header("Location: index.php");
        } else {
            echo "<p>You can only delete your own posts.</p>";
        }
    }
}

// Read: Fetch the logged-in user's posts
$user_posts_result = $conn->query("SELECT * FROM posts WHERE user_id = '$user_id'");
echo "User posts count: " . $user_posts_result->num_rows . "<br>"; // Debug

// Fetch all public posts
$public_posts_result = $conn->query("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id");
echo "Public posts count: " . $public_posts_result->num_rows . "<br>"; // Debug
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; display: flex; gap: 20px; }
        .container { display: flex; width: 100%; }
        .left-section, .right-section { flex: 1; }
        .left-section { min-width: 0; }
        label { display: block; margin: 10px 0; }
        input, textarea, button { padding: 5px; }
        .post { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
        .public-post { border: 1px solid #ddd; padding: 10px; margin: 10px 0; background: #f9f9f9; }
        .public-post a { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            <p><a href="?logout=1">Logout</a></p>

            <h2>Add New Post</h2>
            <form method="post">
                <label>Title: <input type="text" name="title" required></label><br>
                <label>Content: <textarea name="content" required></textarea></label><br>
                <button type="submit">Add Post</button> <!-- Explicitly named button -->
            </form>
            <?php echo "Form loaded successfully!<br>"; // Debug to confirm form presence ?>

            <h2>Your Posts</h2>
            <?php
            if ($user_posts_result->num_rows > 0) {
                while ($row = $user_posts_result->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
                    echo "<p>" . htmlspecialchars($row["content"]) . "</p>";
                    echo "<p>Created: " . $row["created_at"] . "</p>";
                    echo "<a href='?edit=" . $row["id"] . "'>Edit</a> | ";
                    echo "<a href='?delete=" . $row["id"] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>";
                    echo "</div>";

                    if (isset($_GET["edit"]) && $_GET["edit"] == $row["id"]) {
                        echo "<form method='post' style='margin-top: 10px;'>
                            <input type='hidden' name='edit_id' value='" . $row["id"] . "'>
                            <label>Title: <input type='text' name='title' value='" . htmlspecialchars($row["title"]) . "' required></label><br>
                            <label>Content: <textarea name='content' required>" . htmlspecialchars($row["content"]) . "</textarea></label><br>
                            <button type='submit'>Update</button>
                        </form>";
                    }
                }
            } else {
                echo "<p>No posts yet.</p>";
            }
            ?>
        </div>

        <div class="right-section">
            <h2>Public Posts</h2>
            <?php
            if ($public_posts_result->num_rows > 0) {
                while ($row = $public_posts_result->fetch_assoc()) {
                    echo "<div class='public-post'>";
                    echo "<h3>" . htmlspecialchars($row["title"]) . " (by " . htmlspecialchars($row["username"]) . ")</h3>";
                    echo "<p>" . htmlspecialchars($row["content"]) . "</p>";
                    echo "<p>Created: " . $row["created_at"] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No public posts yet.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>