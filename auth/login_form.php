<?php
require_once("../shared/page_components.php");
require_once("../shared/functions.php");
// if you are logged in, you get already_loggedin to books
echo display_header("Login");
// check if the user is logged in
if (check_session()) {
    echo already_loggedin();
} else echo display_main();

echo display_footer();
// display message if already logged in 
function already_loggedin()
{
    $con = <<<SUCCESS
        <div class="container mt-5">
            <h2> You are already logged in! </h2>
            <p> You can proceed to the administrator side of the webpage. </p>
            <a class="btn btn-dark" href="/admin/book_list.php"> Go to books </a>
            <a class="btn btn-dark" href="logout.php"> Logout from the webpage </a>
        </div>
SUCCESS;
    $con .= "\n";
    return $con;
}
// display login form
function display_main()
{
    $login_form = <<<LOGIN
    <div class="container mt-5">
        <div class="container col-md-6 offset-md-3">
            <h2>You are required to login</h2>
            <form method="post" action="login_process.php">
                Username <input class="form-control mb-3" type="text" name="username" required="required">
                Password <input class="form-control mb-3" type="password" name="password" required="required">
                <div class="row">
                    <div class="col-8">
                        <input class="btn btn-dark btn-block" type="submit" value="Log in">
                    </div>
                    <div class="col-4">
                        <a href="/index.php" class="btn btn-dark btn-block"> Home </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
LOGIN;
    $login_form .= "\n";
    return $login_form;
}
