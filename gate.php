<?php
require_once __DIR__.'/aligenies_request.php';
require_once __DIR__.'/server.php';

$request = OAuth2_Request::createFromGlobals();
$response = new OAuth2_Response();
// Handle a request to a resource and authenticate the access token
// #if (!$server->verifyResourceRequest($request,$response)) {
// #       $server->getResponse()->send();
// #       error_log('die');
// #       die;
// #}
// //-------



$chars = md5(uniqid(mt_rand(), true));
$uuid = '';
$uuid = substr($chars,0,8) . '-';
$uuid .= substr($chars,8,4) . '-';
$uuid .= substr($chars,12,4) . '-';
$uuid .= substr($chars,16,4) . '-';
$uuid .= substr($chars,20,12);
$messageId = $uuid;

$poststr = file_get_contents("php://input");
$obj = json_decode($poststr);

$user_id = getUseridFromAccesstoken($obj->payload->accessToken);
error_log($obj->payload->accessToken);
#error_log($user);
if ($user=== 0)
{
error_log('-------------');
	die;
}
error_log($user_id);

error_log('-------');
error_log('----get-request---');
error_log($poststr);

switch($obj->header->namespace)
{
case 'AliGenie.Iot.Device.Discovery':
	$data=array();
	$stm = getDeviceList($user_id);
	while($row = $stm->fetch(PDO::FETCH_ASSOC)){
		        array_push($data,json_decode($row['jsonData'], true));
	}
	$Discovery=json_encode($data);
	$str='{
		header: 
		{
			namespace: "AliGenie.Iot.Device.Discovery", 
			name: "DiscoveryDevicesResponse", 
			messageId: "%s", 
			payLoadVersion: 1
		}, 
		payload: {
			devices:
				'.$Discovery.'

			}
		}';
#'.$Discovery.'
	$resultStr = sprintf($str,$messageId);
	break;

case 'AliGenie.Iot.Device.Control':
	$result = Device_control($obj);
	if($result->result == "True" )
	{
		$str='{
  			"header":{
  			    "namespace":"AliGenie.Iot.Device.Control",
  			    "name":"%s",
  			    "messageId":"%s",
 			     "payLoadVersion":1
			   },
			   "payload":{
			      "deviceId":"%s"
			    }
			}';
		$resultStr = sprintf($str,$result->name,$messageId,$result->deviceId);
		//error_log($resultStr);
		
	}
	else
	{
		$str='{
			  "header":{
			      "namespace":"AliGenie.Iot.Device.Control",
			      "name":"%s",
			      "messageId":"%s",
			      "payLoadVersion":1
			   },
			   "payload":{
			        "deviceId":"%s",
			         "errorCode":"%s",
			         "message":"%s"
			    }
			}';
		$resultStr = sprintf($str,$result->name,$messageId,$result->deviceId,$result->errorCode,$result->message);
	}
	break;
case 'AliGenie.Iot.Device.Query':
	$result = Device_status($obj);
	$properties = json_encode($result->powerstate);
	if($result->result == "True" )
	{
		$str='{
  	  			"header":{
  	  			    "namespace":"AliGenie.Iot.Device.Query",
  	  			    "name":"%s",
  	  			    "messageId":"%s",
 	 			     "payLoadVersion":1
				   },
				   "payload":{
				      "deviceId":"%s"
            			},
				   "properties":%s
			}';
		$resultStr = sprintf($str,$result->name,$messageId,$result->deviceId,$properties);
	}
#		if(strpos($result->deviceId,"temperature"))
#	{
#		
#		$str='{
#  			"header":{
#  			    "namespace":"AliGenie.Iot.Device.Query",
#  			    "name":"%s",
#  			    "messageId":"%s",
# 			     "payLoadVersion":1
#			   },
#			   "payload":{
#			      "deviceId":"%s"
#                       },
#			   "properties":[
#			    {
#	   	              "name":"temperature",
#	   	              "value":"%s"
#		            }
#	                    ]
#
#			}';
#			$result->name="QueryTemperatureResponse";
#	}
#	 elseif(strpos($result->deviceId,"illumination"))
#	{
#		
#		$str='{
#  			"header":{
#  			    "namespace":"AliGenie.Iot.Device.Query",
#  			    "name":"%s",
#  			    "messageId":"%s",
# 			     "payLoadVersion":1
#			   },
#			   "payload":{
#			      "deviceId":"%s"
#                       },
#			   "properties":[
#			    {
#	   	              "name":"brightness",
#	   	              "value":"%s"
#		            }
#	                    ]
#
#			}';
#			$result->name="QueryIlluminationResponse";
#	}
#	 elseif(strpos($result->deviceId,"pm25"))
#	{
#		
#		$str='{
#  			"header":{
#  			    "namespace":"AliGenie.Iot.Device.Query",
#  			    "name":"%s",
#  			    "messageId":"%s",
# 			     "payLoadVersion":1
#			   },
#			   "payload":{
#			      "deviceId":"%s"
#                       },
#			   "properties":[
#			    {
#	   	              "name":"pm2.5",
#	   	              "value":"%s"
#		            }
#	                    ]
#
#			}';
#			$result->name="QueryHumidityResponse";
#	}
#	 elseif(strpos($result->deviceId,"humidity"))
#	{
#		
#		$str='{
#  			"header":{
#  			    "namespace":"AliGenie.Iot.Device.Query",
#  			    "name":"%s",
#  			    "messageId":"%s",
# 			     "payLoadVersion":1
#			   },
#			   "payload":{
#			      "deviceId":"%s"
#                       },
#			   "properties":[
#			    {
#	   	              "name":"humidity",
#	   	              "value":"%s"
#		            }
#	                    ]
#
#			}';
#			$result->name="QueryHumidityResponse";
#	}
#	elseif(strpos($result->deviceId,"Sensor"))
#
#	{
#		
#		$str='{
#  			"header":{
#  			    "namespace":"AliGenie.Iot.Device.Query",
#  			    "name":"%s",
#  			    "messageId":"%s",
# 			     "payLoadVersion":1
#			   },
#			   "payload":{
#			      "deviceId":"%s"
#                       },
#			   "properties":%s
#			}';
#			$result->name="QueryResponse";
#	}
#	else
#	{
#		
#		$str='{
#  			"header":{
#  			    "namespace":"AliGenie.Iot.Device.Query",
#  			    "name":"%s",
#  			    "messageId":"%s",
# 			     "payLoadVersion":1
#			   },
#			   "payload":{
#			      "deviceId":"%s"
#                       },
#			   "properties":[
#			    {
#	   	              "name":"powerstate",
#	   	              "value":"%s"
#		            }
#	                    ]
#
#			}';
#	}	
#	
#	$resultStr = sprintf($str,$result->name,$messageId,$result->deviceId,$result->powerstate);
#	
#}
	else
	{
		$str='{
			  "header":{
			      "namespace":"AliGenie.Iot.Device.Control",
			      "name":"%s",
			      "messageId":"%s",
			      "payLoadVersion":1
			   },
			   "payload":{
			        "deviceId":"%s",
			         "errorCode":"%s",
			         "message":"%s"
			    }
			}';
		$resultStr = sprintf($str,$result->name,$messageId,$result->deviceId,$result->errorCode,$result->message);
	}
	break;
default:
	$resultStr='Nothing return,there is an error~!!';	
}

error_log('----reseponse---');
error_log($resultStr);
echo($resultStr);
?>
