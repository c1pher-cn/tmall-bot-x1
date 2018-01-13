<?php
require_once __DIR__.'/homeassistant_conf.php';
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
{       $ha_Id = '';
	$deviceId=$obj->payload->deviceId;
	$action = '';
	$device_ha = '';
	$response_name = $obj->header->name.'Response';
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
	 
	$query_response = file_get_contents(URL."/api/".$action."/".$deviceId."?api_password=".PASS);
        $state = json_decode($query_response)->state; 	
	error_log($state);
	error_log(URL."/api/".$action."/".$deviceId."?api_password=".PASS);

	$response = new Response();
        $response->put_query_response(True,$state,$response_name,$deviceId,"","",$obj->header->name);
	return $response; 

}	
function  Device_control($obj)
{
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
	default:
		break;
	}
	switch($obj->header->name)
	{
	case 'Pause':
		$action='stop';
		if ($device_ha=='cover')
		{
			$action='stop_cover';
		}
		break;
	case 'TurnOn':
		$action='turn_on';
		if ($device_ha=='cover')
		{
			$action='open_cover';
		}
		break;
	case 'TurnOff':
		$action='turn_off';
		if ($device_ha=='cover')
		{
			$action='close_cover';
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
		$action='set_volume';
		break;
	case 'SetColor':
		$action='set_color';
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
	if($obj->header->name == "SetBrightness" || $obj->header->name == "SetVolume" || $obj->header->name == "SetColor")
	{
		$value = $obj->payload->value;
		if ($action=="set_color")
		{	switch($value)
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
				$b=255;
				$c=0;
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
    		$post_string = json_encode($post_array);
    		$opts = array(
			'http' => array(
				 'method' => "POST",
        			 'header' => "Content-Type: application/json",
        			 'content'=> $post_string
        	    		)
			);
		$context = stream_context_create($opts);
		$http_post = URL."/api/services/".$device_ha."/turn_on?api_password=".PASS;
		error_log($http_post);
		$pdt_response = file_get_contents($http_post, false, $context);
		$response = new Response();
		$response->put_control_response(True,$response_name,$deviceId,"","");	
		return $response;
	}	
	if($obj->header->name == "TurnOn" || $obj->header->name == "TurnOff")
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
		$http_post = URL."/api/services/".$device_ha."/".$action."?api_password=".PASS;
		error_log($http_post);
		$pdt_response = file_get_contents($http_post, false, $context);
		$response = new Response();
		$response->put_control_response(True,$response_name,$deviceId,"","");	
		return $response;
	}	
}


?>
