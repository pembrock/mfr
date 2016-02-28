<?php
/**
 * Created by PhpStorm.
 * User: K. Pasikuta
 * Date: 28.02.2016
 * Time: 21:37
 */

require 'inc/ini.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $seats_count = $_POST['seats_count'];
    $isActive = isset($_POST['isActive']) ? $_POST['isActive'] : null;

    $set = array('title' => $title, 'description' => $description, 'seats_count' => $seats_count, 'isActive' => $isActive);

    if($id > 0)
        $query = $fpdo->update('rooms')->set($set)->where('id', $id);
    else {
        $query = $fpdo->insertInto('rooms')->values($set);
    }
    $query->execute();
    $insert_id = $id > 0 ? $id : $pdo->lastInsertId();
    if($id == 0){
        $orderBy = $fpdo->from('rooms')->select(null)->select('orderBy')->orderBy('orderBy DESC')->limit(1)->fetch();
        $query = $fpdo->update('rooms')->set(array('orderBy' => $orderBy['orderBy'] + 1))->where('id', $insert_id);
        $query->execute();
    }
    header('Location: /admin/rooms.php?edit=' . $insert_id);
}
if (isset($_GET['del'])){
    $id = intval($_GET['del']);

    $query = $fpdo->deleteFrom('rooms')->where('id', $id);
    $query->execute();

    header('Location: /admin/hotels.php');
}
if (isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    if($id > 0){
        $rooms = $fpdo->from('rooms')->where(array('id' => $id ))->fetch();
        if ($rooms) {
            echo $twig->render('/admin/roomEdit.html.twig', array('rooms' => $rooms));
        }
        else
            echo $twig->render('/admin/roomEdit.html.twig');
    }
    else
        echo $twig->render('/admin/roomEdit.html.twig');

}
else{
    $rooms = $fpdo->from('rooms')->fetchAll();
    echo $twig->render('/admin/rooms.html.twig', array('rooms' => $rooms));
}
