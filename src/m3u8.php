<?php

class m3u8
{
    private $header = ['user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36'];
    private $work_dir = 'data/';
    private $ch;

    /**
     * m3u8 constructor.
     */
    public function __construct()
    {
        //初始化一个curl句柄
        $this->ch = curl_init();
        //关闭证书验证
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        //获取返回值
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    function close()
    {
        curl_close($this->ch);
    }

    /**
     * @param mixed $work_dir
     */
    public function setDir($work_dir): void
    {
        $this->work_dir = $work_dir;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header): void
    {
        $this->header = $header;
    }

    private function curl_http($url, $method, $header = [])
    {
        //设置一个链接
        curl_setopt($this->ch, CURLOPT_URL, $url);
        //请求方法
        curl_setopt($this->ch, $method, true);
        //设置请求头
        if ($header) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
        } else {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);
        }
        //执行访问
        return curl_exec($this->ch);
    }

    function get($url, $header = [])
    {
        return $this->curl_http($url, CURLOPT_HTTPGET, $header);
    }

    function post($url, $header = [])
    {
        return $this->curl_http($url, CURLOPT_POST, $header);
    }

    function put($url, $header = [])
    {
        return $this->curl_http($url, CURLOPT_PUT, $header);
    }

    function delete($url, $header = [])
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->curl_http($url, null, $header);
    }

    function patch($url, $header = [])
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        return $this->curl_http($url, null, $header);
    }

    function down_m3u8($http, $m3u8, $filename, $header = [])
    {
        if (!file_exists($filename)) {
            preg_match_all('/,\n(.*\.ts)/', $m3u8, $arr);
            echo "正在下载 $filename ...\n";
            echo '[';
            echo str_repeat('-', count($arr[1]));
            echo "]\n-";
            file_put_contents($this->work_dir . 'tmp.ts', '');
            foreach ($arr[1] as $item) {
                file_put_contents($this->work_dir . 'tmp.ts', $this->get($http . $item, $header), FILE_APPEND);
                echo '>';
            }
            echo "\n";
            echo "$filename 下载完成.\n";
            rename('tmp.ts', $this->work_dir . $filename);
            return $this->work_dir . $filename;
        }
        return false;
    }
}