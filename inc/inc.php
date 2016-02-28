<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 27.02.2016
 * Time: 17:28
 */

include 'config.inc.php';
include __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

#require_once __DIR__ . '/../vendor/twig/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
$twig = new Twig_Environment($loader, array(
    'cache' => false,
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());

$pdo = new PDO("mysql:dbname=". $config['db']['db_name'] , $config['db']['user'], $config['db']['password']);
$pdo->exec("set names utf8");
$fpdo = new FluentPDO($pdo);



