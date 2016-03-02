<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require 'inc/ini.inc.php';

if(isset($_POST['type']) && $_POST['type'] == 'add')
{
    $hid = intval($_POST['id']);
    $rid = intval($_POST['rid']);
    $isActive = intval($_POST['isActive']);
    $cost_hour = intval($_POST['cost_hour']);
    $cost_day = intval($_POST['cost_day']);
    $description = $_POST['description'];
    $values = array('hid' => $hid, 'rid' => $rid, 'description' => $description, 'cost_hour' => $cost_hour, 'cost_day' => $cost_day, 'isActive' => $isActive);
    $query = $fpdo->insertInto('hotel2room')->values($values);
    $query->execute();

    header('Location: /admin/hotels.php?edit=' . $hid);
}

$data = array();
if (isset($_GET['act']))
{
    switch($_GET['act'])
    {
        case 'add':
            $data['hid'] = $_GET['hid'];
            $data['type'] = $_GET['act'];
            break;
        case 'edit':
            $data['hid'] = $_GET['hid'];
            $data['rid'] = $_GET['rid'];
            break;
    }
}

$rooms = $fpdo->from('rooms')->where('isActive', 1)->orderBy('orderBy')->fetchAll();

echo $twig->render('/admin/hotel2room.html.twig', array('rooms' => $rooms, 'data' => $data));