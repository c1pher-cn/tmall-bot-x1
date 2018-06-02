<?php
session_start();



if(!empty($_SESSION['userinfo']))
{
	header("Location: /information.php");
}
$_SESSION['userurl'] = $_SERVER['REQUEST_URI'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
   <link rel="stylesheet" href="weui/style/weuix.min.css"/>
        <title>
            HomeAssistant接入
        </title>
</head>

<body>
<div class="page-bd">
	<div class="weui_cells_title">请点击登陆</div>
        <div class="weui_btn_area">
		<a href="<?php echo '/callback.php'?>" target="_parent" class="weui_btn weui_btn bg-blue">登陆</a>
        </div>
</div>
    

</body>
</html>
