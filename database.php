<?php
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'craftcove2');
}

// Create connection if it doesn't exist
if (!isset($conn)) {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        echo "Error: Unable to connect to database";
    }
}
