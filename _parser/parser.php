<?php
header("Content-Type: application/javascript");
$filetype = $_GET['type'];
$file = $_GET['file'].'.'.$filetype;
$ftimestamp = './temp/timestamp/'.md5($file).'.'.$filetype.'.txt';
$fcached = './temp/cached/'.md5($file).'.'.$filetype;
require 'class.JavaScriptPacker.php';
$encoding = 2;  //0: none, 1: Numeric, 2:Normal, 3:High ASCII
if (file_exists($file)) {
    $script = file_get_contents($file);
} else {
    if (file_exists($ftimestamp)) {
        unlink($ftimestamp);
        unlink($fcached);
    }
    header("HTTP/1.1 404 Not Found");
    die("/* Sensorsen Website's Javascript compression auto parser \n   File '".htmlspecialchars($file)."' Not Found!!!!!! */");
}
//检查文件是否更新了
require 'check-lastmod.php';

if ($need_parse) {
    
    $packer = new JavaScriptPacker($script, $encoding, false, false);
    $packed = $packer->pack();
    //$packed = str_replace('}function', '};function', $packed);
    
    $parsetime = intval(time());
    $sparsetime = date('D, d M Y H:i:s \G\M\T', $parsetime);
    $out = "/* Sensorsen Website's JS&CSS compression auto parser by Senorsen 2013@qsctech-217: zhs490770@foxmail.com 森\n".
            "    JS compression module powered by edward packer, some bugs fixed & other modified by Senorsen \n".
            "    Sen~ 2013 NO rights reserved.  ";
    $out .= "\n\n    Parsed Time: $sparsetime - $parsetime, Last-modified: $filelastmod   */\n\n";
    $out .= $packed;
    echo $out;
    fwrite(fopen($fcached, "w"), $out);
} else {
    $out = file_get_contents($fcached);
    echo $out;
}
?>