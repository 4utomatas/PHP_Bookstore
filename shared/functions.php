<?php
// returns a styled container with errors
function error_container($errors)
{
    $error_list = "";
    foreach ($errors as $err) {
        $error_list .= "<li> {$err} </li>\n";
    }

    $error_container = <<<ERRLIST
    <div class="container mt-5">
        <h2> Something went wrong... </h2>
        <ul>
            {$error_list}        
        </ul>
        <a class="btn btn-dark" href="/admin/book_list.php"> Go back </a>
    </div>
ERRLIST;
    $error_container .= "\n";
    return $error_container;
}
// trim, check if empty, if length > 200 and apply sanitize string
function validate_generic_data(&$data, &$errors)
{
    foreach ($data as $e_key => $e_value) {
        trim($e_value);
        empty($e_value) ? $errors[] = "Error: $e_key is empty." : null;
        if (strlen($e_value) > 200)
            $errors[] = "Error: $e_key is too long.";
        $data[$e_key] = filter_var($data[$e_key], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    }
}
// start the session
function set_session()
{
    ini_set("session.save_path", "/home/unn_w19006600/sessionData");
    if (!isset($_SESSION)) {
        session_start();
    }
}
// check if the session is logged in
function check_session()
{
    set_session();
    if (isset($_SESSION['logged-in']) && $_SESSION['logged-in']) {
        return true;
    } else return false;
}

function sanitize_string($string)
{
    return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
}

function sanitize_book($book)
{
    $book->bookYear = filter_var($book->bookYear, FILTER_SANITIZE_NUMBER_INT);
    $book->bookPrice = filter_var($book->bookPrice, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $book->bookTitle = filter_var($book->bookTitle, FILTER_SANITIZE_STRING);
    return $book;
}
