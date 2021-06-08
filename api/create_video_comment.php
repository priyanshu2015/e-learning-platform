<?php

//headers
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
// header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header("HTTP/1.1 200 OK");
die();
}


//initializing our api
include_once('../core/initialize.php');
include_once('../core/video_comment.php');

//instantiate post
$video_comment = new VideoComment($db);

$data = json_decode(file_get_contents("php://input"));
//get the post data
// $data = json_decode(file_get_contents("php://input"));
$body = $data->body;
$user_id = $data->user_id;
$video_id = $data->video_id;


$video_comment->body = $body;
$video_comment->user_id = $user_id;
$video_comment->video_id = $video_id;


//create post
if ($video_comment->create()) {
    // actually storing on server
    echo json_encode(
        array('message' => 'Comment Added')
    );
} else {
    echo json_encode(
        array('message' => 'Comment Not Added')
    );
}
