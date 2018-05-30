<?php
        session_start();
	$_SESSION['userinfo'] = array(
		'user_name' => '测试用户',
		'email' => 'xxx',
		'user_id' => 'c1pher001'.$uid,
		'from' => 'c1pher-git'
	);
	// 注意至少要带上一个链接。
        if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
                echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
        } else {
                echo "<p>发送成功</p>";
        }

	if ($_SESSION['userurl']!='null'){
		header("Location: ".$_SESSION['userurl']);
		$_SESSION['userurl']='null';
	}else
	{  
		header("Location: /information.php");
	}

?>
