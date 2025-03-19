<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB * connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $author = new Author($db);

    // Get ID
    $author->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get author
    $result = $author->read_single();

    // Get row count
    $num = $result->rowCount();

    // Check if any posts
    if($num > 0){
        //Author array
        $authors_arr = array();
        $authors_array['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $author_item = array(
                'id' => $id,
                'author' => $author,
            );

            // Push to "data"
            array_push($authors_array['data'], $author_item);
        }

        //Turn to JSON & output
        echo json_encode($authors_array['data'][0]);

    }else{
        // No quotes
        echo json_encode(
            array('message' => 'author_id Not Found')
        );

    }
