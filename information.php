<?php
session_start();

include_once( 'server.php' );

error_log(empty($_SESSION));
if(empty($_SESSION)||empty($_SESSION['userinfo']))
{
	$_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
	header("Location: /index.php");
}


if(empty($_POST))
{
	error_log('POST为空');
	#chech input
	$user_id = $_SESSION['userinfo']['user_id'];
	$user_name = $_SESSION['userinfo']['user_name'];
	$fromwhere = $_SESSION['userinfo']['from'];
	$information = getUserInformation($user_id);
	if ($information['homeassistantURL']==null)
	{
		echo '新用户';
		$homeassistantURL = 'your homeassistant URL';
		$homeassistantPASS = 'your homeassistant PASSWORD';
		$email = 'your email';
	}
	$homeassistantURL = $information['homeassistantURL'];
	$homeassistantPASS = $information['homeassistantPASS'];
	$email = $information['email'];
	error_log($_SESSION['userinfo']['user_id']);
}
elseif(empty($_POST['homeassistantURL'])||empty($_POST['homeassistantPASS'])||empty($_POST['email']))
{
	error_log('2222222post 参数不完全');
	$user_id = $_SESSION['userinfo']['user_id'];
	$user_name = $_SESSION['userinfo']['user_name'];
	$fromwhere = $_SESSION['userinfo']['from'];
	$information = getUserInformation($user_id);
	if ($information['homeassistantURL']==null)
	{
		echo '新用户';
		$homeassistantURL = 'your homeassistant URL';
		$homeassistantPASS = 'your homeassistant PASSWORD';
		$email = 'your email';
	}
	$homeassistantURL = $information['homeassistantURL'];
	$homeassistantPASS = $information['homeassistantPASS'];
	$email = $information['email'];
	error_log($_SESSION['userinfo']['user_id']);
}
else
{
	error_log('post正常');
	$user_id = $_SESSION['userinfo']['user_id'];
	$user_name = $_SESSION['userinfo']['user_name'];
	$fromwhere = $_SESSION['userinfo']['from'];
	$homeassistantURL = $_POST['homeassistantURL'];
	$homeassistantPASS = $_POST['homeassistantPASS'];
	$email = $_POST['email'];
	$update_result = updateUser($homeassistantURL,$homeassistantPASS,$email);
	if ($update_result){
		error_log('update success');
		echo 'update success';
	}else{
		error_log('update false');
		echo 'update false？？';
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>HomeAssistant信息修改</title>
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
   <link rel="stylesheet" href="weui/style/weuix.min.css"/>
   </head>
<body ontouchstart  class="page-bg"> 
<div class="page-hd" >
    <h1 class="page-hd-title">
        <?php echo $_SESSION['userinfo']['from'].'用户:'.$_SESSION['userinfo']['user_name'].'您好！';?>
    </h1>
</div>
<?php
#echo '微博用户:'.$user_message['screen_name'].'你好！';
#echo '微博uid:'.$uid;
if(userExist($_SESSION['userinfo']['user_id'])){
?>
<div class="page-bd">
	<div class="weui_cells_title">记录已存在，是否更新ha信息</div>
	<form action="information.php" method="POST">
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
		<div class="weui_cells_title">您的联系邮箱:</div>
                <div class="weui_cell_bd weui_cell_primary">
			<input class="weui_input" name="email" type="text" v-model="email" value="<?php echo $email?>" placeholder="请输入联系邮箱"/>
		</div>
	    </div>
	    <div class="weui_cell">
                <div class="weui_cells_title">HomeAssistant地址(要求公网可访问):</div>
                <div class="weui_cell_bd weui_cell_primary">
			<input class="weui_input" name="homeassistantURL" type="text" v-model="homeassistantURL" value="<?php echo $homeassistantURL?>"  placeholder="请输入HomeAssistant地址"/>
		</div>
	    </div>
	    <div class="weui_cell">
                <div class="weui_cells_title">HomeAssistant密码:</div>
                <div class="weui_cell_bd weui_cell_primary">
			<input class="weui_input" name="homeassistantPASS" type="text" v-model="homeassistantPASS" value="<?php echo $homeassistantPASS?>" placeholder="请输入HomeAssistant密码"/>
		</div>
	    </div>
	    <div class="weui_btn_area">
	        <input type="submit" class="weui_btn weui_btn bg-blue" name="提交修改" value="确认修改">
 	    </div>
	</div>
	</form>
        <div class="weui_btn_area">
                 <a href="discovery.php" target="_parent" class="weui_btn weui_btn bg-blue">返回设备列表</a>
        </div>
</div>
<?php
}else{
?>	
<div class="page-bd">
	<div class="weui_cells_title">初次使用请注册下列信息:</div>
	<form action="information.php" method="POST">
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">您的联系邮箱:</label></div>
                <div class="weui_cell_bd weui_cell_primary">
			<input class="weui_input" name="email" type="text" v-model="email" placeholder="请输入联系邮箱"/>
		</div>
	    </div>
	    <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">HomeAssistant地址(要求公网可访问):</label></div>
                <div class="weui_cell_bd weui_cell_primary">
			<input class="weui_input" name="homeassistantURL" type="text" v-model="homeassistantURL" placeholder="请输入HomeAssistant地址"/>
		</div>
	    </div>
	    <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">HomeAssistant密码:</label></div>
                <div class="weui_cell_bd weui_cell_primary">
			<input class="weui_input" name="homeassistantPASS" type="text" v-model="homeassistantPASS" placeholder="请输入HomeAssistant密码"/>
		</div>
	    </div>
	    <div class="weui_btn_area">
	        <input type="submit" class="weui_btn weui_btn bg-blue" name="提交修改" value="确认提交">
 	    </div>
	</div>
	</form>

</p>
    </div>

<?php
}
?>
<script src="weui/zepto.min.js"></script>
<script src="weui/vue.js"></script>
<script src="weui/vue-resource.js"></script>
<script src="weui/select.js"></script>
<script src="weui/picker.js"></script>

<script>
$(function(){

    var msg = "<?php echo $msg; ?>";

    if(msg !=""){
    	$.alert(msg, "消息");
  	}


  $('#button').on('click', function(){
    var data = $('form').serialize();
    var content = JSON.stringify(data).replace(/"/gi, '').replace(/&/gi, '<br>');
    alert(content);
    $.post('information.php', data).error(function(err){
      console.log(err);
    });
  });
});
</script>
