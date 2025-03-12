<!-- store_results.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Store Results</title>
</head>
<body>
    <h2>Enter New Election Results</h2>
    <form action="submit_results.php" method="POST">
        <input type="text" name="polling_unit_id" placeholder="Polling Unit ID" required><br>
        <input type="text" name="party_abbreviation" placeholder="Party Abbreviation" required><br>
        <input type="number" name="party_score" placeholder="Party Score" required><br>
        <input type="submit" value="Save Result">
    </form>
</body>
</html>

<!-- submit_results.php -->
<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $polling_unit_id = $_POST['polling_unit_id'];
    $party_abbreviation = $_POST['party_abbreviation'];
    $party_score = $_POST['party_score'];
    $sql = "INSERT INTO announced_pu_results (polling_unit_uniqueid, party_abbreviation, party_score, entered_by_user, date_entered, user_ip_address)
            VALUES (?, ?, ?, 'admin', NOW(), '127.0.0.1')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $polling_unit_id, $party_abbreviation, $party_score);
    if ($stmt->execute()) {
        echo "Result saved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
