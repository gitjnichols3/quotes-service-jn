<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB * connect
$database = new Database();
$db = $database->connect();

// Check if database is connected
if ($db) {
    echo "Database connected!<br>";
} else {
    echo "Failed to connect to the database.<br>";
    exit;
}

// Instantiate the Quote object
$quote = new Quote($db);

// Get ID from query parameter
$quote->id = isset($_GET['id']) ? $_GET['id'] : die('ID not provided in the query string.');

// Prepare and execute query
$stmt = $db->prepare("SELECT id, quote, author, category FROM quotes WHERE id = :id");
$stmt->bindParam(':id', $quote->id);
if ($stmt->execute()) {
    echo "Query executed successfully.<br>";
} else {
    echo "Query execution failed.<br>";
    exit;
}

// Get the number of rows returned
$num = $stmt->rowCount();
echo "Number of rows found: $num<br>";

// Fetch the single record
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if a row was found
if ($row) {
    echo "Quote found!<br>";
    echo json_encode($row);
} else {
    echo "No matching records found.<br>";
}


 

/*     // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB * connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $quote = new Quote($db);

    // Get ID
    $quote->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get post
    $result = $quote->read_single();

    if ($result){
        // Get row count
        $num = $result->rowCount();

        // Check if any posts
        if($num > 0){

            //Create array
            $quote_arr = array(
                'id' => $quote->id,
                'quote' => $quote->quote,
            // 'author_id' => $quote->author_id,
                'author' => $quote->author,
            // 'category_id' => $quote->category_id,
                'category' => $quote->category
            );

            //Make JSON
            print_r(json_encode($quote_arr));
        
        } else {
            // No quotes
            echo json_encode(
                array('message' => 'No Quotes Found')
            );

        }
    } */

