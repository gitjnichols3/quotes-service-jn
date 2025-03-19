<?php

    // Headers
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
     // Get row count
    $num = $result->rowCount();

    // Check if any posts
    if($num > 0){
        //Quote array
        $quotes_arr = array();
        $quotes_array['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
               // 'author_id' => $author_id,
                'author' => $author,
              //  'category_id' => $category_id,
                'category' => $category
            );

            // Push to "data"
            array_push($quotes_array['data'], $quote_item);
        }
        $singleObject = $quotes_array[0];
        //Turn to JSON & output
        echo json_encode($singleObject);

    }else{
        // No quotes
        echo json_encode(
            array('message' => 'No Quotes Found')
        );

    }
    

    

