<?php
session_start();
require_once __DIR__.'/server.php';
class AliGenie_Request
{
	protected $header = array(
		"namespace" => "",
		"name" => "",
		"messageId" => "",
		"payLoadVersion" => "",
	);
	protected $payload;
	protected $temp;
	public function handleRequest($request)
	{
	}
}

class AliGenie_Response
{
	protected $header;
	protected $payload;
	protected $temp;
	public function handleResponse()
        {
				                
        }


}

class Response{
	#public $result = array(
	#	"result" => "",
	#	"name" => "",
	#	"deviceId" => "",
	#	"errorCode" => "",
	#	"message" => "",
	#
	#);
	public $result;
	public $name;
	public $deviceId;
	public $errorCode;
	public $message;
	public $powerstate;
	public function put_query_response($result,$properties,$name,$deviceId,$errorCode,$message,$queryname) { 
		$this->result = $result;
		$this->name = $name;
		$this->deviceId = $deviceId;
		$this->errorCode = $errorCode;
		$this->message = $message;
		switch($name)
		{
		case "QueryResponse":
			if($properties!="")
			{       
				$this->powerstate=$properties;
				#error_log($properties);
				#error_log('####################');
		#		if($properties!="off")
		#		{
		#			$this->powerstate="on";
		#		}
			}else
			{
		                $this->errorCode = "SERVICE_ERROR";
				$this->message = "No temperature return";
			}
			break;
		case "QueryTemperatureResponse":
			if($properties!="")
			{
				$this->properties=$properties;
			}else
			{
		                $this->errorCode = "SERVICE_ERROR";
				$this->message = "No temperature return";
			}
			break;
		case "QueryPowerstateResponse":
			if($properties!="")
			{
				$this->properties=$properties;
			}else
			{
		                $this->errorCode = "SERVICE_ERROR";
				$this->message = "No powerstate return";
			}
		case "QueryColorResponse":
		        $this->errorCode = "DEVICE_NOT_SUPPORT_FUNCTION";
			$this->message = "not support";
			$this->result = FALSE;
			break;	
		case "QueryHumidityResponse":
			if($properties!="")
			{
				$this->properties=$properties;
			}else
			{
		                $this->errorCode = "SERVICE_ERROR";
				$this->message = "No temperature return";
			}
			break;
		case "QueryPm2.5Response":
			if($properties!="")
			{
				$this->properties=$properties;
			}else
			{
		                $this->errorCode = "SERVICE_ERROR";
				$this->message = "No temperature return";
			}
			break;
		case "QueryBrightnessResponse":
		        $this->errorCode = "DEVICE_NOT_SUPPORT_FUNCTION";
			$this->message = "not support";
			$this->result = FALSE;
			break;	
		case "QueryChannelResponse":
		        $this->errorCode = "DEVICE_NOT_SUPPORT_FUNCTION";
			$this->message = "not support";
			$this->result = FALSE;
			break;	
		case "QueryModeResponse":
		        $this->errorCode = "DEVICE_NOT_SUPPORT_FUNCTION";
			$this->message = "not support";
			$this->result = FALSE;
			break;	
		default:
		        $this->errorCode = "DEVICE_NOT_SUPPORT_FUNCTION";
			$this->message = "not support";
			$this->result = FALSE;
			break;	
		}
		//$this->result->result = $result;
		//$this->result->name = $name;
		//$this->result->deviceId = $deviceId;
		//$this->result->errorCode = $errorCode;
		//$this->result->message = $message;
	} 
		
