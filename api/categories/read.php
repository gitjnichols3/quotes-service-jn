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

    //Category query
    $result = $category->read();
    // Get row count
    $num = $result->rowCount();

    // Check if any categories
    if($num > 0){
        //Category array
        $category_arr = array();
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
        echo json_encode($categories_array['data']);

    }else{
        // No Categories
        echo json_encode(
            array('message' => 'No Categories Found')
        );

    }