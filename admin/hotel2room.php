<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require 'inc/ini.inc.php';

$rooms = $fpdo->from('rooms')->where('isActive', 1)->orderBy('orderBy')->fetchAll();

echo $twig->render('/admin/hotel2room.html.twig', array('rooms' => $rooms));