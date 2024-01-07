<?php

require '../inc/dbcon.php';

function error422($message){

    /* print it here */
    $data = [
        'status' => 422,
        'message' => $message,
    ];

    /* send here */
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
    exit();
}

function storeBook($bookInput){
    global $conn;

    $book_title = mysqli_real_escape_string($conn, $bookInput['book_title']);
    $book_author = mysqli_real_escape_string($conn, $bookInput['book_author']);
    $book_year = mysqli_real_escape_string($conn, $bookInput['book_year']);
    $book_desc = mysqli_real_escape_string($conn, $bookInput['book_desc']);
    $book_image = mysqli_real_escape_string($conn, $bookInput['book_image']);
    $availability = mysqli_real_escape_string($conn, $bookInput['availability']); //optional
    $borrower = mysqli_real_escape_string($conn, $bookInput['borrower']); //optional
    $borrow_date = mysqli_real_escape_string($conn, $bookInput['borrow_date']); //optional
    $return_date = mysqli_real_escape_string($conn, $bookInput['return_date']); //optional
    
    if(empty(trim($book_title))){
        
        return error422('Enter book title');
    }elseif(empty(trim($book_author))){

        return error422('Enter book author');
    }elseif(empty(trim($book_year))){

        return error422('Enter book year');
    }elseif(empty(trim($book_desc))){

        return error422('Enter book desc');
    }elseif(empty(trim($book_image))){

        return error422('Enter book image');
    }else {
        $query = "INSERT INTO books (book_title, book_author, book_year, book_desc, book_image, availability, borrower, borrow_date, return_date) VALUES ('$book_title','$book_author',$book_year,'$book_desc','$book_image','1',NULL,NULL,NULL)";
        $result = mysqli_query($conn, $query);

        if($result){

            /* print it here */
            $data = [
                'status' => 201,
                'message' => 'Book Stored Successfully',
            ];
    
            /* send here */
            header("HTTP/1.0 201 Stored");
            return json_encode($data);

        }else {
            /* print it here */
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
    
            /* send here */
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        
        }
    }


}
function storeBorrower($borrowerInput){
    global $conn;

    $borrower_name = mysqli_real_escape_string($conn, $borrowerInput['borrower_name']);
    $borrowed_book = mysqli_real_escape_string($conn, $borrowerInput['borrowed_book']);
    // $borrow_date = mysqli_real_escape_string($conn, $borrowerInput['borrow_date']);
    // $return_date = mysqli_real_escape_string($conn, $borrowerInput['return_date']);
    
    
    if(empty(trim($borrower_name))){
        
        return error422('Enter borrower name');
    }elseif(empty(trim($borrowed_book))){

        return error422('Enter the book');
    
    }else {
        $query = "INSERT INTO borrowers (borrower_name, borrowed_book, borrow_date, return_date) VALUES (UPPER('$borrower_name'),$borrowed_book,curdate(),DATE_ADD(curdate(), INTERVAL 7 DAY))";
        // $query = "INSERT INTO borrowers (borrower_name) VALUES ('$borrower_name')";

        $result = mysqli_query($conn, $query);

        if($result){

            /* print it here */
            $data = [
                'status' => 201,
                'message' => 'Borrowed Stored Successfully',
            ];
    
            /* send here */
            header("HTTP/1.0 201 Stored");
            return json_encode($data);

        }else {
            /* print it here */
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
    
            /* send here */
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        
        }
    }


}

