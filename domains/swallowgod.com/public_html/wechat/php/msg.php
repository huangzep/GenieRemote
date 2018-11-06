<?php



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
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0<FuncFlag>
            </xml>";
            if(!empty( $keyword )){
                if($keyword == 1){
                    $contentStr = '数字1';
                }elseif($keyword == 2){
                    $contentStr = '数字2';
                }else{
                    $contentStr = '<a href="http://www.baidu.com">hello welcome to</a>';
                }
                $msgType = "text";

                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
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