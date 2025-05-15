<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle claim action
// Handle claim action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_claimed'])) {
    $item_id = $_POST['item_id'];
    $claimed_by = $_POST['claimed_by'];

    $stmt = $conn->prepare("UPDATE lost_items SET is_claimed = 1, claimed_by = ? WHERE id = ?");
    $stmt->bind_param("si", $claimed_by, $item_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
    exit();
}


// Fetch all lost items with student info
$sql = "SELECT lost_items.*, students.id AS student_id, students.last_name 
        FROM lost_items 
        LEFT JOIN students ON lost_items.student_id = students.id 
        ORDER BY lost_items.timestamp DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>

<h2>Admin Dashboard</h2>
<a href="logout.php">Logout</a>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formatted_time = date("Y-m-d H:i:s", strtotime($row['timestamp']));
        $submittedBy = $row['student_id'] ? htmlspecialchars($row['student_id']) . " (" . htmlspecialchars($row['last_name']) . ")" : "Unknown";

        echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
        echo "<img src='" . htmlspecialchars($row['photo_path']) . "' width='100'><br>";
        echo "<strong>" . htmlspecialchars($row['item_name']) . "</strong><br>";
        echo "Description: " . htmlspecialchars($row['description']) . "<br>";
        echo "Submitted by: " . $submittedBy . "<br>";
        echo "Submitted on: " . $formatted_time . "<br>";
        echo "Status: " . ($row['is_claimed'] ? "Claimed" : "Unclaimed") . "<br>";
        if ($row['is_claimed'] && !empty($row['claimed_by'])) {
    echo "Claimed By: " . htmlspecialchars($row['claimed_by']) . "<br>";
}

        if (!$row['is_claimed']) {
            echo "<form method='POST' class='mark-claimed-form' style='margin-top:5px;'>
        <input type='hidden' name='item_id' value='" . $row['id'] . "'>
        <input type='hidden' name='claimed_by' value=''>
        <button type='submit' name='mark_claimed'>Mark as Claimed</button>
      </form>";
        }

        echo "</div>";
    }
} else {
    echo "No items found.";
}

$conn->close();
?>

<script>
    console.log("Admin dashboard loaded");
</script>
<script src="js/claimHandler.js"></script>



</body>
</html>
