<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote = new Quote($db);


    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"), true);
    
    //Specify required columns
    $required_fields = ['id', 'quote', 'author_id', 'category_id'];

    //Check for missing data
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        echo json_encode([
            "message" => "Missing Required Parameters",
        ]);
        exit;
    }

    //set object values
    $quote->id = $data['id'];
    $quote->quote = $data['quote'];
    $quote->author_id = $data['author_id'];
    $quote->category_id = $data['category_id'];

    
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

    //Run update method
    $quote->update();