<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../core/initialize.php');
include_once('../core/video_comment.php');

//instantiate post

$video_comment = new VideoComment($db);
$video_comment->video_id = isset($_GET['id']) ? $_GET['id'] : die();

//blog post query
$result = $video_comment->read();
//get the row count
$num = $result->rowCount();


// if($num > 0){
    $comment_arr = array();
    $comment_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $comment_item = array(
            'id'  => $id,
            'text' => $body,
            'video_id' => $video_id,
            'user_id'=> $user_id,
            'fullName'=> $username,
            //'createdAt'=> date('D M d Y H:i:s O', strtotime($created_at)),
            'createdAt'=> strtotime($created_at),
            //'createdAt'=> "Tue Jun 08 2021 01:59:10 GMT+0530 (India Standard Time)",
            'authorUrl'=> '#',
            'avatarUrl'=> 'https://cdnb.artstation.com/p/users/avatars/000/126/159/large/582fd86d44a71299b5cc51fe9f580471.jpg?1447075438'
        );
        array_push($comment_arr['data'], $comment_item);
    }
    //convert to JSON and output
    echo json_encode($comment_arr);
// }else{
//     echo json_encode(array('message' => 'No videos found.'));
// }