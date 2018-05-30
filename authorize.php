<?php
session_start();
require_once __DIR__.'/server.php';

$request = OAuth2_Request::createFromGlobals();
$response = new OAuth2_Response();

// validate the authorize request
$m=$request->query['redirect_uri']; 
$temp = substr($m,0,strpos($m,"?"));
$request->query['redirect_uri']= $temp; 

#exit("$m||||$temp");

if (!$server->validateAuthorizeRequest($request, $response)) {
    $response->send();
    die;
}
// display an authorization form
if(empty($_SESSION)||empty($_SESSION['userinfo']))
{
	$_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
	header("Location: /index.php");
}
if (empty($_POST)) {
exit('
  <!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>天猫精灵授权页</title>
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
  <link rel="stylesheet" href="weui/style/weuix.min.css"/>
</head>
<body ontouchstart  class="page-bg"> 
  
  
  
  <form method="post">
<div class="weui_msg hide" id="msg1" style="display: block; opacity: 1;">
        <div class="weui_icon_area"><i class="weui_icon_msg weui_icon_info"></i></div>
        <div class="weui_text_area">
            <h2 class="weui_msg_title">天猫精灵授权确认</h2>
            <p class="weui_msg_desc">确定要授权天猫精灵获取设备信息吗？确认授权请点击”授权“，否则请点击”取消“</p>
        </div>
        <div class="weui_opr_area">
            <p class="weui_btn_area">
                
  					<input type="submit" class="weui_btn weui_btn bg-blue" name="authorized" value="yes">
  					<input type="submit" class="weui_btn weui_btn_warn" name="authorized" value="cancel"">
				
            </p>
        </div>
        <div class="weui_extra_area">
            
        </div>
    </div>

</form>


</body>
</html>
');

}
// print the authorization code if the user has authorized your client
$is_authorized = ($_POST['authorized'] === 'yes');
$server->handleAuthorizeRequest($request, $response, $is_authorized,$_SESSION['userinfo']['user_id']);
if ($is_authorized) {
  // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
  $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5);
  $return = urldecode($m)."&code=".$code; 
  header("Location: ".$return); 
  //header("Location: ".$m."&code=".$code); 
  #header("Location: ".$m); 
  exit("SUCCESS! Authorization Code: $code");
}
$response->send();


?>
