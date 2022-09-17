<?php

include_once 'includes/autoloader.inc.php';
require_once 'includes/http-referer.inc.php';
//
// CHECK IF REQUEST IS REALLY AJAX
//
$isAjax = IHttpReferer::IsAjaxRequest();

// if ($isAjax)
// { 
    
//     echo "AJAX request"; 
// } 
// else
// { 
// echo "Not AJAX"; 
// } 

// Only give response when request came from our own url
$ownUrl = IHttpReferer::IsRequestOwnUrl("ajax.php");

if ($ownUrl)
{
    echo "Own";
}
else
{
    echo "Others";
}
?>