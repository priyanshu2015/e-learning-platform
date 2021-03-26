<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../core/initialize.php');
include_once('../core/course.php');

//instantiate post

$course = new Course($db);

//blog post query
$result = $course->read();
//get the row count
$num = $result->rowCount();

if($num > 0){
    $course_arr = array();
    $course_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $post_item = array(
            'id'  => $id,
            'title' => $title,
            'description' => html_entity_decode($description),
            'category_id' => $category_id,
            'category_name' => $category_name,
            'language_medium_id' => $language_medium_id,
            'language_name' => $language_name,
            'image' => $image,
            'instructor_id'=> $instructor_id,
            'instructor_name'=> $instructor_name,
            'created_at'=> $created_at
            
        );
        array_push($post_arr['data'], $post_item);
    }
    //convert to JSON and output
    echo json_encode($post_arr);
}else{
    echo json_encode(array('message' => 'No posts found.'));
}