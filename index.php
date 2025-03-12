<!-- index.php - Homepage -->
<!DOCTYPE html>
<html>
<head>
    <title>Election Results</title>
</head>
<body>
    <h2>Select Polling Unit</h2>
    <form action="polling_unit_results.php" method="GET">
        <input type="text" name="polling_unit_id" placeholder="Enter Polling Unit ID">
        <input type="submit" value="View Results">
    </form>

    <h2>Select Local Government</h2>
    <form action="lga_results.php" method="GET">
        <input type="text" name="lga_id" placeholder="Enter LGA ID">
        <input type="submit" value="View LGA Results">
    </form>

    <h2>Store New Results</h2>
    <a href="store_results.php">Add Results</a>
</body>
</html>

<?php
// Get absolute path to the SQL file
$sql_file_path = __DIR__ . "/bincom_test.sql"; 

// Check if the file exists
if (!file_exists($sql_file_path)) {
    die("Error: The SQL file does not exist at path: " . $sql_file_path);
}

// Read the file content safely
$sql_content = file($sql_file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($sql_content === false || empty($sql_content)) {
    die("Error: Failed to read the SQL file or the file is empty.");
}

// Capture table structures
$table_structures = [];
$capture = false;
$current_table = [];

foreach ($sql_content as $line) {
    if (!isset($line) || trim($line) === "") continue; // Skip invalid lines
    
    $line = trim($line); // Now it's safe

    // Start capturing when we see a CREATE TABLE statement
    if (stripos($line, "CREATE TABLE") === 0) {
        $capture = true;
        $current_table = [$line];
    } elseif ($capture) {
        $current_table[] = $line;

        // Stop capturing at the end of the table definition
        if (substr($line, -2) === ");") {
            $table_structures[] = implode("\n", $current_table);
            $capture = false;
        }
    }
}

// Extract only table creation statements safely
$table_definitions = array_filter($sql_content, function ($line) {
    return isset($line) && trim($line) !== "" && stripos(trim($line), "CREATE TABLE") === 0;
});

// Filter for relevant tables
$relevant_tables = ["polling_unit", "announced_pu_results", "lga"];
$filtered_structures = array_filter($table_structures, function ($table) use ($relevant_tables) {
    foreach ($relevant_tables as $rt) {
        if (stripos($table, $rt) !== false) {
            return true;
        }
    }
    return false;
});

// Display extracted table creation statements (First 10)
echo "<h3>Table Definitions:</h3>";
foreach (array_slice($table_definitions, 0, 10) as $table) {
    echo "<pre>" . htmlspecialchars($table) . "</pre><br>";
}

// Display filtered full table structures
echo "<h3>Filtered Table Structures:</h3>";
foreach ($filtered_structures as $structure) {
    echo "<pre>" . htmlspecialchars($structure) . "</pre><br>";
}
?>









