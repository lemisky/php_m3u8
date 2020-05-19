# php_m3u8
简单下载m3u8文件的composer包
    
    require_once './vendor/autoload.php';
    
    $dump = new m3u8();
    
    $html = $dump->get('https://baidu.com/');
    
    echo $html;
