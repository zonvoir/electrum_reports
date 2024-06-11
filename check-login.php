<?php
ob_start();
function redirect($url) 
{
    header("Location: $url");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['user'])) {

    if ($currentPage !== 'login.php' && $currentPage !== 'register.php') {
        redirect("login.php");
    }

} else {
    
    
    if ($currentPage == 'login.php' || $currentPage == 'register.php') {
        $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        redirect($previousPage);
    }
}
ob_end_flush();
?>