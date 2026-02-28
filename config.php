<?php



ini_set('session.cookie_path', '/');




if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
