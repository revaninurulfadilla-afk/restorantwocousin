<?php
// Check if setup has already been completed
if (file_exists('setup_completed.flag')) {
    echo "Setup has already been completed. The SQL setup won't run again.";
} else {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');

    // Create Connection
    $link = new mysqli(DB_HOST, DB_USER, DB_PASS);

    // Check Connection
    if ($link->connect_error) {
        die('Connection Failed: ' . $link->connect_error);
    }

    // Create database
    $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS restoran";
    if ($link->query($sqlCreateDB) === TRUE) {
        echo "Database 'restoran' created successfully.<br>";
    } else {
        echo "Error creating database: " . $link->error . "<br>";
    }

    // Switch database
    $link->select_db('restoran');

    // Execute SQL statements from file
    function executeSQLFromFile($filename, $link) {
        $sql = file_get_contents($filename);

        if ($link->multi_query($sql)) {
            do {
                // flush results biar multi_query aman
                if ($result = $link->store_result()) {
                    $result->free();
                }
            } while ($link->more_results() && $link->next_result());

            echo "SQL statements executed successfully.<br>";
            file_put_contents('setup_completed.flag', 'Setup completed successfully.');
        } else {
            echo "Error executing SQL statements: " . $link->error;
        }
    }

    executeSQLFromFile('restaurantdb.txt', $link);

    $link->close();
}
?>

<a href="customerSide/home/home.php">Home</a>
