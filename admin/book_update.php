<?php

require_once("../shared/page_components.php");
require_once("../shared/connection.php");
require_once("../shared/functions.php");
// Main
if (check_session()) {
    display_page();
} else {
    header("Location: /auth/login_form.php");
}
// Displays page contents
function display_page()
{
    echo display_header("Update a book");
    echo display_navbar();

    $errors = array();
    $input = array();
    // have a list of the fields for the forms
    $field_names = array('isbn', 'title', 'year', 'price', 'category', 'publisher');
    // get all inputs
    foreach ($field_names as $name) {
        get_input($input, $errors, $name);
    }

    if (empty($errors))
        validate_generic_data($input, $errors);

    $categories = array();
    try {
        $conn = getConnection();
        if (empty($errors))
            $categories = get_categories($conn);
    } catch (Exception $ex) {
        $errors[] = $ex;
    }

    $publishers = array();
    try {
        $conn = getConnection();
        if (empty($errors))
            $publishers = get_publishers($conn);
    } catch (Exception $ex) {
        $errors[] = $ex;
    }

    if (empty($errors))
        validate_data($input, $errors, $categories, $publishers);
    if (empty($errors))
        execute_query($input, $errors);
    // if there were any errors, print them
    if (!empty($errors))
        echo error_container($errors);

    echo display_footer();
}
// Retrieves $field_name from POSTed input and puts it into array
function get_input(&$input_arr, &$errors_arr, $field_name)
{
    $temp = filter_has_var(INPUT_POST, $field_name) ? $_POST[$field_name] : null;
    if ($temp == null) {
        $errors_arr[] = "Error: " . $field_name . " was not found.";
    } else {
        $input_arr[$field_name] = $temp;
    }
}
// Executes an update query for the edited book
// calls success or error method 
function execute_query($input, &$errors)
{
    if (empty($errors)) {
        try {
            $conn = getConnection();
            $sqlquery = "UPDATE NBL_books SET bookTitle=:title, bookYear=:byear,
             bookPrice=:price, catID=:category, pubID=:publisher WHERE bookISBN=:isbn";
            $stmt = $conn->prepare($sqlquery);
            $query_result = $stmt->execute(
                array(
                    ':title' => $input['title'],
                    ':byear' => $input['year'],
                    ':price' => $input['price'],
                    ':category' => $input['category'],
                    ':publisher' => $input['publisher'],
                    ':isbn' => $input['isbn']
                )
            );
            if ($query_result) {
                echo success_container();
            }
            // close the connection
            $conn = null;
        } catch (Exception $ex) {
            $errors[] = $ex;
        }
    }
}
// Displays a success message
function success_container()
{
    $con = <<<SUCCESS
        <div class="container mt-5">
            <h2> Success! </h2>
            <p> The book was successfully updated. </p>
            <a class="btn btn-dark" href="book_list.php"> Go back </a>
        </div>
SUCCESS;
    $con .= "\n";
    return $con;
}
// get a list of categories
function get_categories($conn)
{
    $sqlquery = "SELECT * FROM NBL_category";
    $queryResult = $conn->query($sqlquery);

    $categories = array();
    if ($queryResult->rowCount() == 0) {
        $errors[] = "Error: categories were not found.";
    }
    while ($qrow = $queryResult->fetchObject()) {
        $categories[] = $qrow->catID;
    }
    return $categories;
}
// get a list of publishers
function get_publishers($conn)
{
    $sqlquery = "SELECT * FROM NBL_publisher";
    $queryResult = $conn->query($sqlquery);

    $publishers = array();
    if ($queryResult->rowCount() == 0) {
        $errors[] = "Error: publishers were not found.";
    } else if ($queryResult->rowCount() > 0) {
        while ($qrow = $queryResult->fetchObject()) {
            $publishers[] = $qrow->pubID;
        }
    }
    return $publishers;
}
// validate specific fields
function validate_data(&$input, &$errors, $categories, $publishers)
{
    $filtered = filter_var($input['year'], FILTER_VALIDATE_INT);
    if (!$filtered) {
        $errors[] = "Error: year was entered incorrectly.";
    }
    $filtered = filter_var($input['price'], FILTER_VALIDATE_FLOAT);
    if (!$filtered) {
        $errors[] = "Error: price was entered incorrectly.";
    }
    if (!in_array($input['category'], $categories)) {
        $errors[] = "Error: category is incorrect.";
    }
    if (!in_array($input['publisher'], $publishers)) {
        $errors[] = "Error: publisher is incorrect.";
    }
}
