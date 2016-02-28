<?php
/**
 * Created by PhpStorm.
 * User: K. Pasikuta
 * Date: 27.02.2016
 * Time: 17:20
 */

include __DIR__ . '/../../inc/inc.php';
//use Hotel\Users\Users;
session_start();
//setcookie('user_name', 'pembrock@gmail.com', time() + 60*60*24*30, '/');

$user = new Users();
//
if (!$user->is_loggedin() && $_SERVER['REQUEST_URI'] != '/admin/login.php')
    header('Location: login.php');
else {
    $user_info = $user->getUser();
    $twig->addGlobal('options', $user_info);
}


