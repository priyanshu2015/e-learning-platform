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



// if($num > 0){
    // // Pagination
    // // find out total pages
    // $rowsperpage = 5;
    // $totalpages = ceil($num / $rowsperpage);
    // if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
    //     $currentpage = (int) $_GET['currentpage'];
    // } else {
    //     $currentpage = 1;  // default page number
    // }
    // // if current page is greater than total pages
    // if ($currentpage > $totalpages) {
    // // set current page to last page
    //             $currentpage = $totalpages;
    // }
    // // if current page is less than first page
    // if ($currentpage < 1) {
    //     // set current page to first page
    //                     $currentpage = 1;
    //                 }
    //     // the offset of the list, based on current page
    //                 $offset = ($currentpage - 1) * $rowsperpage;


    $course_arr = array();
    $course_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $course_item = array(
            'id'  => $id,
            'title' => $title,
            'description' => html_entity_decode($description),
            'category_id' => $category_id,
            'category_name' => $category_name,
            'language_medium_id' => $language_medium_id,
            'language_name' => $language_name,
            'image' => "http://localhost/e-learning-platform" . $image,
            'instructor_id'=> $instructor_id,
            'instructor_name'=> $instructor_name,
            'created_at'=> $created_at
            
        );
        array_push($course_arr['data'], $course_item);
    }
    //convert to JSON and output
    echo json_encode($course_arr);
// }else{
//     echo json_encode(array('message' => 'No posts found.'));
// }