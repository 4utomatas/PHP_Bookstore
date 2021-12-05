<?php

function display_header($title)
{
    if (empty($title)) {
        $title = "NBL";
    }
    $header_content = <<<HEADER
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>{$title}</title>
            <link rel="stylesheet" href="/css/bootstrap.css">
            <link rel="stylesheet" href="/css/footer.css">
        </head>
        <body>
HEADER;
    $header_content .= "\n";
    return $header_content;
}

function display_footer()
{
    $footer_content = <<<FOOTER
        <footer class="footer">
            <div class="container">
                <span>This page was made by w19006600.</span>
            </div>
        </footer>
    </body>
    </html>
FOOTER;
    $footer_content .= "\n";
    return $footer_content;
}
// this function displays only footer without closing tags for body and html
function display_footer_without_closing()
{
    $footer_content = <<<FOOTER2
        <footer class="footer">
            <div class="container">
                <span>This page was made by w19006600.</span>
            </div>
        </footer>
FOOTER2;
    $footer_content .= "\n";
    return $footer_content;
}
function display_page_closings()
{
    $footer_content = <<<FOOTER3
    </body>
    </html>
FOOTER3;
    $footer_content .= "\n";
    return $footer_content;
}
function display_navbar()
{
    $link = check_login();
    $nav = <<<NAV
    <nav class="navbar navbar-dark bg-dark fixed-top" >
        <a style="letter-spacing: 5px;" class="navbar-brand" href="/index.php">NBL</a>
        <ul class="nav navbar-custom">
            <li class="nav-item">
                <a class="nav-link" href="/index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/book_list.php">Books</a>
            </li>
            <li class="nav-item">
                {$link}
            </li>
        </ul>
    </nav>
NAV;
    $nav .= "\n";
    return $nav;
}
// check which button to show in navbar login/logout
function check_login()
{
    ini_set("session.save_path", "/home/unn_w19006600/sessionData");

    if (!isset($_SESSION)) {
        session_start();
    }
    $link = "";
    if (isset($_SESSION['logged-in']) && $_SESSION['logged-in']) {
        $link = '<a class="nav-link" href="/auth/logout.php"> Logout </a>';
    } else {
        $link = '<a class="nav-link" href="/auth/login_form.php"> Login </a>';
    }


    return $link;
}
