<?php
session_start();
function is_user_logged_in()
{
    return isset($_SESSION['user']);
}

?>