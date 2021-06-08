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
include_once('../core/course_video.php');

//instantiate post
$course_video = new CourseVideo($db);
//get the post data
// $data = json_decode(file_get_contents("php://input"));
$title = $_POST['title'];
$description = $_POST['description'];
$course_id = $_POST['course_id'];
$url = $_POST['url'];


$course_video->title = $title;
$course_video->description = $description;
$course_video->course_id = $course_id;
$course_video->url = $url;

//create post
if ($course_video->create()) {
    // actually storing on server

    echo json_encode(
        array('message' => 'Video Added')
    );
} else {
    echo json_encode(
        array('message' => 'Failed')
    );
}
