<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');

include('function.php');


$requestMethod = $_SERVER["REQUEST_METHOD"];

if($requestMethod == "GET"){


    //query specific ID, if ada parameter (id) masuk ke func getCustomer, else getCustomerList
    if(isset($_GET['book_id'])){
        $oneBook = getOneBook($_GET);
        echo $oneBook;
    } else {
        //query allcustomerlist
        $bookList = getBookList();
    echo $bookList;
    }
    


}
else 
{

    /* print it here */
    $data = [
        'status' => 405,
        'message' => $requestMethod. 'Method Not Allowed',
    ];

    /* send here */
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);

}

?>