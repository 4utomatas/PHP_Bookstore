<?php
require_once("../shared/page_components.php");
echo display_header("Logout");
//logs out the user from the site, destroys session
ini_set("session.save_path", "/home/unn_w19006600/sessionData");
try {
  session_start();
  $_SESSION = array();
  session_destroy();
  echo success_container();
} catch (Exception $e) {
  echo "An error has occured: $e";
}

function success_container()
{
  $con = <<<SUCCESS
        <div class="container mt-5">
            <h2> Successfuly logged off </h2>
            <p> Go to login page <a href="login_form.php"> here </a>
        </div>
SUCCESS;
  $con .= "\n";
  return $con;
}
