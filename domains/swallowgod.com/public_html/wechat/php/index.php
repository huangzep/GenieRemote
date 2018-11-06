<?php
/**
 *
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest1();
$wechatObj->valid();
//file_put_contents('./test_weixin1.log',json_encode($_GET)."\r\n",FILE_APPEND);
//$postStr = file_get_contents("php://input", 'r');
//file_put_contents('./test_weixin2.log',$postStr."\r\n",FILE_APPEND);
//{"signature":"e767ea6a765811c1e9e7981318b6afb54116f934","timestamp":"1540108338","nonce":"1522853278","openid":"o-p__0jTp2iFk_gvAGc0oY2Dxq-E"}
//http://swallowgod.com/wechat/index.php?signature=e767ea6a765811c1e9e7981318b6afb54116f934&timestamp=1540108338&nonce=1522853278

//微信签名验证
class wechatCallbackapiTest1
{
    public function valid()
    {
      try {
        $echoStr = $_GET["nonce"];

        //valid signature , option
        if($this->checkSignature()){ //验证成功后，返回$echoStr字符串给微信处理
           echo $echoStr;
        } else {
          echo 'hello world'; 
        }
      } catch(Exception $e){
        //echo $e->getMessage();
      }
    }

    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        //  var_dump($tmpArr);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
       // var_dump($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

// die;

//被动消息回复
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();
class wechatCallbackapiTest{
    public function responseMsg(){
        $postStr = file_get_contents("php://input", 'r');
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0<FuncFlag>
            </xml>";
            $imgTpl = "<xml>  
            <ToUserName><![CDATA[%s]]></ToUserName>  
            <FromUserName><![CDATA[%s]]></FromUserName>  
            <CreateTime>%s</CreateTime>  
            <MsgType><![CDATA[image]]></MsgType>  
            <Image>  
                <MediaId><![CDATA[%s]]></MediaId>  
            </Image>  
            </xml>";  

            if(!empty( $keyword )) {
                // $msgType = "text";
                // if($keyword == 1) {
                //     $contentStr = '数字1';
                // } elseif($keyword == 2){
                //     $contentStr = '数字2';
                // } elseif($keyword == 3) {
                //     $contentStr = '数字2';
                //     // $mediaId = "CF6_Yptwksy6HDKt12GX5ivzAv6p61YbFMGtcGxQB_GlxRAFp8TAxT3eZRmrkoY_";
                //     // $msgType = "image";
                //     // $resultStr = sprintf($imgTpl, $fromUsername, $toUsername, $time, $msgType, $mediaId);
                //     // echo $resultStr;
                // }
                // else{
                //     $contentStr = '<a href="http://www.baidu.com">hello welcome to</a>';
                // }

                // $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                // //file_put_contents('./test_weixin3.log',$resultStr."\r\n",FILE_APPEND);
                // echo $resultStr;

                file_put_contents('./test_weixin3.log',$keyword."\r\n",FILE_APPEND);
                   $mediaId = "YBBTlm5SnLbX4oXLcfkwV08YBBb-2N_kOPH48TKg697BMcHIuIfMGWG6LNJhayHu";
                    $resultStr = sprintf($imgTpl, $fromUsername, $toUsername, $time, $mediaId);
                    file_put_contents('./test_weixin3.log',$resultStr."\r\n",FILE_APPEND);
                    echo $resultStr;
            }else{
                echo '请输入1/2，系统会自动回复';
            }
        }else {
            echo '咋不说哈呢';
            exit;
        }
    }
}

?>