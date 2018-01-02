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
        deviceId: "sensor.temperature1", 
        deviceName: "传感器", 
        deviceType: "sensor", 
        zone: "主卧", 
        brand: "homeassistant", 
        model: "DIY温湿度PM传感器", 
        icon: "https://home-assistant.io/demo/favicon-192x192.png", 
        properties: [
          {
		"name":"pm2.5",
		"value":"27"
          },
          {
		"name":"humidity",
		"value":"27"
          },
          {
		"name":"temperature",
		"value":"27"
          }
        ], 
	actions: [
		"Query",
		"QueryHumidity",
		"QueryTemperature",
		"QueryPm2.5"
        ], 
        extension: {
          link: "https://www.baidu.com"
        }
    },
    {
	deviceId: "sensor.illumination_34ce008dc8c3", 
        deviceName: "传感器", 
        deviceType: "sensor", 
        zone: "餐厅", 
        brand: "homeassistant", 
        model: "小米网关光照传感器", 
        icon: "https://home-assistant.io/demo/favicon-192x192.png", 
        properties: [
          {
		"name":"illumination",
		"value":"27"
          }
        ], 
        actions: [
		"Query",
		"QueryIllumination"
        ], 
        extension: {
          link: "https://www.baidu.com"
        }
    },
    {
        deviceId: "sensor.temperature_158d0001712cbf", 
        deviceName: "传感器", 
        deviceType: "sensor", 
        zone: "阳台", 
        brand: "homeassistant", 
        model: "小米温湿度传感器", 
        icon: "https://home-assistant.io/demo/favicon-192x192.png", 
        properties: [
          {
		"name":"humidity",
		"value":"27"
          },
          {
		"name":"temperature",
		"value":"27"
          }
        ], 
        actions: [
		"QueryHumidity",
		"QueryTemperature"
        ], 
        extension: {
          link: "https://www.baidu.com"
        }
    },
    {
        deviceId: "light.yeelight_rgb_7811dc6817ed", 
        deviceName: "灯", 
        deviceType: "light", 
        zone: "餐厅", 
        brand: "homeassistant", 
        model: "yeelight", 
        icon: "https://home-assistant.io/demo/favicon-192x192.png", 
        properties: [
          {
            status: "off"
          }
        ], 
        actions: [
          "TurnOn", 
          "TurnOff", 
	  "SetBrightness",
	  "SetColor",
	  "QueryBrightness",
	  "QueryPowerState", 
	  "QueryColor", 
	  "QueryPowerState",
	  "Query"
        ], 
        extension: {
          link: "https://www.baidu.com"
	}
    },
    {
        deviceId: "switch.bookroom_key2_008ca89c_ch1", 
        deviceName: "灯", 
        deviceType: "light", 
        zone: "书房", 
        brand: "homeassistant", 
        model: "hassmart", 
        icon: "https://home-assistant.io/demo/favicon-192x192.png", 
        properties: [
          {
            status: "off"
          }
        ], 
        actions: [
          "TurnOn", 
          "TurnOff", 
	  "QueryPowerState"
        ], 
        extension: {
          link: "https://www.baidu.com"
	}
    },
    {
        deviceId: "switch.bookroom_key2_008ca89c_ch2", 
        deviceName: "灯", 
        deviceType: "light", 
        zone: "书房", 
        brand: "homeassistant", 
        model: "hassmart", 
        icon: "https://home-assistant.io/demo/favicon-192x192.png", 
        properties: [
          {
            status: "off"
          }
        ], 
        actions: [
          "TurnOn", 
          "TurnOff", 
	  "QueryPowerState"
        ], 
        extension: {
          link: "https://www.baidu.com"
	}
    },
    {
        deviceId: "switch.bathroom1_key2_008c4410_ch1", 
        deviceName: "灯", 
        deviceType: "light", 
        zone: "卫生间", 
        brand: "homeassistant", 
        model: "hassmart", 
        icon: "https://home-assistant.io/demo/favicon-192x192.png", 
        properties: [
          {
            status: "off"
          }
        ], 
        actions: [
          "TurnOn", 
          "TurnOff", 
	  "QueryPowerState"
        ], 
        extension: {
          link: "https://www.baidu.com"
	}
    },
    {
        deviceId: "switch.bathroom1_key2_008c4410_ch2", 
        deviceName: "灯", 
        deviceType: "light", 
        zone: "卫生间", 
        brand: "homeassistant", 
        model: "hassmart", 
        icon: "https://home-assistant.io/demo/favicon-192x192.png", 
        properties: [
          {
            status: "off"
          }
        ], 
        actions: [
          "TurnOn", 
          "TurnOff", 
	  "QueryPowerState"
        ], 
        extension: {
          link: "https://www.baidu.com"
	}
    },
    {
        deviceId: "switch.plug_158d000163ae00", 
        deviceName: "净化器", 
        deviceType: "airpurifier", 
        zone: "阳台", 
        brand: "homeassistant", 
        model: "负离子发生器", 
        icon: "https://home-assistant.io/demo/favicon-192x192.png", 
        properties: [
          {
            status: "off"
          }
        ], 
        actions: [
          "TurnOn", 
          "TurnOff", 
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
	  "SetBrightness",
	  "SetColor",
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
		if(strpos($result->deviceId,"temperature"))
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
				   "properties":[
				    {
		   	              "name":"temperature",
		   	              "value":"%s"
			            }
		                    ]

				}';
				$result->name="QueryTemperatureResponse";
		}
		 elseif(strpos($result->deviceId,"illumination"))
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
				   "properties":[
				    {
		   	              "name":"illumination",
		   	              "value":"%s"
			            }
		                    ]

				}';
				$result->name="QueryIlluminationResponse";
		}
		 elseif(strpos($result->deviceId,"humidity"))
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
				   "properties":[
				    {
		   	              "name":"humidity",
		   	              "value":"%s"
			            }
		                    ]

				}';
				$result->name="QueryHumidityResponse";
		}
		else
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
				   "properties":[
				    {
		   	              "name":"powerstate",
		   	              "value":"%s"
			            }
		                    ]

				}';
		}	
		
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
