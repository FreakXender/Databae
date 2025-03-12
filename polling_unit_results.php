

<?php
// Include database connection
include 'db.php';

// Check if form is submitted
$polling_unit_id = "";
$results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['polling_unit_id'])) {
        $polling_unit_id = intval($_POST['polling_unit_id']);

        // Prepare SQL query
        $query = "SELECT party_abbreviation, party_score FROM announced_pu_results WHERE polling_unit_uniqueid = ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("i", $polling_unit_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch results
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }

            $stmt->close();
        } else {
            echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Please enter a Polling Unit ID.</p>";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polling Unit Results</title>
</head>
<body>
    <h2>View Election Results by Polling Unit ID</h2>
    
    <form method="POST">
        <label for="polling_unit_id">Enter Polling Unit ID:</label>
        <input type="number" name="polling_unit_id" required>
        <button type="submit">View Results</button>
    </form>

    <?php if (!empty($results)): ?>
        <h3>Results for Polling Unit ID: <?php echo htmlspecialchars($polling_unit_id); ?></h3>
        <table border="1">
            <tr>
                <th>Party</th>
                <th>Score</th>
            </tr>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['party_abbreviation']); ?></td>
                    <td><?php echo htmlspecialchars($row['party_score']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>No results found for Polling Unit ID: <?php echo htmlspecialchars($polling_unit_id); ?></p>
    <?php endif; ?>
</body>
</html>
