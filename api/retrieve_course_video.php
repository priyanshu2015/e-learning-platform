<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../core/initialize.php');
include_once('../core/course.php');
include_once('../core/course_video.php');
//instantiate course
$course = new Course($db);
$course->id = isset($_GET['id']) ? $_GET['id'] : die();
$course_video = new CourseVideo($db);
$course_video->course_id = $course->id;
$course_video->id = isset($_GET['video_id']) ? $_GET['video_id'] : 0;
$course->retrieve_video($course_video);

$course_arr = array(
    'id'  => $course_video->id,
    'title' => $course_video->title,
    'description' => html_entity_decode($course_video->description),
    'image' => "http://localhost/e-learning-platform" . $course_video->image,
    'course_id'=> $course_video->course_id,
    'url'=> $course_video->url,
    'created_at'=> $course_video->created_at

);

//make a json
echo json_encode($course_arr);  // or print_r(json_encode($post_arr));