	public function put_control_response($result,$name,$deviceId,$errorCode,$message) { 
		$this->result = $result;
		$this->name = $name;
		$this->deviceId = $deviceId;
		$this->errorCode = $errorCode;
		$this->message = $message;
		//$this->result->result = $result;
		//$this->result->name = $name;
		//$this->result->deviceId = $deviceId;
		//$this->result->errorCode = $errorCode;
		//$this->result->message = $message;
	} 
	
}
function  Device_status($obj)
{       
	$user_id=getUseridFromAccesstoken($obj->payload->accessToken);
	if ($user_id=== 0)
	{error_log('-------');
		die;
	}
	$information = getUserInformation($user_id);
	if ($information['homeassistantURL']==null)
	{
		$homeassistantURL = 'your homeassistant URL';
		$homeassistantPASS = 'your homeassistant PASSWORD';
	}
	$URL = $information['homeassistantURL'];
	$PASS = $information['homeassistantPASS'];
	$ha_Id = '';
	$deviceId=$obj->payload->deviceId;
	$action = '';
	$device_ha = '';
	$response_name = $obj->header->name.'Response';
	error_log($response_name);
	switch(substr($deviceId,0,stripos($deviceId,".")))
	{
	case 'fan':
		$device_ha='fan';
		break;
	case 'switch':
		$device_ha='switch';
		break;
	case 'light':
		$device_ha='light';
		break;
	case 'media_player':
		$device_ha='media_player';
		break;
	case 'sensor':
		//同名称传感器的整合
		//if('mixsensor' in $deviceId)
		//	将$deviceId 中的 'mixsensor' 替换成（ $obj->header->name 去掉uery，改小写）
		if(strstr($deviceId,"mixsensor"))
		{
			$sensorname = "";
			switch($obj->header->name)
			{
			case 'temperature':
				$sensorname= 'temperature';
				break;
			case 'brightness':
				$sensorname= 'brightness';
				break;
			case 'humidity':
				$sensorname= 'humidity';
				break;
			case 'pm2.5':
				$sensorname= 'pm25';
				break;
			default:
				$sensorname= 'temperature';


			}
			$ha_Id = str_replace("mixsensor",$sensorname,$deviceId); 
		}
		$device_ha='sensor';
		break;
	default:
		break;
	}

	switch($obj->header->name)
	{
	case 'powerstate':
	case 'color':
	case 'temperature':
	case 'windspeed':
	case 'brightness':
	case 'fog':
	case 'humidity':
	case 'pm2.5':
	case 'channel':
	case 'number':
	case 'direction':
	case 'angle':
	case 'anion':
	case 'effluent':
	case 'mode':
	
	default:
		$action = 'states';
	}

	 if($action==""&&$device_ha=="")
        {
                $response = new Response();
		$response->put_query_response(False,"",$response_name,$deviceId,"not support","action or device not support,name:".$obj->header->name." device:".substr($deviceId,0,stripos($deviceId,".")),"");
		return $response;
        }
	$rs = getDevice($user_id,$deviceId);
    while($row = $rs->fetch()){
	//echo $row['jsonData'];
		$virtual = $row['virtual'];

	    //判断设备是否为虚拟设备
	if ($virtual=="1"){
	        //当前设备为虚拟设备
	        $devices = $row['devices'];
	        //$devices = explode("|", $devices);
	        //print_r($devices);//取出该虚拟设备包含的子设备        
	        $devices = str_replace(" ","",$devices);
	        $devices = json_decode($devices,true);
	        //echo $devices[0]['title'];
	        //print_r($devices);
	        $action = 'states';
	        $states=array(array('name'=>'powerstate','value'=>'on'));
	        foreach($devices as $item) {//遍历包含的子设备
	            $query_response = file_get_contents($URL."/api/".$action."/".$item['deviceId']."?api_password=".$PASS);
	            $state = json_decode($query_response)->state;
	
	            $a = array ('name'=>$item['title'],'value'=>$state);  
		    array_push($states,$a);
        	}
        //$properties = json_encode($states);
   	}
   	else{
   	     //当前设备不是虚拟设备
   	     $jsonData = $row['jsonData'];
   	     //$devices = explode("|", $devices);
   	     //print_r($devices);//取出该虚拟设备包含的子设备        
   	     $jsonData = str_replace(" ","",$jsonData);
   	     $jsonData = json_decode($jsonData,true);
		$query_response = file_get_contents($URL."/api/".$action."/".$deviceId."?api_password=".$PASS);
        	$state = json_decode($query_response)->state;
        	$states=array();
        	list($pname) = array_keys($jsonData['properties'][0]);
        	#$pname = array_keys($jsonData['properties'][0])[0];
        	if($state != 'on' && $state != 'off'){
       			$states=array(array('name'=>'powerstate','value'=>'on'), array('name'=>$pname, 'value'=>$state));
       			//test(json_encode($states));
        	} else {
        		$states=array(array('name'=>'powerstate','value'=>$state));
        	}
        //$properties = json_encode($states);
    	}
    }	



	#$query_response = file_get_contents($URL."/api/".$action."/".$deviceId."?api_password=".$PASS);
        #$state = json_decode($query_response)->state; 	
	#error_log($state);
	#error_log($URL."/api/".$action."/".$deviceId."?api_password=".$PASS);

	$response = new Response();
        $response->put_query_response(True,$states,$response_name,$deviceId,"","",$obj->header->name);
	return $response; 

}	
function  Device_control($obj)
{
	$user_id=getUseridFromAccesstoken($obj->payload->accessToken);
	error_log($user_id);
	if ($user_id=== 0)
	{
		die;
	}
	$information = getUserInformation($user_id);
	if ($information['homeassistantURL']==null)
	{
		$homeassistantURL = 'your homeassistant URL';
		$homeassistantPASS = 'your homeassistant PASSWORD';
	}
	$URL = $information['homeassistantURL'];
	$PASS = $information['homeassistantPASS'];
        // result:
        //      result=true
        //      name    
        //      deviceId
        //
        //      result=false
        //      deviceId
        //      errorCode
	//      message
	$deviceId=$obj->payload->deviceId;
	$action = '';
	$device_ha = '';
	$response_name = $obj->header->name.'Response';
	switch(substr($deviceId,0,stripos($deviceId,".")))
	{
	case 'vacuum':
		$device_ha='vacuum';
		break;
	case 'cover':
		$device_ha='cover';
		break;
	case 'fan':
		$device_ha='fan';
		break;
	case 'switch':
		$device_ha='switch';
		break;
	case 'light':
		$device_ha='light';
		break;
	case 'media_player':
		$device_ha='media_player';
		break;
	case 'script':
		$device_ha='script';
		break;
	case 'climate':
		$device_ha='climate';
		break;
	default:
		break;
	}
	switch($obj->header->name)
	{
	case 'Continue':
		$action='continue';
		if ($device_ha=='cover')
		{
			$action='start_cover';
		}
		elseif ($device_ha=='vacuum')
		{
			$action='start_pause';
		}
		elseif ($device_ha=='media_player')
		{
			$action='media_play';
		}
		break;
	case 'Pause':
		$action='stop';
		if ($device_ha=='cover')
		{
			$action='stop_cover';
		}
		elseif ($device_ha=='vacuum')
		{
			$action='start_pause';
		}
		elseif ($device_ha=='media_player')
		{
			$action='media_pause';
		}
		break;
	case 'TurnOn':
		$action='turn_on';
		if ($device_ha=='cover')
		{
			$action='open_cover';
		}
		elseif ($device_ha=='vacuum')
		{
			$action='turn_on';
		}
		elseif ($device_ha=='climate')
                {
                        $mode = $obj->payload->value;
                        $action='set_operation_mode';
                }
		break;
	case 'TurnOff':
		$action='turn_off';
		if ($device_ha=='cover')
		{
			$action='close_cover';
		}
		elseif ($device_ha=='vacuum')
		{
			$action='return_to_base';
		}
		elseif ($device_ha=='climate')
                {
                        $mode = $obj->payload->value;
                        $action='set_operation_mode';
                }
		break;
	case 'SetBrightness':
		$action='set_bright';
		break;
	case 'AdjustUpBrightness':
		$action='brightness_up';
		break;
	case 'AdjustDownBrightness':
		$action='brightness_down';
		break;
	case 'AdjustUpVolume':
		$action='volume_up';
		break;
	case 'AdjustDownVolume':
		$action='volume_down';
		break;
	case 'SetVolume':
		$action='volume_set';
		break;
	case 'SetColor':
		$action='set_color';
		break;
	case 'SetMode':
		$mode = $obj->payload->value;
		if ($mode =='silent')
		{
			$action='volume_mute';
		}elseif ($mode=='heat' || $mode=='cold' || $mode=='ventilate'  || $mode=='auto' || $mode=='energy' || $mode=='dehumidification')
		{
			$action='set_operation_mode';
		}
		break;
	case 'CancelMode':
		$action='volume_mute';
		break;
	case 'SetMute':
		$action='volume_mute';
		break;
	case 'CancelMute':
		$action='volume_mute';
		break;
	case 'Next':
		$action='media_next_track';
		break;
	case 'Previous':
		$action='media_previous_track';
		break;
	case 'SelectChannel':
		$action='select_source';
		break;
	#
	case 'SetTemperature':
		$action='set_temperature';
		break;
	case 'SetWindSpeed':
		$action='set_fan_mode';
		break;


	default:
		break;
	}
	if($action==""&&$device_ha=="")
	{
		$response = new Response();
		$response->put_control_response(False,$response_name,$deviceId,"not support","action or device not support,name:".$obj->header->name." device:".substr($deviceId,0,stripos($deviceId,".")));
		return $response;
	}
	if($obj->header->name == "SetBrightness" || $obj->header->name == "SetVolume" || $obj->header->name == "SelectChannel" || $obj->header->name == "SetColor" || $obj->header->name == "SetMode" || $obj->header->name == "CancelMode" || $obj->header->name == "SetTemperature" || $obj->header->name == "SetWindSpeed" || ($obj->header->name=="TurnOn"&$action=="set_operation_mode") || ( $obj->header->name=="TurnOff"&$action=="set_operation_mode"))
	{
		$value = $obj->payload->value;
		if ($action=="volume_mute")
		{	
			if($obj->header->name=='SetMode')
			{
				$value = 'true';
			}else{
				$value = 'false';
			}
			$post_array = array (
				"entity_id" => $deviceId,
				"is_volume_muted" => $value
			);
		}
		if ($action=="volume_set")
		{	
			$post_array = array (
				"entity_id" => $deviceId,
				"volume_level" => $value
			);
		}
		if ($action=="select_source")
 		{	
 			$post_array = array (
 				"entity_id" => $deviceId,
 				"source" => $value
 			);
 		}
		if ($action=="set_bright")
		{	
			$action="turn_on";
			switch(strtolower($value))
			{
				case 'min':
					$brightness = 0;
					break;
				case 'max':
					$brightness = 100;
					break;
				default:
					$brightness = (int)$value;
			}
			$post_array = array (
				"entity_id" => $deviceId,
				"brightness_pct" => $brightness
			);
		}
		if ($action=="set_color")
		{	
			$action="turn_on";
			switch($value)
			{
			case 'Red':	
				$a=255;
				$b=0;
				$c=0;
				break;
			case 'Green':	
				$a=0;
				$b=128;
				$c=0;
				break;
			case 'Yellow':	
				$a=255;
				$b=200;
				$c=36;
				break;
			case 'Blue':	
				$a=0;
				$b=0;
				$c=255;
				break;
			case 'White':	
				$a=255;
				$b=255;
				$c=255;
				break;
			case 'Black':	
				$a=0;
				$b=0;
				$c=0;
				break;
			case 'Cyan':	
				$a=0;
				$b=255;
				$c=255;
				break;
			case 'Purple':	
				$a=128;
				$b=0;
				$c=128;
				break;
			case 'Orange':	
				$a=255;
				$b=165;
				$c=0;
				break;
			default:
				$a=100;
				$b=100;
				$c=100;
				break;
			}
		
			$post_array = array (
				"entity_id" => $deviceId,
				"rgb_color" => array($a,$b,$c)
			);
		}
		if ($action=="set_operation_mode")
		{
			if ($mode=='heat')
			{
				$value="heat";
			}
			elseif($mode=='cold')
			{
				$value="cool";
			}
			elseif($mode=='ventilate' || $mode=='auto')
			{
				$value="fan_only";
			}	
			elseif($mode=='dehumidification')
			{
				$value="dry";
			}
			elseif($mode=='off')
                        {
                                $value="off";
                        }else
                        {
                                $value="cool";
                        }	
			$post_array = array (
 				"entity_id" => $deviceId,
 				"operation_mode" => $value
 			);
 		}
		if ($action=="set_temperature")
 		{	
 			$post_array = array (
 				"entity_id" => $deviceId,
 				"temperature" => $value
 			);
 		}
		if ($action=="set_fan_mode")
		{
			if($value=="max" || $value=="3")	
			{
				$value="High";
			}
			elseif($value=="min" || $value=="1")	
			{
				$value="Low";
			}
			elseif($value=="2")
			{
				$value="Middle";
			}	
			else
			{
				$value="High";
			}
			$post_array = array (
 				"entity_id" => $deviceId,
 				"fan_mode" => $value
 			);
 		}
		$post_string = json_encode($post_array);
		#error_log($post_string);
    		$opts = array(
			'http' => array(
				 'method' => "POST",
        			 'header' => "Content-Type: application/json",
        			 'content'=> $post_string
        	    		)
			);
		$context = stream_context_create($opts);
		$http_post = $URL."/api/services/".$device_ha."/".$action."?api_password=".$PASS;
		#error_log($http_post);
		$pdt_response = file_get_contents($http_post, false, $context);
		$response = new Response();
		$response->put_control_response(True,$response_name,$deviceId,"","");	
		return $response;
	}	
	if($obj->header->name == "TurnOn" || $obj->header->name == "TurnOff" || $obj->header->name == "Pause" ||  $obj->header->name == "Continue" ||  $obj->header->name == "AdjustUpVolume" ||  $obj->header->name == "AdjustDownVolume" || $obj->header->name == "Next" || $obj->header->name == "Previous")
	{
		$post_array = array (
			"entity_id" => $deviceId,
		);
    		$post_string = json_encode($post_array);
    		$opts = array(
			'http' => array(
				 'method' => "POST",
        			 'header' => "Content-Type: application/json",
        			 'content'=> $post_string
        	    		)
			);
		$context = stream_context_create($opts);
		$http_post = $URL."/api/services/".$device_ha."/".$action."?api_password=".$PASS;
		error_log($http_post);
		$pdt_response = file_get_contents($http_post, false, $context);
		$response = new Response();
		$response->put_control_response(True,$response_name,$deviceId,"","");	
		return $response;
	}	
}


?>
