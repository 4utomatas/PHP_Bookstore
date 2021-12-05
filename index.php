<?php
require_once("shared/page_components.php");
display_page();
function display_page()
{
    echo display_header("Home");
    echo display_main();
    echo display_footer_without_closing();
    echo display_script_tags();
    echo display_page_closings();
}

function display_main()
{
    $main = <<<MAINPAGE
    <div class="container">
        <h1>Hello, this is the homepage!</h1>
        <h4>Here are some links in this web page:</h4>
        <div class="row">
            <div class="col-md-9">
                <div class="list-group list-group-flush">
                    <a href="/orderBooksForm.php" class="list-group-item list-group-item-action">Order Books</a>
                    <a href="/auth/login_form.php" class="list-group-item list-group-item-action">Login and Logout</a>
                    <a href="/admin/book_list.php" class="list-group-item list-group-item-action">Choose a book to edit</a>
                    <a href="/credits.php" class="list-group-item list-group-item-action">Credits</a>
                </div>
            </div>
            <div class="col-md-3">
                <h2>Offers:</h2>
                <aside id="offers"></aside>
                <aside id="JSONoffers"></aside>
            </div>
        </div>
        
    </div>
MAINPAGE;
    $main .= "\n";
    return $main;
}

function display_script_tags()
{
    $scripts = <<<SCRIPTS
        <script type="text/javascript" src="/js/getOffers.js"></script>       
SCRIPTS;
    $scripts .= "\n";
    return $scripts;
}
