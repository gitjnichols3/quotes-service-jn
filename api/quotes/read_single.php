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
    $quote->read_single();
    
            //Create array
            $quote_arr = array(
                'id' => $quote->id,
                'quote' => $quote->quote,
            // 'author_id' => $quote->author_id,
                'author' => $quote->author,
            // 'category_id' => $quote->category_id,
                'category' => $quote->category
            );

            if ($quote_arr->quote){
                //Make JSON
                print_r(json_encode($quote_arr));
            }else{
                $response = array("message" => "No Quotes Found");
                echo json_encode($response);
            }
    

