<?php
namespace common\helpers;

/**
 * 网络帮助类
 * @author wjh
 * @version 2014-4-29
 */
class NetHelper
{


    public static  function getCookie(){
        $cookie = $_SERVER['HTTP_COOKIE'];//'PHPSESSID=3gv9f471kuijpbiqntuhk63sk2;';
        return sprintf("\"%s\"",$cookie);
    }

    /**
     * httpRequestSimple
     * @author wjh 2014-8-13
     * @param $url
     * @param string $cookie
     * @return mixed
     */
    public static function httpRequestSimple($url,$cookie='') {
        if(strlen($cookie)==0)
        {
            $cookie = NetHelper::getCookie();
        }
        return self::httpRequest($url,'','GET',0,FALSE,$cookie);
    }


   /** 
    * Respose A Http Request 
    * 
    * @param string $url 
    * @param array $post 
    * @param string $method 
    * @param bool $returnHeader 
    * @param string $cookie 
    * @param bool $bysocket 
    * @param string $ip 
    * @param integer $timeout 
    * @param bool $block 
    * @return string Response 
    */  
 	public static function httpRequest($url,$post='',$method='GET',$limit=0,$returnHeader=FALSE,$cookie='',$bysocket=FALSE,$ip='',$timeout=15,$block=TRUE) {  
       $return = '';  
       $matches = parse_url($url);

        if(strlen($cookie)==0)
        {
            $cookie = NetHelper::getCookie();
        }
  
       !isset($matches['host']) && $matches['host'] = '';  
       !isset($matches['path']) && $matches['path'] = '';  
       !isset($matches['query']) && $matches['query'] = '';  
       !isset($matches['port']) && $matches['port'] = '';  
  
       $host = $matches['host'];  
       $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';  
       $port = !empty($matches['port']) ? $matches['port'] : 80;  

       if(strtolower($method) == 'post') {
           $post = (is_array($post) and !empty($post)) ? http_build_query($post) : $post;
           $out = "POST $path HTTP/1.0\r\n";
           $out .= "Accept: */*\r\n";
           //$out .= "Referer: $boardurl\r\n";
           $out .= "Accept-Language: zh-cn\r\n";
           $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
           $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
           $out .= "Host: $host\r\n";
           $out .= 'Content-Length: '.strlen($post)."\r\n";
           $out .= "Connection: Close\r\n";
           $out .= "Cache-Control: no-cache\r\n";
           $out .= "Cookie: $cookie\r\n\r\n";
           $out .= $post;
       } else {
           $out = "GET $path HTTP/1.0\r\n";
           $out .= "Accept: */*\r\n";
           //$out .= "Referer: $boardurl\r\n";
           $out .= "Accept-Language: zh-cn\r\n";
           $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
           $out .= "Host: $host\r\n";
           $out .= "Connection: Close\r\n";
           $out .= "Cookie: $cookie\r\n\r\n";
       }
  
       $fp = fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);  
  
       if(!$fp) return ''; else {  
           $header = $content = '';  
  
           stream_set_blocking($fp, $block);  
           stream_set_timeout($fp, $timeout);  
           fwrite($fp, $out);  
           $status = stream_get_meta_data($fp);  
  
           if(!$status['timed_out']) {//未超时  
               while (!feof($fp)) {  
                   $header .= $h = fgets($fp);  
                   if($h && ($h == "\r\n" ||  $h == "\n")) break;  
               }  
  
               $stop = false;  
               while(!feof($fp) && !$stop) {  
                   $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));  
                   $content .= $data;  
                   if($limit) {  
                       $limit -= strlen($data);  
                       $stop = $limit <= 0;  
                   }  
               }  
           }  
        fclose($fp);  
  
           return $returnHeader ? array($header,$content) : $content;  
       }  
    }


    /**
     * 模拟post提交
     * @author wjh
     * @param $url
     * @param array $data
     * @param string $returnType json or string ,default json
     * @return string
     */
    public static function curl_post_sessionid($url=null,$data=array()){
        if (empty($url))
            $url = BDataHelper::getHltConfig('api')."/j_spring_security_token_check?u=admin";

        $result = self::curl_post($url,$data,'string',1);
        preg_match('/Set-Cookie:(.*);/iU',$result,$str);
        $sessionid = (!empty($str) && count($str) > 1) ? trim($str[1]) : '';
        return $sessionid;
    }

    /**
     * 模拟post提交
     * @author wjh
     * @param $url
     * @param array $data
     * @param string $returnType json or string ,default json
     * @return string
     */
    public static function curl_post($url,$data,$returnType='json',$header=0,$cookie=null,$method=null){
        $ch = curl_init();
        if (!empty($cookie))
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);//JSESSIONID=5649CCE3FA2B78DF08308516AC1A5FEB

        curl_setopt($ch, CURLOPT_TIMEOUT,60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888'); //设置代理服务器
        curl_setopt($ch, CURLOPT_HEADER,$header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        if (empty($method))
            $method = 'POST';

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        $data = http_build_query($data); //wjh 20150407 由于处理文件上传，取消此功能

        $headerSet = array('Content-Type: multipart/form-data');
        //curl_setopt($ch, CURLOPT_HTTPHEADER,$headerSet);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output=curl_exec($ch);
        curl_close($ch);

        //output
        if ($returnType == 'json')
            return json_decode($output,true);
        elseif($returnType == 'string')
            return trim($output);
        else
            return trim($output);
    }


    /**
     * 模拟post提交
     * @author wjh
     * @param $url
     * @param array $data
     * @param string $returnType json or string ,default json
     * @return string
     */
    public static function curl_get($url,$data,$returnType='json',$header=0,$cookie=null){
        $ch = curl_init();
        if (!empty($cookie))
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);//JSESSIONID=5649CCE3FA2B78DF08308516AC1A5FEB

        curl_setopt($ch, CURLOPT_TIMEOUT,60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
        //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888'); //设置代理服务器
        curl_setopt($ch, CURLOPT_HEADER,$header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //$data = http_build_query($data); wjh 20150407 由于处理文件上传，取消此功能
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output=curl_exec($ch);
        curl_close($ch);

        //output
        if ($returnType == 'json')
            return json_decode($output,true);
        elseif($returnType == 'string')
            return trim($output);
        else
            return trim($output);
    }

}
?>
