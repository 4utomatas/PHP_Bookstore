<?php
require_once("../shared/page_components.php");
// connection to db
require_once("../shared/connection.php");
require_once("../shared/functions.php");

// MAIN
if (check_session()) {
    display_page();
} else {
    header("Location: /auth/login_form.php");
}

// Displays page contents
function display_page()
{
    echo display_header("Edit a book");
    echo display_navbar();
    $errors = array();

    try {
        $db_conn = getConnection();
        $id = get_id();
        if ($id != null)
            $edit_form = book_edit($db_conn, $id, $errors);
        else $errors[] = "Error: no id was found.";
        // close the connection
        $db_conn = null;
    } catch (Exception $ex) {
        $errors[] = $ex;
    } finally {
        if (!empty($errors)) {
            echo error_container($errors);
        } else {
            echo $edit_form;
        }
    }
    echo display_footer();
}
// gets and validates the isbn(id) from the url
function get_id()
{
    $id = filter_has_var(INPUT_GET, 'id') ? $_GET['id'] : null;
    return $id;
}
// gets the row by isbn and calls methods to get cats and pubs and then displays it
function book_edit($conn, $id, &$errors)
{
    try {
        $sqlquery = "SELECT * FROM NBL_books WHERE bookISBN=$id";
        $queryResult = $conn->query($sqlquery);
        // if the result has 0 rows, it is empty, which should not be the case
        if ($queryResult->rowCount() == 0) {
            $errors[] = "Error: no such book was found.";
        } else if ($queryResult->rowCount() > 0) {
            while ($qrow = $queryResult->fetchObject()) {
                $pub_id = sanitize_string($qrow->pubID);
                $cat_id = sanitize_string($qrow->catID);
                $categories = category_list($conn, $cat_id, $errors);
                $publishers = publisher_list($conn, $pub_id, $errors);
                $book = sanitize_book($qrow);
                // return a formatted FORM component 
                return book_edit_form($book, $categories, $publishers);
            }
        }
    } catch (Exception $ex) {
        $errors[] = $ex;
    }
}
// a function for retrieving cateogories
function category_list($conn, $selected_id, &$errors)
{
    $sqlquery = "SELECT * FROM NBL_category";
    $queryResult = $conn->query($sqlquery);

    $q_rows = "";
    if ($queryResult->rowCount() == 0) {
        $errors[] = "Error: categories were not found.";
    }
    while ($qrow = $queryResult->fetchObject()) {
        $id = sanitize_string($qrow->catID);
        $desc = sanitize_string($qrow->catDesc);
        if ($id != $selected_id)
            $q_rows .= "<option value='{$id}'> {$desc} </option> \n";
        else $q_rows .= "<option value='{$id}' selected> {$desc} </option> \n";
    }
    $category_list = category_list_format($q_rows);
    // return the list string
    return $category_list;
}
// formatted category list component
function category_list_format($rows)
{
    $category_list = <<<CATLIST
        <select class="custom-select" name="category">
            {$rows}
        </select>
CATLIST;
    $category_list .= "\n";
    return $category_list;
}
// a function for retrieving publishers 
function publisher_list($conn, $selected_id, &$errors)
{
    $sqlquery = "SELECT * FROM NBL_publisher";
    $queryResult = $conn->query($sqlquery);

    $q_rows = "";
    if ($queryResult->rowCount() == 0) {
        $errors[] = "Error: publishers were not found.";
    }
    while ($qrow = $queryResult->fetchObject()) {
        $id = sanitize_string($qrow->pubID);
        $name = sanitize_string($qrow->pubName);
        $location = sanitize_string($qrow->location);
        if ($qrow->pubID != $selected_id)
            $q_rows .= "<option value='{$id}'> {$name} {$location}</option> \n";
        else $q_rows .= "<option value='{$id}' selected> {$name} {$location}</option> \n";
    }
    $publisher_list = publisher_list_format($q_rows);
    // return publisher list string
    return $publisher_list;
}
// formatted publisher list component
function publisher_list_format($rows)
{
    $publisher_list = <<<PUBLIST
        <select class="custom-select" name="publisher">
            {$rows}
        </select>
PUBLIST;
    $publisher_list .= "\n";
    return $publisher_list;
}
// edit form component
function book_edit_form($q_row, $categories, $publishers)
{
    $edit_form = <<<EDITFORM
    <div class="container mt-5">
        <div class="col-lg-6 col-md-8 offset-lg-3 offset-md-2">
            <div class="row justify-content-between">
                <div class="col">
                    <h2> Edit a book </h2>
                </div>
                <div class="col">
                    <a class="btn btn-dark float-right" href="book_list.php"> Go back </a>
                </div>
            </div>
            <form class="" action="book_update.php" method="POST">
                <div class="form-group">
                    <label>ISBN</label>
                    <input class="form-control" name="isbn" readonly="readonly" value="{$q_row->bookISBN}"/>
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" name="title" value="{$q_row->bookTitle}"/>
                </div>
                <div class="form-group">
                    <label>Year</label>
                    <input class="form-control" type="number" step="1" name="year" value="{$q_row->bookYear}"/>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    {$categories}
                </div>
                <div class="form-group">
                    <label>Publisher</label>
                    {$publishers}
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input class="form-control" type="number" step="0.01" min="0" name="price" value="{$q_row->bookPrice}"/>
                </div>
                <div class="form-group">
                    <input class="form-control btn-dark" type="submit" value="Update"/>
                </div>
            </form>
        </div>
    </div>
EDITFORM;
    $edit_form .= "\n";
    return $edit_form;
}
