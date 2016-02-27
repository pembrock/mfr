<?php
/**
 * Created by PhpStorm.
 * User: K. Pasikuta
 * Date: 27.02.2016
 * Time: 15:50
 */

require 'inc.php';

$page = $config['path']['pages'] .  ($_SERVER['REQUEST_URI'] == '/' ? '/main.php' : $_SERVER['REQUEST_URI'] . '.php');
if (file_exists($page))
    include "$page";
else
    echo "Error 404";