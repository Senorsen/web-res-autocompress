<?php
date_default_timezone_set("Asia/Shanghai");
$need_parse = false;
$filelastmod = intval(filemtime($file));
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
    $browsertime = intval(strtotime(preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE'])));
    if ($browsertime >= $filelastmod) {
        header("HTTP/1.1 304 Not Modified");
        exit(0);
    }
}

//else
if (file_exists($ftimestamp)) {
    header('Last-Modified: '.date('D, d M Y H:i:s \G\M\T', $filelastmod));
    $timeinfile = intval(file_get_contents($ftimestamp));
    if ($timeinfile != $filelastmod) {
        $need_parse = true;
        fwrite(fopen($ftimestamp, "w"), $filelastmod);
    } else {
        $need_parse = false;
    }
} else {
    fwrite(fopen($ftimestamp, "w"), $filelastmod);
    $need_parse = true;
}

?>