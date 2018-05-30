<?php
require_once __DIR__.'/homeassistant_conf.php';

function my_db(){
$dsn = DBNAME;
$user = DBUSER;
$pwd = DBPASS;
$db = new PDO($dsn, $user, $pwd);
return $db;
}
function getUseridFromAccesstoken($token)
{
        $db = my_db();
        $stm = $db->prepare("select * from oauth_access_tokens where access_token=:token");
        $stm->bindParam(":token",$token,PDO::PARAM_STR);
	$stm->execute();
	if($stm->rowCount() === 0)
	{
		return 0;
	}
	$result = $stm->fetch(PDO::FETCH_ASSOC);
	return $result['user_id'];

}
function getDevice($user_id,$deviceId)
{
        $db = my_db();
	$stm = $db->prepare("select * from oauth_devices where user_id=:user_id and deviceId=:deviceId");
	$stm->bindParam(":user_id",$user_id,PDO::PARAM_STR);
	$stm->bindParam(":deviceId",$deviceId,PDO::PARAM_STR);
        $stm->execute();
	return $stm;
}
function getDeviceList($user_id)
{
        $db = my_db();
        $stm = $db->prepare("select * from oauth_devices where user_id=:user_id");
        $stm->bindParam(":user_id",$user_id,PDO::PARAM_STR);
        $stm->execute();
	return $stm;
	#$result = $stm->fetch(PDO::FETCH_ASSOC);
	#return $result;
}	
function existDevice($user_id,$deviceId)
{
        $db = my_db();
	$stm = $db->prepare("select * from oauth_devices where user_id=:user_id and deviceId=:deviceId");
	$stm->bindParam(":user_id",$user_id,PDO::PARAM_STR);
	$stm->bindParam(":deviceId",$deviceId,PDO::PARAM_STR);
	$stm->execute();
	return $stm->rowCount();

}	
function deleteDevice($user_id,$deviceId)
{
        $db = my_db();
	$stm = $db->prepare("delete from oauth_devices where user_id=:user_id and deviceId=:deviceId");
	$stm->bindParam(":user_id",$user_id,PDO::PARAM_STR);
	$stm->bindParam(":deviceId",$deviceId,PDO::PARAM_STR);
	$stm->execute();
	return $stm;

}	
function updateDevice($user_id,$deviceName,$deviceId,$jsonData,$virtual,$devices)
{
        $db = my_db();
        $stm = $db->prepare("update oauth_devices set (deviceName=:deviceName,jsonData=:jsonData,virtual=:virtual,devices=:devices)  where user_id=:user_id and deviceId=:deviceId)");
        $stm->bindParam(":user_id",$user_id,PDO::PARAM_STR);
        $stm->bindParam(":deviceName",$deviceName,PDO::PARAM_STR);
        $stm->bindParam(":deviceId",$deviceId,PDO::PARAM_STR);
        $stm->bindParam(":jsonData",$jsonData,PDO::PARAM_STR);
	$stm->bindParam(":virtual",$virtual,PDO::PARAM_STR);
        $stm->bindParam(":devices",$devices,PDO::PARAM_STR);
        $stm->execute();
	return $stm;
	#$result = $stm->fetch(PDO::FETCH_ASSOC);
        #error_log($result);
        #if ($stm->rowCount()>0)
        #{
        #        return true;
        #}
        #else{
        #        return false;
        #}

}
function insertDevice($user_id,$deviceName,$deviceId,$jsonData,$virtual,$devices)
{
        $db = my_db();
        $stm = $db->prepare("insert into oauth_devices (user_id,deviceName,deviceId,jsonData,virtual,devices)  values(:user_id,:deviceName,:deviceId,:jsonData,:virtual,:devices)");
        $stm->bindParam(":user_id",$user_id,PDO::PARAM_STR);
        $stm->bindParam(":deviceName",$deviceName,PDO::PARAM_STR);
        $stm->bindParam(":deviceId",$deviceId,PDO::PARAM_STR);
        $stm->bindParam(":jsonData",$jsonData,PDO::PARAM_STR);
        $stm->bindParam(":virtual",$virtual,PDO::PARAM_STR);
        $stm->bindParam(":devices",$devices,PDO::PARAM_STR);	
	$stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
	if ($stm->rowCount()>0)
	{
		return true;
	}
	else{
		return false;
	}
}	
function userExist($userid)
{
	$db = my_db();
	$stm = $db->prepare("select * from user_data where user_id=:user_id");
	$stm->bindParam(":user_id",$userid,PDO::PARAM_STR);
	$stm->execute();
	$result = $stm->fetch(PDO::FETCH_ASSOC);
	#error_log($result);
	if ($stm->rowCount()>0)
	{
		return true;
	}
	else{
		return false;
	}

}
function getUserInformation($user_id)
{
	$db = my_db();
	$stm = $db->prepare("select * from user_data where user_id=:user_id");
	$stm->bindParam(":user_id",$user_id,PDO::PARAM_STR);
	$stm->execute();
	if ($stm->rowCount()==1)
	{
		$result = $stm->fetch(PDO::FETCH_ASSOC);
		#error_log($result);
		return $result;
	}
	else{
		$result = ARRAY (
			'homeassistantURL' => 'null',
			'homeassistantPASS' => 'null',
			'email' => 'null'
		);
		return $result;
	}

}
function updateUser($haurl,$hapass,$email){
	$db = my_db();
	#"insert into user_data(user_id, user_name,email,homeassistantURL,homeassistantPASS,expires,fromwhere) values('weibo-boyue','boyue','xx@xx','url','PASS',0,'weibo') on duplicate key  UPDATE email ='aaaaa',homeassistantURL = 'url', homeassistantPASS='PASS',expires=0,fromwhere='weibo';"
	$stm = $db->prepare("insert into user_data(user_id, user_name,email,homeassistantURL,homeassistantPASS,fromwhere) values(:user_id,:user_name,:email,:haurl,:hapass,:fromwhere) on duplicate key  UPDATE email=:email2,homeassistantURL=:haurl2, homeassistantPASS=:hapass2");
	#$stm = $db->prepare("INSERT INTO user_data where user_id=:user_id");
	$stm->bindParam(":user_id",$_SESSION['userinfo']['user_id'],PDO::PARAM_STR);
	$stm->bindParam(":user_name",$_SESSION['userinfo']['user_name'],PDO::PARAM_STR);
	$stm->bindParam(":fromwhere",$_SESSION['userinfo']['from'],PDO::PARAM_STR);
	$stm->bindParam(":haurl",$haurl,PDO::PARAM_STR);
	$stm->bindParam(":email",$email,PDO::PARAM_STR);
	$stm->bindParam(":hapass",$hapass,PDO::PARAM_STR);
	$stm->bindParam(":email2",$email,PDO::PARAM_STR);
	$stm->bindParam(":haurl2",$haurl,PDO::PARAM_STR);
	$stm->bindParam(":hapass2",$hapass,PDO::PARAM_STR);
	
	#$stm->debugDumpParams();
	return $stm->execute();

}
	
	
$dsn = DBNAME;
$user = DBUSER;
$pwd = DBPASS;
	
// error reporting (this is a demo, after all!)
ini_set('display_errors',1);error_reporting(E_ALL);

// Autoloading (composer is preferred, but for this example let's just do this)
require_once('oauth2-server-php/src/OAuth2/Autoloader.php');
OAuth2_Autoloader::register();

// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
$storage = new OAuth2_Storage_Pdo(array('dsn' => $dsn, 'username' => $user, 'password' => $pwd));

// Pass a storage object or array of storage objects to the OAuth2 server class
$server = new OAuth2_Server($storage);

// Add the "Client Credentials" grant type (it is the simplest of the grant types)
$server->addGrantType(new OAuth2_GrantType_ClientCredentials($storage));
?>
