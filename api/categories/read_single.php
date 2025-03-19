<?php
    // Headers
    header('Access-Control_Allow_Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB * connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate category object
    $category = new Category($db);

    // Get ID
    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get category
    $result = $category->read_single();

    // Get row count
    $num = $result->rowCount();

    // Check if any posts
    if($num > 0){
        //Category array
        $categories_arr = array();
        $categories_array['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $category_item = array(
                'id' => $id,
                'category' => $category,
            );

            // Push to "data"
            array_push($categories_array['data'], $category_item);
        }

        //Turn to JSON & output
        echo json_encode($categories_array['data'][0]);

    }else{
        // No quotes
        echo json_encode(
            array('message' => 'category_id Not Found')
        );

    }
