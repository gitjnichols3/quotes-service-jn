<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();



    try {
        // Get raw POST data
        $rawData = file_get_contents('php://input');
        
        // Decode JSON, return error if invalid
        $input = json_decode($rawData, true);
        if ($input === null) {
            throw new Exception('Invalid JSON data');
        }
    
        // Check if required fields exist
        if (empty($input['quote']) || empty($input['author_id']) || empty($input['category_id'])) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing required fields'
            ]);
            exit;
        }
    
        $quoteModel = new Quote();
        $newQuote = $quoteModel->create(
            $input['quote'],
            $input['author_id'],
            $input['category_id']
        );
    
        echo json_encode([
            'status' => 'success',
            'data' => $newQuote
        ]);
    
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }

/*
    // Instantiate quote object
    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    $quote->create();

    // Create quote
    if($quote->create()) {
        echo json_encode(
            array('message' => 'Quote Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Quote Not Created')
        );
    }
    
*/