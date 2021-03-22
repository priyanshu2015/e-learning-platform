<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//initializing our api
include_once('../core/initialize.php');

//instantiate user
$user = new user($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->name = $data->name;
$user->email = $data->email;
$user->phone = $data->phone;
$user->password = $data->password;


// create the user
if (
    !empty($user->name) &&
    !empty($user->email) &&
    !empty($user->password)
) {
    try {
        if ($user->create()) {
            // set response code
            http_response_code(200);

            //display message
            echo json_encode(
                array('message' => 'User created successfully.')
            );
        } else {
            // set response code
            http_response_code(400);
            echo json_encode(
                array('message' => 'Unable to create user.')
            );
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(array("message" => $e->getMessage()));
    }
} else {
    // set response code
    http_response_code(400);

    // display message: unable to create user
    echo json_encode(array("message" => "Empty Fields."));
}
