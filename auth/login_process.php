<?php
require_once("../shared/page_components.php");
require_once("../shared/connection.php");
require_once("../shared/functions.php");

// if you are logged in, you get redirected to books
if (check_session()) {
  success_container();
} else {
  display_page();
}
// main function, displays contents of page
function display_page()
{
  echo display_header("Logging in...");
  $input = array();
  $errors = array();
  $input = get_input($errors);
  // always needs to check if the errors array is empty before continuing
  if (empty($errors))
    $pass_hash = get_password($input['username'], $errors);
  if (empty($errors))
    check_password($pass_hash, $input['password'], $errors);
  if (!empty($errors)) {
    echo error_container($errors);
  } else {
    echo success_container();
  }

  echo display_footer();
}
// get POST'ed variables
function get_input(&$errors)
{
  $input = array();
  $input['username'] = filter_has_var(INPUT_POST, 'username')
    ? $_POST['username'] : $errors[] = "Error: username is required.";
  $input['password'] = filter_has_var(INPUT_POST, 'password')
    ? $_POST['password'] : $errors[] = "Error: password is required.";
  return $input;
}
// get password hash from db by username
function get_password($username, &$errors)
{
  try {
    // sanitize the username
    $username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    // Make a database connection
    $dbConn = getConnection();
    $querySQL = "SELECT passwordHash FROM NBL_users WHERE username = :username";
    // Prepare the sql statement using PDO
    $stmt = $dbConn->prepare($querySQL);
    // Execute the query using PDO
    $stmt->execute(array(':username' => $username));
    if ($stmt->rowCount() == 0) {
      $errors[] = "Error: Incorrect credentials.";
    } else if ($stmt->rowCount() > 0) {
      $user = $stmt->fetchObject();
      return $user->passwordHash;
    }
    // if the password was not found, return null
    return null;
  } catch (Exception $e) {
    $errors[] = "There was a problem: {$e->getMessage()}";
  }
  return null;
}
// check if passwords match and log in 
function check_password($pass_hash, $password, &$errors)
{
  if (!empty($pass_hash)) {
    if (password_verify($password, $pass_hash)) {
      // logs in
      set_session();
      $_SESSION['logged-in'] = true;
    } else {
      $errors[] = "Error: Incorrect credentials.";
    }
  } else {
    $errors[] = "Error: Incorrect credentials.";
  }
}
// display a success message
function success_container()
{
  $con = <<<SUCCESS
        <div class="container mt-5">
            <h2> Success! </h2>
            <p> You have successfully logged in. </p>
            <a class="btn btn-dark" href="/admin/book_list.php"> Go to books </a>
        </div>
SUCCESS;
  $con .= "\n";
  return $con;
}
