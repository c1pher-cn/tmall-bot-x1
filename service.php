<?php
require_once __DIR__.'/server.php';
if(empty($_SESSION['userinfo'])){
        $_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
        echo "{\"code\" : \"v\",\"Msg\":\"v\"}";
	die;
}
$user_id = $_SESSION['userinfo']['user_id'];


$v = isset($_GET['v']) ? $_GET['v'] : "v";
if ($v=="v"){
	echo "{\"code\" : \"v\",\"Msg\":\"v\"}";
}elseif ($v=="add"){
    //echo "{\"zrs\" : \"1545\",\"xzwplqd\":\"561\",\"lqzb\":\"321\"}";
    //$list = getComments();
    //echo json_encode ($list);
    $deviceName=$_REQUEST['deviceName'];
    $deviceId=$_REQUEST['deviceId'];
    $jsonData=$_REQUEST['jsonData'];
	$virtual=$_REQUEST['virtual'];
        $devices=$_REQUEST['states'];
	if($virtual!="1"){
		$virtual="0";
	}
       	
    
    if(existDevice($user_id,$deviceId)){
	#已经存在，更新    
    
    	if(updateDevice($user_id,$deviceName,$deviceId,$jsonData,$virtual,$devices)){
        	echo "{\"code\" : \"ok\",\"Msg\":\"已存在，更新成功！\"}";
    	}
    	else{
    		echo "{\"code\" : \"err\",\"Msg\":\"已存在，更新失败！\"}";
    	}
    }
    else{
	#没有，新增
        if(insertDevice($user_id,$deviceName,$deviceId,$jsonData,$virtual,$devices))
	{
	    echo "{\"code\" : \"ok\",\"Msg\":\"增加成功！\"}";
	}
	else{
	    echo "{\"code\" : \"err\",\"Msg\":\"增加失败！\"}";
    	}
    }
    //echo "{\"deviceName\" : \"$deviceName\",\"deviceId\":\"$deviceId\",\"jsonData\":\"$count\"}";
}elseif ($v=="getList"){
	$stm = getDeviceList($user_id);
	$data=array();
	while($row = $stm->fetch(PDO::FETCH_ASSOC)){
	        $dataArray = json_decode($row['jsonData'], true);
		$dataArray = array_merge($dataArray,array("virtual"=>$row['virtual']));
		array_push($data,$dataArray);
	}	
	$a=array(
		"code"=>"ok",
    	"Msg"=>"获取成功！",
    	"data"=>$data
	);

	echo json_encode($a);
    //echo "{\"deviceName\" : \"$deviceName\",\"deviceId\":\"$deviceId\",\"jsonData\":\"$count\"}";
}
elseif ($v=="del"){
    
    $deviceId=$_REQUEST['deviceId'];
    $result = deleteDevice($user_id,$deviceId); 
    $stm = getDeviceList($user_id);
    $data=array();
    while($row = $stm->fetch(PDO::FETCH_ASSOC)){
       //array_push($data,json_decode($row['jsonData'], true));
        $dataArray = json_decode($row['jsonData'], true);
        $dataArray = array_merge($dataArray,array("virtual"=>$row['virtual']));
        array_push($data,$dataArray);
    }  
    
    $a=array(
        "code"=>"ok",
        "Msg"=>"删除成功！",
        "data"=>$data
    );

    if($result){
        echo json_encode($a);
    }else{
        echo "{\"code\" : \"ok\",\"Msg\":\"删除失败！\"}";
    } 
	

	echo json_encode($a);
    //echo "{\"deviceName\" : \"$deviceName\",\"deviceId\":\"$deviceId\",\"jsonData\":\"$count\"}";
}


elseif ($v=="getNotice"){//到我的服务器获取版本更新完善的消息，不会收集信息请放心使用！

	$str='{code: "ok",Msg: "获取成功",data: 
		{
			title: "天猫精灵设备管理",
			notice: "建议反馈请微博联系",
			link: "https://weibo.com/u/1147593092",
			updata: true,
			updataLink: "https://bbs.hassbian.com/thread-2982-1-1.html",
			logo: [
		{img: "https://bbs.hassbian.com/static/image/common/logo.png",link: "https://bbs.hassbian.com/thread-2982-1-1.html"},
		{img: "https://home-assistant.io/demo/favicon-192x192.png",link: "javascript:;"}]}}';
	echo $str;

}






function getdata($url){

    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 2); //设置整个网络请求最长执行时间为2秒
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1); //设置连接目标服务器1秒无响应时判断为超时
    //curl_setopt($curl, CURLOPT_HEADER, 1);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //执行命令
    $data = curl_exec($curl);
    $http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
    $errorCode =curl_errno($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    echo $http_code;
    echo $errorCode;
    return $data;
}
