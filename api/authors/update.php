<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $author = new Author($db);

    
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"), true);

    //Specify required columns
    $required_fields = ['id', 'author'];

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

    //set object values and run update function
    $author->id = $data['id'];
    $author->author = $data['author'];

    $author->update();

