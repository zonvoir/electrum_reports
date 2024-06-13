<?php

$loggedInUser = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$role = $loggedInUser ? $loggedInUser['role'] : '';

function canAccess($roleName) 
{
    global $role;
    
    if ($role['name'] == $roleName) {
        return true;
    } else {
        return false;
    }
}

?>