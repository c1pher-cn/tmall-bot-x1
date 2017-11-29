<?php
require_once __DIR__.'/aligenies_request.php';
$chars = md5(uniqid(mt_rand(), true));
$uuid  = substr($chars,0,8) . '-';
$uuid .= substr($chars,8,4) . '-';
$uuid .= substr($chars,12,4) . '-';
$uuid .= substr($chars,16,4) . '-';
$uuid .= substr($chars,20,12);

$poststr = file_get_contents("php://input");
$obj = json_decode($poststr);
$messageId = $uuid;

switch($obj->header->namespace)
{
case 'AliGenie.Iot.Device.Discovery':

	$str='{
  header: {
    namespace: "AliGenie.Iot.Device.Discovery",
    name: "DiscoveryDevicesResponse",
    messageId: "%s",
    payLoadVersion: 1
  },
  payload: {
    devices: [
      {
        deviceId: "sensor.temperature_158d0001712cbf",
        deviceName: "空气监测仪",
        deviceType: "sensor",
        zone: "阳台",
        brand: "homeassistant",
        model: "小米温湿度传感器",
        icon: "https://home-assistant.io/demo/favicon-192x192.png",
        properties: [
          {
		"name":"temperature",
		"value":"27"
          }
        ],
        actions: [
		"Query",
		"QueryTemperature",
		"QueryHumidity",
		"QueryPowerState"
        ],
        extension: {
          link: "https://www.baidu.com"
        }
},
{
        deviceId: "switch.plug_158d000163ae00",
        deviceName: "插座",
        deviceType: "switch",
        zone: "阳台",
        brand: "homeassistant",
        model: "小米网关",
        icon: "",
        properties: [
          {
            status: "off"
          }
        ],
        actions: [
          "TurnOn",
          "TurnOff",
          "Query",
	  "QueryPowerState"
        ],
        extension: {
          link: "https://www.baidu.com"
        }
      },
      {
        deviceId: "light.gateway_light_34ce008dc8c3",
        deviceName: "落地灯",
        deviceType: "light",
        zone: "客厅",
        brand: "homeassistant",
        model: "小米网关",
        icon: "https://home-assistant.io/demo/favicon-192x192.png",
        properties: [
          {
            status: "off"
          }
        ],
        actions: [
          "TurnOn",
	  "TurnOff",
	  "QueryBrightness",
	  "QueryPowerState",
	  "QueryColor",
          "Query"
        ],
        extension: {
          link: "https://www.baidu.com"
        }
      },
      {
        deviceId: "media_player.sony_bravia_tv",
        deviceName: "电视",
        deviceType: "television",
        zone: "客厅",
        brand: "homeassistant",
        model: "Sony Tv",
        icon: "https://home-assistant.io/demo/favicon-192x192.png",
        properties: [
          {
            status: "off"
          }
        ],
        actions: [
          "TurnOn",
          "TurnOff",
          "Query",
	  "QueryPowerState",
          "SelectChannel",
          "AdjustUpChannel",
          "AdjustDownChannel",
          "AdjustUpVolume",
          "AdjustDownVolume",
          "SetVolume",
          "SetMute",
          "CancelMute",
          "Play",
          "Pause",
          "Continue",
          "Next",
          "Previous"
        ],
        extension: {
          link: "https://www.baidu.com"
        }
      }
    ]
  }
}';
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
                           },
			   "properties":[
			    {
	   	              "name":"powerstate",
	   	              "value":"%s"
		            }
	                    ]

			}';
		$resultStr = sprintf($str,$result->name,$messageId,$result->deviceId,$result->powerstate);

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
default:
	$resultStr='Nothing return,there is an error~!!';
}
error_log('-------');
error_log('----get-request---');
error_log($poststr);
error_log('----reseponse---');
error_log($resultStr);
echo($resultStr);
?>
