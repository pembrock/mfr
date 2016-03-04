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
    //Image upload
    if (!empty($_FILES['logo']['name'])){
        if ($id > 0){
            $query = $fpdo->from('hotel')->select(null)->select('logo')->where('id', $id)->fetch(); //Check 'logo' field in DB
            if(!is_null($query['logo'])) //if not NULL
                unlink('../public/upload/images/logos/' . $query['logo']); //delete old logo file
        }
        $handle = new upload($_FILES['logo']);
        if($handle->uploaded){
            $handle->file_new_name_body   = md5(time());
            $handle->image_resize         = true;
            $handle->image_x              = 300;
            $handle->image_ratio_y        = true;
            $handle->process('../public/upload/images/logos/');
            if (!$handle->processed) {
                echo 'error : ' . $handle->error;
                die();
            }
            else{
                $logo = $handle->file_dst_name;
                $handle->clean();
            }
        }
    }

    $set = array('title' => $title, 'description' => $description);
    if ($logo)
        $set['logo'] = $logo;
    if($id > 0)
        $query = $fpdo->update('hotel')->set($set)->where('id', $id);
    else {
        $query = $fpdo->insertInto('hotel')->values($set);
    }
    $query->execute();
    $insert_id = $id > 0 ? $id : $pdo->lastInsertId();
    if($id == 0){
        $orderBy = $fpdo->from('hotel')->select(null)->select('orderBy')->orderBy('orderBy DESC')->limit(1)->fetch();
        $query = $fpdo->update('hotel')->set(array('orderBy' => $orderBy['orderBy'] + 1))->where('id', $insert_id);
        $query->execute();
    }
    header('Location: /admin/hotels.php?edit=' . $insert_id);
}

if (isset($_GET['del'])){
    $id = intval($_GET['del']);
    $query = $fpdo->from('hotel')->select(null)->select('logo')->where('id', $id)->fetch(); //Check 'logo' field in DB
    if(!is_null($query['logo'])) //if not NULL
        unlink('../public/upload/images/logos/' . $query['logo']); //delete old logo file

    $query = $fpdo->deleteFrom('hotel')->where('id', $id);
    $query->execute();

    header('Location: /admin/hotels.php');
}
if (isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    if($id > 0){
        $hotels = $fpdo->from('hotel')->where(array('id' => $id ))->fetch();

        $h2r = $fpdo->from('hotel2room')
                    ->leftJoin('rooms ON rooms.id = hotel2room.rid')
                    ->select('rooms.title')
                    ->where(array('hid' => $id))
                    ->orderBy('rooms.orderBy')
                    ->fetchAll();
        if ($hotels) {
            if (!empty($hotels['logo']) && file_exists('../public/upload/images/logos/' . $hotels['logo']))
                $hotels['logo'] = '../public/upload/images/logos/' . $hotels['logo'];
            else
                $hotels['logo'] = NULL;

            echo $twig->render('/admin/hotelEdit.html.twig', array('hotels' => $hotels, 'h2r' => $h2r));
        }
        else
            echo $twig->render('/admin/hotelEdit.html.twig');
    }
    else
        echo $twig->render('/admin/hotelEdit.html.twig');

}
else{
    $hotels = $fpdo->from('hotel')->fetchAll();
    echo $twig->render('/admin/hotels.html.twig', array('hotels' => $hotels));
}
