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
include_once('../core/course.php');

//instantiate post
$course = new Course($db);

//get the post data
// $data = json_decode(file_get_contents("php://input"));
$title = $_POST['title'];
$description = $_POST['description'];
$instructor_id = $_POST['instructor_id'];
$category_id = $_POST['category_id'];
$language_medium_id = $_POST['language_medium_id'];

$tmp_file=$_FILES['image']['tmp_name'];

$img_name=$_FILES['image']['name'];

$img_filename=SITE_ROOT.'/images/course_images/img_'.time().'_'.$img_name;







$course->title = $title;
$course->description = $description;
$course->instructor_id = $instructor_id;
$course->category_id = $category_id;
$course->language_medium_id = $language_medium_id;
$course->image = $img_filename;

//create post
if ($course->create()) {
    // actually storing on server
    move_uploaded_file($_FILES["image"]["tmp_name"], $img_filename);

    echo json_encode(
        array('message' => 'Course Created')
    );
} else {
    echo json_encode(
        array('message' => 'Course Not Created')
    );
}
