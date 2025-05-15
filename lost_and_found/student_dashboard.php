<?php
session_start();
include 'db.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}

// Handle success message
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<p>Item submitted successfully!</p>";
}

?>

<h2>Student Dashboard</h2>
<a href="logout.php">Logout</a>

<!-- Options to either report or view lost items -->
<p><a href="student_dashboard.php?section=report">Report Lost Item</a> | <a href="student_dashboard.php?section=view">View Lost Items</a></p>

<?php
// Handle different sections based on the 'section' parameter in URL
if (isset($_GET['section'])) {
    $section = $_GET['section'];

    if ($section == 'report') {
        // Show the form to report lost item
        ?>
        <h3>Turn Over a Lost Item</h3>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="item_name" placeholder="Item Name" required><br>
            <textarea name="description" placeholder="Description" required></textarea><br>

            <!-- Image input field -->
            <input type="file" name="photo" accept="image/*" id="photo" onchange="previewImage(event)" required><br>

            <!-- Image Preview -->
            <img id="imagePreview" src="#" alt="Image Preview" style="display:none; max-width: 300px; margin-top: 10px;"/>

            <button type="submit" name="submit">Submit</button>
        </form>

        <script>
            // Function to preview image before upload
            function previewImage(event) {
                const image = document.getElementById("imagePreview");
                image.style.display = "block";  // Make the image preview visible
                image.src = URL.createObjectURL(event.target.files[0]);  // Set the source of the preview image
            }
        </script>
        <?php
    } elseif ($section == 'view') {
        // Show the list of lost items
        echo "<h3>Lost Items</h3>";

        // Fetch lost items from the correct table (lost_items)
        $sql = "SELECT * FROM lost_items ORDER BY timestamp DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<img src='" . $row['photo_path'] . "' width='100'><br>";
                echo "<strong>" . htmlspecialchars($row['item_name']) . "</strong><br>";
                echo "Status: " . ($row['is_claimed'] ? "Claimed" : "Unclaimed");
                echo "</div><hr>";
            }
        } else {
            echo "No lost items found.";
        }
    }
} else {
    echo "<p>Welcome to your dashboard! Please choose an option above.</p>";
}

$conn->close();
?>
