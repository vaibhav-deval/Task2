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

// Pagination and Search Parameters
$posts_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

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
            header("Location: index.php?page=$page" . ($search ? "&search=$search" : ""));
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
            header("Location: index.php?page=$page" . ($search ? "&search=$search" : ""));
        } else {
            echo "<p>You can only delete your own posts.</p>";
        }
    }
}

// User Posts Query with Search and Pagination
$offset = ($page - 1) * $posts_per_page;
$user_posts_query = "SELECT * FROM posts WHERE user_id = '$user_id'" . ($search ? " AND title LIKE '%$search%'" : "") . " ORDER BY created_at DESC LIMIT $offset, $posts_per_page";
$user_posts_result = $conn->query($user_posts_query);
echo "User posts count: " . $user_posts_result->num_rows . "<br>"; // Debug

// Total user posts for pagination
$total_user_posts_query = "SELECT COUNT(*) as total FROM posts WHERE user_id = '$user_id'" . ($search ? " AND title LIKE '%$search%'" : "");
$total_user_posts_result = $conn->query($total_user_posts_query);
$total_user_posts = $total_user_posts_result->fetch_assoc()['total'];
$total_user_pages = ceil($total_user_posts / $posts_per_page);

// Public Posts Query with Search and Pagination
$public_posts_query = "SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id" . ($search ? " WHERE p.title LIKE '%$search%'" : "") . " ORDER BY p.created_at DESC LIMIT $offset, $posts_per_page";
$public_posts_result = $conn->query($public_posts_query);
echo "Public posts count: " . $public_posts_result->num_rows . "<br>"; // Debug

// Total public posts for pagination
$total_public_posts_query = "SELECT COUNT(*) as total FROM posts" . ($search ? " WHERE title LIKE '%$search%'" : "");
$total_public_posts_result = $conn->query($total_public_posts_query);
$total_public_posts = $total_public_posts_result->fetch_assoc()['total'];
$total_public_pages = ceil($total_public_posts / $posts_per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans bg-gray-100 p-6">
    <div class="container mx-auto flex flex-col md:flex-row gap-6">
        <div class="left-section w-full md:w-1/2">
            <h2 class="text-2xl font-bold text-gray-800">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            <p class="mt-2"><a href="?logout=1" class="text-blue-600 hover:underline">Logout</a></p>

            <h2 class="text-xl font-semibold mt-6 text-gray-700">Add New Post</h2>
            <form method="post" class="mt-4 space-y-4">
                <div>
                    <label for="title" class="block text-gray-700">Title:</label>
                    <input type="text" name="title" id="title" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="content" class="block text-gray-700">Content:</label>
                    <textarea name="content" id="content" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 h-24"></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700">Add Post</button>
            </form>
            <?php echo "Form loaded successfully!<br>"; // Debug ?>

            <h2 class="text-xl font-semibold mt-6 text-gray-700">Your Posts</h2>
            <div class="mt-4">
                <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search your posts..." class="w-full p-2 border border-gray-300 rounded-md mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <?php
            if ($user_posts_result->num_rows > 0) {
                while ($row = $user_posts_result->fetch_assoc()) {
                    echo "<div class='post mt-4 p-4 border border-gray-300 rounded-md bg-white'>";
                    echo "<h3 class='text-lg font-medium text-gray-800'>" . htmlspecialchars($row["title"]) . "</h3>";
                    echo "<p class='text-gray-600 mt-2'>" . htmlspecialchars($row["content"]) . "</p>";
                    echo "<p class='text-sm text-gray-500 mt-1'>Created: " . $row["created_at"] . "</p>";
                    echo "<div class='mt-2 space-x-4'>";
                    echo "<a href='?edit=" . $row["id"] . "&page=$page" . ($search ? "&search=$search" : "") . "' class='text-blue-600 hover:underline'>Edit</a> | ";
                    echo "<a href='?delete=" . $row["id"] . "&page=$page" . ($search ? "&search=$search" : "") . "' onclick='return confirm(\"Are you sure?\");' class='text-red-600 hover:underline'>Delete</a>";
                    echo "</div>";
                    echo "</div>";

                    if (isset($_GET["edit"]) && $_GET["edit"] == $row["id"]) {
                        echo "<form method='post' class='mt-4 space-y-4'>";
                        echo "<input type='hidden' name='edit_id' value='" . $row["id"] . "'>";
                        echo "<div>";
                        echo "<label for='edit_title' class='block text-gray-700'>Title:</label>";
                        echo "<input type='text' name='title' id='edit_title' value='" . htmlspecialchars($row["title"]) . "' required class='w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500'>";
                        echo "</div>";
                        echo "<div>";
                        echo "<label for='edit_content' class='block text-gray-700'>Content:</label>";
                        echo "<textarea name='content' id='edit_content' required class='w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 h-24'>" . htmlspecialchars($row["content"]) . "</textarea>";
                        echo "</div>";
                        echo "<button type='submit' class='w-full bg-green-600 text-white p-2 rounded-md hover:bg-green-700'>Update</button>";
                        echo "</form>";
                    }
                }
                // Pagination for User Posts
                echo "<div class='mt-4 flex justify-between'>";
                echo "<a href='?page=" . ($page > 1 ? $page - 1 : 1) . ($search ? "&search=$search" : "") . "' class='text-blue-600 hover:underline'>Previous</a>";
                echo "<span class='text-gray-600'>Page $page of $total_user_pages</span>";
                echo "<a href='?page=" . ($page < $total_user_pages ? $page + 1 : $total_user_pages) . ($search ? "&search=$search" : "") . "' class='text-blue-600 hover:underline'>Next</a>";
                echo "</div>";
            } else {
                echo "<p class='mt-4 text-gray-600'>No posts yet.</p>";
            }
            ?>
        </div>

        <div class="right-section w-full md:w-1/2">
            <h2 class="text-xl font-semibold text-gray-700">Public Posts</h2>
            <div class="mt-4">
                <input type="text" name="search" id="search_public" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search public posts..." class="w-full p-2 border border-gray-300 rounded-md mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <?php
            if ($public_posts_result->num_rows > 0) {
                while ($row = $public_posts_result->fetch_assoc()) {
                    echo "<div class='public-post mt-4 p-4 border border-gray-300 rounded-md bg-gray-50'>";
                    echo "<h3 class='text-lg font-medium text-gray-800'>" . htmlspecialchars($row["title"]) . " (by " . htmlspecialchars($row["username"]) . ")</h3>";
                    echo "<p class='text-gray-600 mt-2'>" . htmlspecialchars($row["content"]) . "</p>";
                    echo "<p class='text-sm text-gray-500 mt-1'>Created: " . $row["created_at"] . "</p>";
                    echo "</div>";
                }
                // Pagination for Public Posts
                echo "<div class='mt-4 flex justify-between'>";
                echo "<a href='?page=" . ($page > 1 ? $page - 1 : 1) . ($search ? "&search=$search" : "") . "' class='text-blue-600 hover:underline'>Previous</a>";
                echo "<span class='text-gray-600'>Page $page of $total_public_pages</span>";
                echo "<a href='?page=" . ($page < $total_public_pages ? $page + 1 : $total_public_pages) . ($search ? "&search=$search" : "") . "' class='text-blue-600 hover:underline'>Next</a>";
                echo "</div>";
            } else {
                echo "<p class='mt-4 text-gray-600'>No public posts yet.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>