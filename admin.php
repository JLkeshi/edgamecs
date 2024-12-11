<?php
include 'database/db_connect.php';  // Include database connection

// Handle deletion of a leaderboard entry
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM leaderboard WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "Entry deleted successfully.";
    } else {
        echo "Error deleting entry: " . $conn->error;
    }
    header("Location: admin.php");
    exit;
}

// Fetch leaderboard entries
$query = "SELECT id, username, score, difficulty, subj, game_mode FROM leaderboard ORDER BY score DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="container-wrap">
        <section id="leaderboard">
            <nav class="ladder-nav">
                <div class="ladder-title">
                    <h1>Admin Dashboard</h1>
                </div>
                <div class="ladder-search">
                    <input type="text" id="search-leaderboard" class="live-search-box" placeholder="Search Player..." />
                </div>
            </nav>
            <table id="rankings" class="leaderboard-results" width="100%">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Score</th>
                        <th>Difficulty</th>
                        <th>Topics</th>
                        <th>Game Mode</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rank = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$rank}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['score']}/10</td>
                                <td>{$row['difficulty']}</td>
                                <td>{$row['subj']}</td>
                                <td>{$row['game_mode']}</td>
                                <td><a href='admin.php?delete_id={$row['id']}' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                              </tr>";
                        $rank++;
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <script src="BackEnd/admin.js"></script>
</body>
</html>

<?php
$conn->close();
?>
