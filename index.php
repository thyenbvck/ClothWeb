<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once('./connection.php');
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    if (isset($_GET['controller'])) {

        $controller = $_GET['controller'];

        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        } else {
            $action = 'index';
        }
    } else {
        $controller = 'home';
        $action = 'index';
    }
} else {
    $page = 'main';
    $controller = 'home';
    $action = 'index';
}
require_once('routes.php');
