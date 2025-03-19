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
    

    

