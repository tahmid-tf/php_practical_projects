<?php

function loginUser($user)
{
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['name'];
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function requireAuth()
{
    if (! isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function logoutUser()
{
    session_unset();
    session_destroy();
}
