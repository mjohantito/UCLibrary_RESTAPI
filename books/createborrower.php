<?php

// error_reporting(0);

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Method: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');

include('function.php');


$requestMethod = $_SERVER["REQUEST_METHOD"];

if($requestMethod == "POST"){

    $inputData = json_decode(file_get_contents("php://input"),true);
    if(empty($inputData)){
        // echo $_POST['name'];
        $storeBorrower = storeBorrower($_POST);
    }else{
        $storeBorrower = storeBorrower($inputData);
        // echo $inputData['name'];
    }
    echo $storeBorrower;


}
else 
{

    /* print it here */
    $data = [
        'status' => 405,
        'message' => $requestMethod. ' Method Not Allowed',
    ];

    /* send here */
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);

}

?>