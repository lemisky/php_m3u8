<?php

require_once './vendor/autoload.php';

$dump = new m3u8();

$html = $dump->get('https://baidu.com/');

echo $html;