<?php
require_once __DIR__.'/server.php';

#$_POST['grant_type']=$_GET['grant_type'];
#$_POST['code']=$_GET['code'];
#$_POST['redirect_uri']=$_GET['redirect_uri'];
#$_POST['client_id']=$_GET['client_id'];
#$_POST['client_secret']=$_GET['client_secret'];
#error_log($_POST['grant_type']);
#error_log($_POST['client_id']);
#error_log($_POST['client_secret']);
#error_log($_POST['code']);
#error_log($_POST['redirect_uri']);

// Handle a request for an OAuth2.0 Access Token and send the response to the client
$server = new OAuth2_Server($storage,array('access_lifetime'=>86400));
$server->addGrantType(new OAuth2_GrantType_AuthorizationCode($storage));
$server->addGrantType(new OAuth2_GrantType_RefreshToken($storage,array('always_issue_new_refresh_token' => true)));
$server->handleTokenRequest(OAuth2_Request::createFromGlobals(), new OAuth2_Response())->send();

?>
