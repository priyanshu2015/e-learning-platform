<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../core/initialize.php');
include_once('../core/course.php');

//instantiate course
$course = new Course($db);


$course->id = isset($_GET['id']) ? $_GET['id'] : die();
$course->read_single();

$course_arr = array(
    'id'  => $course->id,
    'title' => $course->title,
    'description' => html_entity_decode($course->description),
    'category_id' => $course->category_id,
    'category_name' => $course->category_name,
    'language_medium_id' => $course->language_medium_id,
    'language_name' => $course->language_name,
    'image' => "http://localhost/e-learning-platform" . $course->image,
    'instructor_id'=> $course->instructor_id,
    'instructor_name'=> $course->instructor_name,
    'created_at'=> $course->created_at

);

//make a json
echo json_encode($course_arr);  // or print_r(json_encode($post_arr));