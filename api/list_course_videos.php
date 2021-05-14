<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../core/initialize.php');
include_once('../core/course.php');
include_once('../core/course_video.php');
//instantiate post

$course = new Course($db);
$course->id = isset($_GET['id']) ? $_GET['id'] : die();
$course_video = new CourseVideo($db);
$course_video->course_id = $course->id;
//blog post query
$result = $course->list_videos($course_video);
//get the row count
$num = $result->rowCount();


// if($num > 0){
    $video_arr = array();
    $video_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $video_item = array(
            'id'  => $id,
            'title' => $title,
            'description' => html_entity_decode($description),
            'image' => "http://localhost/e-learning-platform" . $image,
            'url'=> $url,
            'course_id'=> $course_id,
            'created_at'=> $created_at
        );
        array_push($video_arr['data'], $video_item);
    }
    //convert to JSON and output
    echo json_encode($video_arr);
// }else{
//     echo json_encode(array('message' => 'No videos found.'));
// }