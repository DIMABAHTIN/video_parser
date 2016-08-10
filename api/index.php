<?php
/**
 * Created by PhpStorm.
 * User: espe
 * Date: 09.08.2016
 * Time: 0:11
 */

require 'config.php';
require 'functions.php';
require 'class/movie_class.php';

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['REQUEST_URI']));

$action = $request[2];
$data = $request[3];

if($action == 'video') {
    $url = $_GET['url'];
    $html_code = $_GET['html_code'];

    if($url != '') {
        $movie = new movie_class($url);
    } elseif($html_code != '') {
        $movie = new movie_class('', $html_code);
    } else {
        http_response_code(400);
        die();
    }

    // move data to array
    // prepare to send
    $send_data['title'] = $movie->get_title();

    if(  $send_data['title'] == '') {
        http_response_code(404);
        die();
    }

    if($html_code == '') {
        $send_data['html_code'] = $movie->get_html_code();
    } else {
        $send_data['html_code'] = $html_code;
    }
    if (IMAGE == 1) {
        $send_data['image'] = $movie->get_image();
    }
    if (DESCRIPTION == 1) {
        $send_data['description'] = $movie->get_description();
    }
    write_log(json_encode(print_r($send_data, true)));

    echo json_encode($send_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    http_response_code(201);
} else {
    http_response_code(405);
}