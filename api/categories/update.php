<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate category object
    $category = new Category($db);


    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"), true);


    //Specify required columns
    $required_fields = ['category'];

    $missing_fields = [];

    //Check for missing data
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

    $category->category = $data['category'];


    $category->update();


/*     // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set ID to update
    $category->id = $data->id;
    $category->category = $data->category;

    $category->update(); */
/*
    // update category
    if($category->update()) {
        echo json_encode(
            array('message' => 'Category Updated')
        );
    } else {
        echo json_encode(
            array('message' => 'Category Not Updated')
        );
    }
*/
