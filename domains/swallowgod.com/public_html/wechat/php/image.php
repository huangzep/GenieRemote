<?php 
define("AppID","wxc664dbcefe7d41c5");//你的id 
define("AppSecret", "c23e8610a222bf6fc6374bd02eb62544");//你的secret

/*  上传临时文件 */
$a = "0";
if($a == "1"){
$type = "image";
$filepath = dirname(__FILE__)."\logo.png"; 
$filedata = array("file1"  => "@".$filepath);
$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".token()."&type=".$type;
$result = https_request($url, $filedata);
$p = json_decode($result);
echo "media_id:".$p->media_id;
}


/*  获取临时的文件  */
$b = "1";
if($b == "1"){
$id = "Zary6julqwRBBuSgzFbiMCSjYmG2930UvzjrbnHN4nyT3YGZVD8H-ecfoReGT1Qr";
$url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".token()."&media_id=".$id;
$arr = downloadWeixinFile($url);
saveWeixinFile("1.jpg",$arr['body']);

}


function downloadWeixinFile($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);    
    curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $package = curl_exec($ch);
    $httpinfo = curl_getinfo($ch);
    curl_close($ch);
    $imageAll = array_merge(array('header' => $httpinfo), array('body' => $package)); 
    return $imageAll;
}


function saveWeixinFile($filename, $filecontent)
{
    $local_file = fopen($filename, 'w');
    if (false !== $local_file){
        if (false !== fwrite($local_file, $filecontent)) {
            fclose($local_file);
        }
    }
}



function token(){
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".AppID."&secret=".AppSecret;
    $data = json_decode(file_get_contents($url),true);
if($data['access_token']){
return $data['access_token'];
    }else{
        echo "Error";
   exit();
    }
}
?>