function getOneBorrowerId($borrowerParam){
    global $conn;

    if($borrowerParam['borrower_name'] == null){
        
        return error422('name not fetched yet');

    }

    $borrowerName = mysqli_real_escape_string($conn, $borrowerParam['borrower_name']);

    $query = "SELECT * FROM borrowers WHERE borrower_name LIKE UPPER('%$borrowerName%') LIMIT 1";
    
    $result = mysqli_query($conn, $query); 
    echo $query;


    if($result){

        if(mysqli_num_rows($result) == 1){

            $res = mysqli_fetch_assoc($result);
            return json_encode($res);

            
        // $data = [
        //     'status' => 200,
        //     'message' => 'Book Fetched Successfully',
        //     'data' => $res
        //     ];
    
        //     header("HTTP/1.0 200 Success");
        //     return json_encode($data);

        }else{
            /* print it here */
            $data = [
                'status' => 404,
                'message' => 'No Borrower Found',
            ];
    
            /* send here */
            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }
    }else{
        /* print it here */
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
    
        /* send here */
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function getOneBorrowerfromBook($borrowerParam){
    global $conn;

    if($borrowerParam['borrowed_book'] == null){
        
        return error422('name not fetched yet');

    }

    $borrowedBook = mysqli_real_escape_string($conn, $borrowerParam['borrowed_book']);

    $query = "SELECT * FROM borrowers WHERE borrowed_book = '$borrowedBook' LIMIT 1";
    
    $result = mysqli_query($conn, $query); 
    // echo $query;


    if($result){

        if(mysqli_num_rows($result) == 1){

            $res = mysqli_fetch_assoc($result);
            return json_encode($res);

            
        // $data = [
        //     'status' => 200,
        //     'message' => 'Book Fetched Successfully',
        //     'data' => $res
        //     ];
    
        //     header("HTTP/1.0 200 Success");
        //     return json_encode($data);

        }else{
            /* print it here */
            $data = [
                'status' => 404,
                'message' => 'No Borrower Found',
            ];
    
            /* send here */
            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }
    }else{
        /* print it here */
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
    
        /* send here */
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function getBorrowerList(){
    global $conn;

    $query = "SELECT * FROM borrowers";
    $query_run = mysqli_query($conn, $query);

    if($query_run){
        
        if(mysqli_num_rows($query_run) > 0){

            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            echo json_encode($res);
            // $data = [
            //     'status' => 200,
            //     'message' => 'Books List Fetched Successfully',
            //     'data' => $res


            //     ];
        
            //     /* send here */
            //     header("HTTP/1.0 200 OK");
            //     echo json_encode($data);

        }else {
            $data = [
                'status' => 404,
                'message' => 'No Borrower Found',
                ];
        
                /* send here */
                header("HTTP/1.0 404 No Borrower Found");
                return json_encode($data);
        }


    }else{
        /* print it here */
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];

        /* send here */
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

//get book details
function getOneBook($bookParam){
    global $conn;

    if($bookParam['book_id'] == null){
        
        return error422('Id not fetched yet');

    }

    $bookId = mysqli_real_escape_string($conn, $bookParam['book_id']);

    $query = "SELECT * FROM books WHERE book_id='$bookId' LIMIT 1";
    $result = mysqli_query($conn, $query); 


    if($result){

        if(mysqli_num_rows($result) == 1){

            $res = mysqli_fetch_assoc($result);
            return json_encode($res);

            
        // $data = [
        //     'status' => 200,
        //     'message' => 'Book Fetched Successfully',
        //     'data' => $res
        //     ];
    
        //     header("HTTP/1.0 200 Success");
        //     return json_encode($data);

        }else{
            /* print it here */
            $data = [
                'status' => 404,
                'message' => 'No Book Found',
            ];
    
            /* send here */
            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }
    }else{
        /* print it here */
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
    
        /* send here */
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function getBookList(){
    global $conn;

    $query = "SELECT * FROM books";
    $query_run = mysqli_query($conn, $query);

    if($query_run){
        
        if(mysqli_num_rows($query_run) > 0){

            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            echo json_encode($res);
            // $data = [
            //     'status' => 200,
            //     'message' => 'Books List Fetched Successfully',
            //     'data' => $res


            //     ];
        
            //     /* send here */
            //     header("HTTP/1.0 200 OK");
            //     echo json_encode($data);

        }else {
            $data = [
                'status' => 404,
                'message' => 'No Books Found',
                ];
        
                /* send here */
                header("HTTP/1.0 404 No Customer Found");
                return json_encode($data);
        }


    }else{
        /* print it here */
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];

        /* send here */
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

//update for borrowing book
function updateBook($bookParam){
    global $conn;

    if(!isset($bookParam['book_id'])){
        return error422('Book ID not found');
    }elseif ($bookParam['book_id'] == null){
        return error422('Enter your Book ID');
    }

    $bookId = mysqli_real_escape_string($conn, $bookParam['book_id']);
    //  return error422('Enter return date');
    // }
    
    
    
        $query = "UPDATE books SET availability='2' WHERE book_id='$bookId' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if($result){

            /* print it here */
            $data = [
                'status' => 200,
                'message' => 'Book Updated Successfully',
            ];
    
            /* send here */
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        }else {
            /* print it here */
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
    
            /* send here */
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        
        }
    
}

//update for borrowing book
function updateBorrower($bookInput, $borrowerParam){
    global $conn;

    if(!isset($borrowerParam['borrower_id'])){
        return error422('Borrower Id not Found');
    }elseif ($borrowerParam['borrower_id'] == null){
        return error422('Enter your Borrower ID');
    }

    $borrowerId = mysqli_real_escape_string($conn, $borrowerParam['borrower_id']);
    $borrowed_book = mysqli_real_escape_string($conn, $bookInput['borrowed_book']); // get id from click
    
    if(empty(trim($borrowed_book))){
        return error422('Enter Borrowed book Id');
    }
    
    else {
        $query = "UPDATE borrowers SET borrowed_book='$borrowed_book' WHERE borrower_id='$borrowerId' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if($result){

            /* print it here */
            $data = [
                'status' => 200,
                'message' => 'Borrower Updated Successfully',
            ];
    
            /* send here */
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        }else {
            /* print it here */
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
    
            /* send here */
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        
        }
    }
}




//update for returned book
function updateReturnedBook($bookParam){
    global $conn;

    if(!isset($bookParam['book_id'])){
        return error422('Book ID not found');
    }elseif ($bookParam['book_id'] == null){
        return error422('Enter your Book ID');
    }

    $bookId = mysqli_real_escape_string($conn, $bookParam['book_id']);
    // echo $bookId;

    
    $query = "UPDATE books SET availability='1' WHERE book_id='$bookId' LIMIT 1";
    $result = mysqli_query($conn, $query);

        if($result){

            /* print it here */
            $data = [
                'status' => 200,
                'message' => 'Book Updated Successfully',
            ];
    
            /* send here */
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        }else {
            /* print it here */
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
    
            /* send here */
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        
        }
}

//update for returned book
function updateReturnedBorrower($borrowerParam){
    global $conn;

    if(!isset($borrowerParam['borrower_id'])){
        return error422('Borrower Id not Found');
    }elseif ($borrowerParam['borrower_id'] == null){
        return error422('Enter your Borrower ID');
    }

    $borrowerId = mysqli_real_escape_string($conn, $borrowerParam['borrower_id']);
    // echo $bookId;

    // $borrowed_book = mysqli_real_escape_string($conn, $bookInput['borrowed_book']); // get id from click
    
    $query = "UPDATE borrowers SET borrowed_book=NULL,borrow_date=NULL,return_date=NULL WHERE borrower_id='$borrowerId' LIMIT 1";

    $result = mysqli_query($conn, $query);

        if($result){

            /* print it here */
            $data = [
                'status' => 200,
                'message' => 'Borrower Updated Successfully',
            ];
    
            /* send here */
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        }else {
            /* print it here */
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
    
            /* send here */
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        
        }
}


function deleteBorrower($borrowerParam){
    //from book_id -> Borrowed_book
    global $conn;

    if(!isset($borrowerParam['borrowed_book'])){
        return error422('Book ID not found');
    }elseif ($borrowerParam['borrowed_book'] == null){
        return error422('Enter your Book ID');
    }

    $bookId = mysqli_real_escape_string($conn, $borrowerParam['borrowed_book']);
    //  return error422('Enter return date');
    // }
    
    
    
        $query = "DELETE FROM borrowers WHERE borrowed_book='$bookId' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if($result){

            /* print it here */
            $data = [
                'status' => 200,
                'message' => 'Book Deleted Successfully',
            ];
    
            /* send here */
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        }else {
            /* print it here */
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
    
            /* send here */
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        
        }
}
?>