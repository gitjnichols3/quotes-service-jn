<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set ID to update
    $quote->id = $data->id;

    // **Check if the quote record exists before attempting to update**
    $checkQuery = 'SELECT id FROM quotes WHERE id = :id LIMIT 1';
    $stmt = $db->prepare($checkQuery);
    $stmt->bindParam(':id', $quote->id, PDO::PARAM_INT);
    $stmt->execute();

    // If the record does not exist, return a "Record not found" message
    if ($stmt->rowCount() == 0) {
        echo json_encode([
            'message' => 'No Quotes Found'
        ]);
        exit; // Stop execution as the record does not exist
    }

    // Delete quote
    if($quote->delete()) {
        echo json_encode(
            array(
            'id' => $quote->id   
            )
        );
    } else {
        //Not deleted
        echo json_encode(
            array('message' => 'Quote Not Deleted')
        );
    }
    
