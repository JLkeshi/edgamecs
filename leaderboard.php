<?php
include 'database/db_connect.php';  // Include the database connection

// Insert score if POST data is set
if (isset($_POST['username']) && isset($_POST['score']) && isset($_POST['difficulty']) && isset($_POST['game_mode'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $score = $_POST['score'];
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);
    $subj = mysqli_real_escape_string($conn, $_POST['subj']);
    $game_mode = mysqli_real_escape_string($conn, $_POST['game_mode']);

    // Insert into the leaderboard table
    $query = "INSERT INTO leaderboard (username, score, difficulty, subj, game_mode) VALUES ('$username', $score, '$difficulty', '$subj', '$game_mode')";
    
    if (mysqli_query($conn, $query)) {
        echo "Score recorded successfully.";
    } else {
        echo "Error recording score: " . mysqli_error($conn);
    }
}

// Fetch all leaderboard entries, including game mode
$query = "SELECT username, score, difficulty, subj, game_mode FROM leaderboard ORDER BY score DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/leaderboard.css">
    <title>Leaderboard</title>
</head>
<body>

<div class="leaderboard-container">
    <h1>Leaderboard</h1>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Score</th>
                <th>Difficulty</th>
                <th>Topics</th>
                <th>Game Mode</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $rank = 1; // Initialize rank variable
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $rank; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo $row['score']; ?>/10</td>
                    <td><?php echo htmlspecialchars($row['difficulty']); ?></td>
                    <td><?php echo htmlspecialchars($row['subj']); ?></td>
                    <td><?php echo htmlspecialchars($row['game_mode']); ?></td>
                </tr>
                <?php 
                $rank++; // Increment rank after each row
            } 
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
