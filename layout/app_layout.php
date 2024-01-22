<?php
function app_header($page_title = "Dashboard")
{
    $title = $page_title;
    include_once "header.php";
}

function app_footer()
{
    include_once "footer.php";
}