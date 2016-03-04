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
    $isActive = isset($_POST['isActive']) ? $_POST['isActive'] : null;
    if (!empty($_POST['dateRange'])) {
        $date = explode(' - ', $_POST['dateRange']);
        $startTs = new \DateTime($date[0] . ' 00:00:00');
        $stopTs = new \DateTime($date[1] . ' 23:59:59');
    }

    $set = array('title' => $title, 'description' => $description, 'isActive' => $isActive, 'start_ts' => isset($startTs) ? $startTs->format('Y-m-d H:i:s') : null, 'stop_ts' => isset($stopTs) ? $stopTs->format('Y-m-d H:i:s') : null);

    if($id > 0)
        $query = $fpdo->update('promo')->set($set)->where('id', $id);
    else {
        $query = $fpdo->insertInto('promo')->values($set);
    }
    $query->execute();
    $insert_id = $id > 0 ? $id : $pdo->lastInsertId();
    if($id == 0){
        $orderBy = $fpdo->from('promo')->select(null)->select('orderBy')->orderBy('orderBy DESC')->limit(1)->fetch();
        $query = $fpdo->update('promo')->set(array('orderBy' => $orderBy['orderBy'] + 1))->where('id', $insert_id);
        $query->execute();
    }
    header('Location: /admin/promo.php?edit=' . $insert_id);
}

if (isset($_GET['del'])){
    $id = intval($_GET['del']);

    $query = $fpdo->deleteFrom('promo')->where('id', $id);
    $query->execute();

    header('Location: /admin/promo.php');
}
if (isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    if($id > 0){
        $promo = $fpdo->from('promo')->where(array('id' => $id ))->fetch();
        if ($promo) {
            echo $twig->render('/admin/promoEdit.html.twig', array('promo' => $promo));
        }
        else
            echo $twig->render('/admin/promoEdit.html.twig');
    }
    else
        echo $twig->render('/admin/promoEdit.html.twig');

}
else{
    $promo = $fpdo->from('promo')->fetchAll();
    echo $twig->render('/admin/promo.html.twig', array('promo' => $promo));
}
