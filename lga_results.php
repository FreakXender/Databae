<!-- lga_results.php -->
<?php
include 'db.php';
$lga_id = $_GET['lga_id'];
$sql = "SELECT party_abbreviation, SUM(party_score) AS total_score FROM announced_pu_results apr
        JOIN polling_unit pu ON apr.polling_unit_uniqueid = pu.uniqueid
        JOIN lga ON pu.lga_id = lga.lga_id
        WHERE lga.lga_id = ? GROUP BY apr.party_abbreviation";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lga_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    echo "Party: " . $row['party_abbreviation'] . " - Total Score: " . $row['total_score'] . "<br>";
}
?>
