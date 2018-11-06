<?php
/**
 *
  * wechat php
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest1();
$wechatObj->valid();

//微信签名验证
class wechatCallbackapiTest1
{
    public function valid()
    {
      try {
        $echoStr = $_GET["nonce"];
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
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

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

            if (!empty( $keyword )) {
              
              
               
                if ($keyword == 051) {
                  $contentStr = "你英语"."\r\n"."微信号"."哪一个";
                  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                  echo $resultStr;
                } 
               
                if ($keyword == 052) {
                  $contentStr = "你英语"."\r\n"."微信号"."哪一个";
                  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                  echo $resultStr;
                } 
               
                if ($keyword == 053) {
                  $contentStr = "你英语"."\r\n"."微信号"."哪一个";
                  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                  echo $resultStr;
                } 
              
              
              //回复1 许愿
              if ($keyword == 1 || $keyword == "许愿") {
                $contentStr = '<a href="http://www.baidu.com">请点击这里许愿哦~</a>';;
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
                echo $resultStr;
              } 
              //回复2 悄悄话 
              if ($keyword == 2 || $keyword == "悄悄话") {
                $contentStr = '你的心事，我来倾听'."\n".'<a href="http://www.baidu.com">请点击这里发布悄悄话哦~</a>';;
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
                echo $resultStr;
              } 
              if ($keyword) {
                $contentStr = '主人，我在等待您的降临！'."\n".'回复1或者许愿，就可以进入灯灯的许愿通道'."\n".'回复2或者悄悄话，让灯灯来倾听你的故事吧~~';
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
                echo $resultStr;
            }

            }else{
                echo '请主人重新输入';
            }
        }else {
            echo '主人，咋不说哈呢';
            exit;
        }
    }
}

// $nonce = $_GET['nonce'];
// $token = 'imooc';
// $timestamp = $_GET['timestamp'];
// if (isset($_GET['echostr'])) {
//     if (bindServerCheck()) {
//         echo $_GET['echostr'];
//     }
//     exit();
// }
responseMsg();

 //消息回复
function responseMsg() {
    //1.获取到微信推送过来post数据（xml格式）
    file_put_contents('./test_weixin.log','Q1'."\r\n",FILE_APPEND);
    $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
    file_put_contents('./test_weixin.log','Q2'."\r\n".$postArr,FILE_APPEND);
    //2.处理消息类型，并设置回复类型和内容
    $postObj = simplexml_load_string($postArr, 'SimpleXMLElement', LIBXML_NOCDATA);
    file_put_contents('./test_weixin.log','Q2'."\r\n".$postObj,FILE_APPEND);
    //判断该数据包是否是订阅de事件推送
    if (strtolower($postObj->MsgType) == 'event') {
        //如果是关注 subscribe事件
        if (strtolower($postObj->Event) == 'subscribe') {
            $toUser = $postObj->FromUserName;
            $fromUser = $postObj->ToUserName;
            $time = time();
            $msgType = 'text';
            $content = '欢迎关注我的微信公众号！';
            $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
            $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
            echo $info;
        }
    }
}

// 开发者模式绑定校验
function bindServerCheck($token) {
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
    $tmpArr = array(
        $token,
        $timestamp,
        $nonce
    );
    sort($tmpArr);
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);
    if ($tmpStr == $signature) {
        return true;
    } else {
        return false;
    }
}

?>