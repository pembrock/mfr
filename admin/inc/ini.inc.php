<?php
/**
 * Created by PhpStorm.
 * User: K. Pasikuta
 * Date: 27.02.2016
 * Time: 17:20
 */

include __DIR__ . '/../../inc/inc.php';

use ptejada\uFlex;
$user = new uFlex\User();

if ($user->isSigned())
    echo "ok";
else
    echo "bad";