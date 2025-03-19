<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB * connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote = new Quote($db);

    //Quote query
    $result = $quote->read();
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

        //Turn to JSON & output
        echo json_encode($quotes_array['data']);

    }else{
        // No quotes
        echo json_encode(
            array('message' => 'No Quotes Found')
        );

    }