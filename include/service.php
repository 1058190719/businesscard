<?php
/**
 * Created by PhpStorm.
 * User: zjl
 * Date: 2018/12/1
 * Time: 23:39
 */
require_once "const.php";
require_once  BUSINESSCARD_PATH . "include/function.php";

$action = isset($_POST['action'])?$_POST['action']:$_GET['action'];
if ($action == 'showCardInfo'){
    $id = $_GET['id'];
    $filename = TEMP_QRCORD_PATH  . 'carddata/' . $id . '.json';
    exit(json_encode(readFileJSON($filename)));
}
if ($action == 'setCardInfo'){
    $id = time();
    $arr = array(
        'name' => $_POST['name'],
        'age' => $_POST['age'],
        'sex' => $_POST['sex']
    );
    $url = $_POST['url'] . '?id='.$id;
    $filename = TEMP_QRCORD_PATH  . 'carddata/' . $id . '.json';
    writeFileJSON($filename,$arr);
    generateQRcode($url,$id) ;
    exit(json_encode(array('id' => $id)));
}

