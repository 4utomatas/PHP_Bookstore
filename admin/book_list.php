<?php
require_once("../shared/page_components.php");
require_once("../shared/connection.php");
require_once("../shared/functions.php");
// check if the user is logged in
if (check_session()) {
    display_page();
} else {
    header("Location: /auth/login_form.php");
}
// Displays page contents
function display_page()
{
    echo display_header("Book list");
    echo display_navbar();
    $errors = array();
    try {
        $db_conn = getConnection();
        book_list($db_conn, $errors);
        // closes the connection
        $db_conn = null;
    } catch (Exception $ex) {
        $errors[] = $ex;
    }
    if (!empty($errors)) {
        echo error_container($errors);
    }
    echo display_footer();
}
// gets books from db and calls methods to format them
function book_list($conn)
{
    try {
        $sqlquery = "SELECT * FROM NBL_books as books
        JOIN NBL_category as cat on cat.catID = books.catID
        ORDER BY books.bookTitle ASC";
        $queryResult = $conn->query($sqlquery);
        if ($queryResult->rowCount() == 0) {
            $errors[] = "Error: No books were found.";
        } else if ($queryResult->rowCount() > 0) {
            $q_rows = "";
            while ($qrow = $queryResult->fetchObject()) {
                $book = sanitize_book($qrow);
                $q_rows .= book_row_format($book);
            }
            // display the list
            echo book_list_format($q_rows);
        }
    } catch (Exception $ex) {
        $errors[] = $ex;
    }
}
// formats a book object into a table row 
function book_row_format($q_row)
{
    $row_content = <<<ROWCONTENT
        <tr>
            <td><a href="book_edit.php?id={$q_row->bookISBN}">{$q_row->bookTitle}</a></td>
            <td>{$q_row->bookYear}</td>
            <td>{$q_row->catDesc}</td>
            <td>{$q_row->bookPrice}</td>
            <td><a class="btn btn-primary" href="book_edit.php?id={$q_row->bookISBN}">Edit</a></td>
        </tr>
ROWCONTENT;
    $row_content .= "\n";
    return $row_content;
}
// takes in book table rows and formats into a table
function book_list_format($q_rows)
{
    // var_dump($q_rows);
    $list_content = <<<LISTCONTENT
        <div class="container mt-5">
            <h1> Book list </h1>
            <p> Select a book you want to edit. </p>
            <div class="table-responsive-sm">
                <table class="table"
                    <thead>
                        <tr>
                            <td>Title</td>
                            <td>Year</td>
                            <td>Category</td>
                            <td>Price</td>
                            <td>Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        $q_rows
                    </tbody>
                </table>
            </div>
        </div>
LISTCONTENT;
    $list_content .= "\n";
    return $list_content;
}
