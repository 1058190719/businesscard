<?php
/**
 * Created by PhpStorm.
 * User: zjl
 * Date: 2018/12/1
 * Time: 23:33
 */
require_once "const.php";
require_once BUSINESSCARD_PATH."/lib/phpqrcode/phpqrcode.php";

//图片上传
function uploadImg(){

}
//图片下载
function downloadImg(){

}
//生成二维码
function generateQRcode($url,$id){
    $value = $url;					//二维码内容

    $errorCorrectionLevel = 'L';	//容错级别
    $matrixPointSize = 5;			//生成图片大小

    //生成二维码图片
    $filename = TEMP_QRCORD_PATH . 'qrcard/' . $id . '.png';
    exec('/usr/bin/sudo touch ' . $filename);
    exec('/usr/bin/sudo chmod 777 '.$filename);
    QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);
}
//数据写入文件
function writeFileJSON($filename,$cardInfo){
    //判断是否是文件的脚本位置
    $isfile = BUSINESSCARD_PATH . 'sbin/isfile.sh';
    $cmd = '/usr/bin/sudo sh ' . $isfile . ' ' . $filename;
    $result = exec($cmd);
    if ($result != 'yes'){
        exec('/usr/bin/sudo touch ' . $filename);
    }
    $file_right = getFileRight($filename);
    exec('/usr/bin/sudo chmod 666 ' . $filename);
    $fp = fopen($filename,'w');
    if (!$fp){
        writeLog('Failed to open file name ' . $filename);
        return false;
    }
    fwrite($fp,base64_encode(json_encode($cardInfo)));
    fclose($fp);
    exec('/usr/bin/sudo chmod ' . $file_right . ' ' . $filename);
    writeLog('Successful to write file name ' . $filename);
    return true;
}
//读取文件数据
function readFileJSON($filename){
    //判断是否是文件的脚本位置
    $isfile = BUSINESSCARD_PATH . 'sbin/isfile.sh';

    $result = exec('/usr/bin/sudo sh ' . $isfile . ' ' . $filename);
    if ($result != 'yes'){
        writeLog('The file named ' . $filename . ' does not exist');
        return false;
    }
    $content = file_get_contents($filename);
    return json_decode(base64_decode($content,true),true);
}
//获取文件权限
function getFileRight($filename){
    return substr(sprintf("%o",fileperms($filename)),-4);
}
//写日志
function writeLog($content){
    $log_file = BUSINESSCARD_PATH . 'log/businesscard.log';
    $content = date('Y-m-d H:i:s',time()) . "\t" . $content;
    $file_right = getFileRight($log_file);
    exec('/usr/bin/sudo chmod 666 ' . $log_file);
    $fp = fopen($log_file,'a+');
    if (!empty(fgets($fp,4096))){
        $content = "\r\n" . $content;
    }
    fwrite($fp, $content);
    fclose($fp);
    exec('/usr/bin/sudo chmod ' . $file_right . ' ' . $log_file);
}