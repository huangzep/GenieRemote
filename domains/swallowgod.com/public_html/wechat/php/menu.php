<?php
/**
 *
  * wechat php
  */

//define your token
define("TOKEN", "weixin");
file_put_contents('./test_weixin.log','0000'."\r\n",FILE_APPEND);
$wechatObj = new wechatCallbackapiTest1();
$wechatObj->valid();

//微信签名验证
class wechatCallbackapiTest1
{
    public function valid()
    {
      try {
        $echoStr = $_GET["nonce"];
        file_put_contents('./test_weixin.log',$echoStr."\r\n",FILE_APPEND);
        if($this->checkSignature()){ //验证成功后，返回$echoStr字符串给微信处理
            file_put_contents('./test_weixin.log','01111'."\r\n",FILE_APPEND);
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

              
              
               
                if ($keyword == 221) {
                  $contentStr = "你英语"."\r\n"."微信号"."哪一个";
                  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
                  echo $resultStr;
                } 
               
                if ($keyword == 222) {
                  $contentStr = "你英语"."\r\n"."微信号"."哪一个";
                  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
                  echo $resultStr;
                } 
               
                if ($keyword == 223) {
                  $contentStr = "你英语"."\r\n"."微信号"."哪一个";
                  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
                  echo $resultStr;
                } 
              
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
                    $contentStr = '欢迎主人关注玉燕小灯神
                    回复1或者许愿，就可以进入灯灯的许愿通道
                    回复2或者悄悄话，让灯灯来倾听你的故事吧~~';
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
                    echo $resultStr;
                }
              
              
                
            }else{
                echo '请主人重新输入';
            }
        }else {
            echo '主人，咋不说哈呢';
        }
    }
}

//生成自定义菜单
file_put_contents('./test_weixin.log','02220'."\r\n",FILE_APPEND);
$wechatMenu = new wechatCallbackMenu();
$wechatMenu->createMenu();
class wechatCallbackMenu{
    /*获取accessToken
    *return , 返回access_token
    **/
    public function getWxAccessToken(){
        //这里使用session来暂时保存access_token，可以使用mysql数据库来保存数据
        // if( isset($_SESSION['access_token']) && $_SESSION['access_token'] && ($_SESSION['expires_in']-time()>0) ){
        //     //如果缓存中已经存在了access_token，并且没有过期，可以直接取用就行
        //     return $_SESSION['access_token'];
        // }else{
            //如果不存在access_token或者已经过期了，就生成一个
            file_put_contents('./test_weixin.log','Q2'."\r\n",FILE_APPEND);
            $appId='wxc664dbcefe7d41c5';
            $appSecret='c23e8610a222bf6fc6374bd02eb62544';
            $url ='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;
            $arr = $this->http_curl($url,'get','json');
            //这里获取成功以后，需要更新一下$_SESSION['access_token']和$_SESSION['expires_in']里面的数据
            $access_token = $arr['access_token'];
            $_SESSION['access_token'] = $access_token;
            $_SESSION['expires_in']   = time() + 7200 ;
            file_put_contents('./test_weixin.log','Q3'.$access_token."\r\n",FILE_APPEND);
            return $arr['access_token'];
        // }
        
    }
    /*
    *网页采集工具，主要是用来执行一些功能
    *@parm: $url  , string , 网页或者接口地址
    *@parm: $type , string , 是post请求还是get请求，默认的话是get请求 
    *@parm: $res  , string , 网页返回的是什么形式的数据
    *@parm: $arr  , array  , 发送post请求的时候携带的一些参数
    *return: 返回一个数组类型的数据
    **/
    public function http_curl($url,$type='get',$res='json',$arr=''){
        file_put_contents('./test_weixin.log','Q4'."\r\n",FILE_APPEND);
    	//1.初始化
    	$ch = curl_init();
    	//2.设置参数
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if( $type == 'post' ){
            //如果是post请求的话,设置post的一些参数
            curl_setopt($ch , CURLOPT_POST , 1);
            curl_setopt($ch , CURLOPT_POSTFIELDS, $arr);
        }
    	//3.执行
        $result = curl_exec($ch);
        file_put_contents('./test_weixin.log','Q7'.$result."\r\n",FILE_APPEND);
    	//4.关闭
    	curl_close($ch);
    	if( curl_errno($ch)){
    		//打印错误日志
    		var_dump(curl_error($ch));
    	}
        if( $res == 'json' ){
            //将json转化成数组的形式
            $result = json_decode($result , TRUE);
        }
        file_put_contents('./test_weixin.log','Q5'.$result."\r\n",FILE_APPEND);
    	return $result;
    }
    public function createMenu(){
        file_put_contents('./test_weixin.log','Q1'."\r\n",FILE_APPEND);
        $access_token = $this->getWxAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        file_put_contents('./test_weixin.log',$access_token."\r\n",FILE_APPEND);
        //拼装要生成的菜单
        $array = array(
            'button'=>array(
                //第一个一级菜单
                array(
                    'type'=>'click',
                    'name'=>urlencode('菜单一'),
                    'key' =>'Item1'
                    )
                )
            );
        //转化成json的格式
        $arrayJson = urldecode(json_encode( $array ));
        $res = $this->http_curl($url,'post','json',$arrayJson);
        file_put_contents('./test_weixin.log','Q9'.$res."\r\n",FILE_APPEND);
    }
}

?>