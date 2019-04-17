<?php
include_once( 'server.php' );
session_start();

if(empty($_SESSION)||empty($_SESSION['userinfo']))
{
        $_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
	header("Location: /index.php");
}
$user_id = $_SESSION['userinfo']['user_id'];
$user_name = $_SESSION['userinfo']['user_name'];
$fromwhere = $_SESSION['userinfo']['from'];
$information = getUserInformation($user_id);
if ($information['homeassistantURL']==null)
{
	echo '新用户';
	die;
	$homeassistantURL = 'your homeassistant URL';
	$homeassistantPASS = 'your homeassistant PASSWORD';
	$email = 'your email';
}
$homeassistantURL = $information['homeassistantURL'];
$homeassistantPASS = $information['homeassistantPASS'];
$email = $information['email'];

$url = $homeassistantURL."/api/states";
$ch = curl_init(); 
   // set url 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
"Content-Type: application/json\r\n"."Authorization: Bearer ".$homeassistantPASS."\r\n"
));
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_TIMEOUT, 2); //设置整个网络请求最长执行时间为2秒
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1); //设置连接目标服务器1秒无响应时判断为超时

#curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
#curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$query_response = curl_exec($ch); 
$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
$errorCode =curl_errno($ch);
if ($http_code >= 400 && $http_code < 500)
{
	echo '请检查您的homeassistant密码是否正确或自行验证下方链接是否可以打开您的HomeAssistant，'.PHP_EOL.'链接：';
	echo '<p><a href="'.$url.'">点击链接检查是否可以正常打开</a></p>';

	die;
}
if ($http_code > 500)
{
	echo '请检查您的homeassistant运行状态是否正确';
	die;
}
if ($errorCode)
{
	echo '超时或其他错误'.curl_error($ch);
	die;
}
curl_close($ch);  


#$query_response = @file_get_contents($homeassistantURL."/api/states?api_password=".$homeassistantPASS);
#if ($query_response === FALSE){
#	echo 'something error whit your homeassistant'.PHP_EOL;
#	$error = var_dump($http_response_header);
#	echo $error[0];
#	die;
#}
$arr = json_decode($query_response);
$binary_sensor=array();
$sensor=array();
$light=array();
$cover=array();
$switch=array();
$vacuum=array();
$fan=array();
$media_player=array();
$scripts=array();
$climate=array();
$num = count($arr); 
for($i=0;$i<$num;++$i){ 
	if (strstr($arr[$i]->entity_id,"binary_sensor.")){
		array_push($binary_sensor,$arr[$i]);
		continue;
	}
	if (strstr($arr[$i]->entity_id,"sensor.")&&(strstr($arr[$i]->entity_id,"temperature")||strstr($arr[$i]->entity_id,"humidity")||strstr($arr[$i]->entity_id,"pm25"))){
		array_push($sensor,$arr[$i]);
		continue;
	}
	if (strstr($arr[$i]->entity_id,"light.")){
		array_push($light,$arr[$i]);
		continue;
	}
	if (strstr($arr[$i]->entity_id,"cover.")){
		array_push($cover,$arr[$i]);
		continue;
	}
	if (strstr($arr[$i]->entity_id,"switch.")){
		array_push($switch,$arr[$i]);
		continue;
	}
	if (strstr($arr[$i]->entity_id,"vacuum.")){
		array_push($vacuum,$arr[$i]);
		continue;
	}
	if (strstr($arr[$i]->entity_id,"fan.")){
		array_push($fan,$arr[$i]);
		continue;
	}
	if (strstr($arr[$i]->entity_id,"media_player.")){
		array_push($media_player,$arr[$i]);
		continue;
	}
	if (strstr($arr[$i]->entity_id,"script.")){
		array_push($scripts,$arr[$i]);
		continue;
	}
	if (strstr($arr[$i]->entity_id,"climate.")){
		array_push($climate,$arr[$i]);
		continue;
	}
}
$hadevice=array();
$num = count($cover); 
for($i=0;$i<$num;++$i){ 
  array_push($hadevice,array("entity_id" => $cover[$i]->entity_id,"friendly_name" => $cover[$i]->attributes->friendly_name));
}	  
	
$num = count($light); 
for($i=0;$i<$num;++$i){ 
  array_push($hadevice,array("entity_id" => $light[$i]->entity_id,"friendly_name" => $light[$i]->attributes->friendly_name));
}
$num = count($switch); 
for($i=0;$i<$num;++$i){ 
  array_push($hadevice,array("entity_id" => $switch[$i]->entity_id,"friendly_name" => $switch[$i]->attributes->friendly_name));
}
$num = count($vacuum); 
for($i=0;$i<$num;++$i){ 
  array_push($hadevice,array("entity_id" => $vacuum[$i]->entity_id,"friendly_name" => $vacuum[$i]->attributes->friendly_name));
}
$num = count($sensor); 
for($i=0;$i<$num;++$i){ 	
  array_push($hadevice,array("entity_id" => $sensor[$i]->entity_id,"friendly_name" => $sensor[$i]->attributes->friendly_name));
}
$num = count($fan); 
for($i=0;$i<$num;++$i){ 	
  array_push($hadevice,array("entity_id" => $fan[$i]->entity_id,"friendly_name" => $fan[$i]->attributes->friendly_name));
}
$num = count($media_player); 
for($i=0;$i<$num;++$i){ 	
  array_push($hadevice,array("entity_id" => $media_player[$i]->entity_id,"friendly_name" => $media_player[$i]->attributes->friendly_name));
}
$num = count($scripts); 
for($i=0;$i<$num;++$i){ 	
  array_push($hadevice,array("entity_id" => $scripts[$i]->entity_id,"friendly_name" => $scripts[$i]->attributes->friendly_name));
}
$num = count($climate); 
for($i=0;$i<$num;++$i){ 	
  array_push($hadevice,array("entity_id" => $climate[$i]->entity_id,"friendly_name" => $climate[$i]->attributes->friendly_name));
}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>天猫精灵设备添加</title>
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
  <link rel="stylesheet" href="weui/style/weuix.min.css"/>
  
  <!--
    <link rel="icon" href="weui/favicon.ico">


      
-->
      <script src="weui/zepto.min.js"></script>
      <script src="weui/vue.js"></script>
      <script src="weui/vue-resource.js"></script>
      <script src="weui/select.js"></script>
      <script src="weui/picker.js"></script>
      <style>
         .weui_label {
    		display: block;
    		width: 260px;
    		word-wrap: break-word;
    		word-break: break-all;
		} 
                 .page-hd-title {
    font-size: 20px;
    font-weight: 400;
    text-align: center;
    margin-bottom: 15px;
}
          
    </style>

</head>


<body ontouchstart  class="page-bg">
<div id="app">
<div class="tcenter" style="overflow:hidden; ">
 
    
    <template v-for="(item, index) in notice.logo">
    	<a v-bind:href="item.link" target="_blank">
      		<img class=" img-radius"  style="margin:10px auto 0;height:50px;margin-left: 30px;" v-bind:src="item.img">
    	</a>
    </template>
    
    
    
</div>

<div class="page-hd" >
    <h1 class="page-hd-title">
        {{ notice.title }}
    </h1>
    
    <p class="page-hd-desc" style="margin-bottom: 30px;">
        <a v-bind:href="notice.link" target="_blank">
    		{{ notice.notice }}
        </a>
    </p>
    <p class="page-hd-desc">
    	填写下面的信息，生成配置文件。
    
    </p>
</div>

<div class="page-bd">
    
    <div class="weui_cells_title">填写下列信息</div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">deviceId（设备Id）:</label></div>
		<div class="weui_cell_bd weui_cell_primary">
                   <select class="weui_select" name="select2" id="deviceId" v-model="deviceId" >

                        <template v-for="(item, index) in Homeassistant_device">

                                <option v-bind:value="item['entity_id']">{{ item['friendly_name']+" "+item['entity_id'] }}</option>

                        </template>

                    </select> 
			
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">deviceName（设备名称）:</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" id="deviceName" type="text" v-model="deviceName" placeholder="请输入deviceName"/>
                </div>
            </div>
        
            <div class="weui_cell weui_cell_select weui_select_after">
                <div class="weui_cell_hd">
                    <label for="" class="weui_label">deviceType（设备类型）:</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <select class="weui_select" id="deviceType" name="select2" v-model="deviceType" >
                        
                        <template v-for="(item, index) in deviceTypeData">

                        	<option v-bind:value="item['value']">{{ item['title'] }}</option>
                            
                        </template>
                        
                    </select>
                </div>
            </div>
            
            <div class="weui_cell weui_cell_select weui_select_after">
                <div class="weui_cell_hd">
                    <label for="" class="weui_label">zone（位置）:</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <select class="weui_select" id="zone" name="select2" v-model="zone" >
                        
                        <template v-for="(item, index) in zoneData">

                        	<option v-bind:value="zoneData[index]">{{ item }}</option>
                            
                        </template>

                        
                    </select>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">brand（品牌）:</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" id="brand" type="text" v-model="brand" placeholder="请输入brand"/>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">model（型号）:</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" id="model" type="text" v-model="model" placeholder="请输入model"/>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">icon（图标）:</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" id="icon" type="text" v-model="icon" placeholder="请输入icon"/>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label for="" class="weui_label">properties（支持的属性状态）:</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="text" value="" id='properties' placeholder="点击选择properties"/>
                    
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label for="" class="weui_label">actions（支持的操作）:</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="text" value="" id='actions' placeholder="点击选择actions"/>
                    
                </div>
            </div>
             
</div>

            <div class="weui_btn_area">
                 <a href="javascript:;" class="weui_btn weui_btn bg-blue" @click="saveData()">添  加</a>
	    </div> 


   <div class="weui_cells" style="margin-bottom:  50px; display:none;"> 
    <p style="margin: 22px;" id="jsonData">
    {
      "deviceId":"{{ deviceId }}",
      "deviceName":"{{ deviceName }}",
      "deviceType":"{{ deviceType }}",
      "zone":"{{ zone }}",          
      "brand":"{{ brand }}",
      "model":"{{ model }}",     
      "icon":"{{ icon }}",
      "properties":{{ properties }},
      "actions":{{ actions }},
      "extensions":{
         "extension1":"",
         "extension2":""
      }
     }
    
    </p>
    </div>
    
    
    </div>
<div class="weui-footer" style="margin-top: 70px;">
<p class="weui-footer-text">Copyright &copy; qebabe</p>

</div>
</div>   
      
   <script>
       
   
 $(function(){
     
     $("#deviceId").select({
        onChange: function(d) {
          //$.alert(d.values);
            //$("#ac").val(d.values);
            var a = d.values;
	    alert("hahhaha");
	    console.log("aaaaa");
	    console.log(d.values);
        }
      });
     $("#actions").select({
        title: "选择actions：",
        multi: true,
        split:',',
        closeText:'完成',
        items: vm.actionsData,
        onChange: function(d) {
          //$.alert(d.values);
            //$("#ac").val(d.values);
            var a = d.values.split(",")
            for (var i=0;i<a.length;i++)
            {
                Vue.set(vm.actions,i,a[i]);
            }

        }
      });
     $("#properties").select({
        title: "选择properties：",
        multi: true,
        split:',',
        closeText:'完成',
        items: vm.propertiesData1,
        onChange: function(d) {
          //$.alert(d.values);
            //$("#ac").val(d.values);
            
            
            var a = d.values.split(",")
            var value
            
            for (var i=0;i<a.length;i++)
            {
                
                value=vm.propertiesData[i].value;
                var dd='{"'+a[i]+'":"'+value+'"}';
                
                Vue.set(vm.properties,i,JSON.parse(dd))
                
            }
            //Vue.set(vm.properties,i,a[i]);
            

        }
      });
     
     
     
 })
         
       
       
       
       
       
       
       
       
       
       
       
       
       
       
       
var vm = new Vue({
  el: '#app',
  data: {
      notice:{
      	title:'天猫精灵设备管理',
        nocice:"",
        
      },
      deviceId:'',
      deviceName:'',
      deviceType:'light',
      zone:'客厅',
      brand:'',
      model:'',
      icon: "https://home-assistant.io/demo/favicon-192x192.png",
      actions:[],
      properties:[],
      

      extension: {
                    link: "https://www.baidu.com"
      },
      Homeassistant_device:<?php echo json_encode($hadevice);?>,
      
      
      deviceTypeData: [
    {
      title: "电视", 
      value: "television"
    }, 
    {
      title: "灯", 
      value: "light"
    }, 
    {
      title: "空调", 
      value: "aircondition"
    }, 
    {
      title: "空气净化器", 
      value: "airpurifier"
    }, 
    {
      title: "插座", 
      value: "outlet"
    }, 
    {
      title: "开关", 
      value: "switch"
    }, 
    {
      title: "扫地机器人", 
      value: "roboticvacuum"
    }, 
    {
      title: "窗帘", 
      value: "curtain"
    }, 
    {
      title: "加湿器", 
      value: "humidifier"
    }, 
    {
      title: "风扇", 
      value: "fan"
    }, 
    {
      title: "暖奶器", 
      value: "bottlewarmer"
    }, 
    {
      title: "豆浆机", 
      value: "soymilkmaker"
    }, 
    {
      title: "电热水壶", 
      value: "kettle"
    }, 
    {
      title: "饮水机", 
      value: "waterdispenser"
    }, 
    {
      title: "电饭煲", 
      value: "cooker"
    }, 
    {
      title: "热水器", 
      value: "waterheater"
    }, 
    {
      title: "烤箱", 
      value: "oven"
    }, 
    {
      title: "净水器", 
      value: "waterpurifier"
    }, 
    {
      title: "冰箱", 
      value: "fridge"
    }, 
    {
      title: "机顶盒", 
      value: "STB"
    }, 
    {
      title: "传感器", 
      value: "sensor"
    }, 
    {
      title: "洗衣机", 
      value: "washmachine"
    }, 
    {
      title: "智能床", 
      value: "smartbed"
    }, 
    {
      title: "香薰机", 
      value: "aromamachine"
    }, 
    {
      title: "窗", 
      value: "window"
    }, 
    {
      title: "抽油烟机", 
      value: "kitchenventilator"
    }, 
    {
      title: "指纹锁", 
      value: "fingerprintlock"
    }, 
    {
      title: "万能遥控器", 
      value: "telecontroller"
    }, 
    {
      title: "洗碗机", 
      value: "dishwasher"
    }, 
    {
      title: "除湿机", 
      value: "dehumidifier"
    }, 
    {
      title: "干衣机", 
      value: "dryer"
    }, 
    {
      title: "壁挂炉", 
      value: "wall-hung-boiler"
    }, 
    {
      title: "摄像头", 
      value: "camera"
    }, 
    {
      title: "路由器", 
      value: "router"
    },
    {
      title: "微波炉", 
      value: "microwaveoven"
    },
    {
      title: "取暖器", 
      value: "heater"
    },
    {
      title: "驱蚊器", 
      value: "mosquito-dispeller"
    },
    {
      title: "跑步机", 
      value: "treadmill"
    },
    {
      title: "智能门控(门锁)", 
      value: "smart-gating"
    },
    {
      title: "智能手环", 
      value: "smart-band"
    },
    {
      title: "晾衣架", 
      value: "hanger"
    }
  ],
      zoneData: [
 	"门口",
	"客厅",
	"卧室",
	"客房",
	"主卧",
	"次卧",
	"书房",
	"餐厅",
	"厨房",
	"洗手间",
	"浴室",
	"阳台",
	"宠物房",
	"老人房",
	"儿童房",
	"婴儿房",
	"保姆房",
	"玄关",
	"一楼",
	"二楼",
	"三楼",
	"四楼",
	"楼梯",
	"走廊",
	"过道",
	"楼上",
	"楼下",
	"影音室",
	"娱乐室",
	"工作间",
	"杂物间",
	"衣帽间",
	"吧台",
	"花园",
	"温室",
	"车库",
	"休息室",
	"办公室",
	"起居室"
      ],
      actionsData: [{"title":"\u6253\u5f00","value":"TurnOn"},{"title":"\u5173\u95ed","value":"TurnOff"},{"title":"\u9891\u9053\u5207\u6362","value":"SelectChannel"},{"title":"\u9891\u9053\u589e\u52a0","value":"AdjustUpChannel"},{"title":"\u9891\u9053\u51cf\u5c11","value":"AdjustDownChannel"},{"title":"\u58f0\u97f3\u6309\u7167\u6b65\u957f\u8c03\u5927","value":"AdjustUpVolume"},{"title":"\u58f0\u97f3\u6309\u7167\u6b65\u957f\u8c03\u5c0f","value":"AdjustDownVolume"},{"title":"\u58f0\u97f3\u8c03\u5230\u67d0\u4e2a\u503c","value":"SetVolume"},{"title":"\u8bbe\u7f6e\u9759\u97f3","value":"SetMute"},{"title":"\u53d6\u6d88\u9759\u97f3","value":"CancelMute"},{"title":"\u64ad\u653e","value":"Play"},{"title":"\u6682\u505c","value":"Pause"},{"title":"\u7ee7\u7eed","value":"Continue"},{"title":"\u4e0b\u4e00\u9996\u6216\u4e0b\u4e00\u53f0","value":"Next"},{"title":"\u4e0a\u4e00\u9996\u6216\u4e0a\u4e00\u53f0","value":"Previous"},{"title":"\u8bbe\u7f6e\u4eae\u5ea6","value":"SetBrightness"},{"title":"\u8c03\u5927\u4eae\u5ea6","value":"AdjustUpBrightness"},{"title":"\u8c03\u5c0f\u4eae\u5ea6","value":"AdjustDownBrightness"},{"title":"\u8bbe\u7f6e\u6e29\u5ea6","value":"SetTemperature"},{"title":"\u8c03\u9ad8\u6e29\u5ea6","value":"AdjustUpTemperature"},{"title":"\u8c03\u4f4e\u6e29\u5ea6","value":"AdjustDownTemperature"},{"title":"\u8bbe\u7f6e\u98ce\u901f","value":"SetWindSpeed"},{"title":"\u8c03\u5927\u98ce\u901f","value":"AdjustUpWindSpeed"},{"title":"\u8c03\u5c0f\u98ce\u901f","value":"AdjustDownWindSpeed"},{"title":"\u6a21\u5f0f\u7684\u5207\u6362","value":"SetMode"},{"title":"\u8bbe\u7f6e\u989c\u8272","value":"SetColor"},{"title":"\u6253\u5f00\u529f\u80fd","value":"OpenFunction"},{"title":"\u5173\u95ed\u529f\u80fd","value":"CloseFunction"},{"title":"\u67e5\u8be2\u989c\u8272","value":"QueryColor"},{"title":"\u67e5\u8be2\u7535\u6e90\u5f00\u5173","value":"QueryPowerState"},{"title":"\u67e5\u8be2\u6e29\u5ea6","value":"QueryTemperature"},{"title":"\u67e5\u8be2\u6e7f\u5ea6","value":"QueryHumidity"},{"title":"\u67e5\u8be2\u98ce\u901f","value":"QueryWindSpeed"},{"title":"\u67e5\u8be2\u4eae\u5ea6","value":"QueryBrightness"},{"title":"\u67e5\u8be2\u96fe\u91cf","value":"QueryFog"},{"title":"\u67e5\u8be2\u6a21\u5f0f","value":"QueryMode"},{"title":"\u67e5\u8be2pm2.5\u542b\u91cf","value":"QueryPM25"},{"title":"\u67e5\u8be2\u65b9\u5411","value":"QueryDirection"},{"title":"\u67e5\u8be2\u89d2\u5ea6","value":"QueryAngle"}],
      propertiesData:[{"title":"powerstate","name":"\u7535\u6e90\u72b6\u6001","value":"off"},{"title":"color","name":"\u989c\u8272","value":"Red"},{"title":"temperature","name":"\u6e29\u5ea6","value":"1"},{"title":"windspeed","name":"\u98ce\u901f","value":"1"},{"title":"brightness","name":"\u4eae\u5ea6","value":"1"},{"title":"fog","name":"\u96fe\u91cf","value":"1"},{"title":"humidity","name":"\u6e7f\u5ea6","value":"1"},{"title":"pm2.5","name":"pm2.5","value":"1"},{"title":"channel","name":"\u7535\u89c6\u9891\u9053","value":"\u4e1c\u65b9\u536b\u89c6"},{"title":"number","name":"\u7535\u89c6\u9891\u9053\u53f7","value":"1"},{"title":"direction","name":"\u65b9\u5411","value":"left"},{"title":"angle","name":"\u89d2\u5ea6","value":"1"},{"title":"anion","name":"\u8d1f\u79bb\u5b50\u529f\u80fd","value":"off"},{"title":"effluent","name":"\u51fa\u6c34\u529f\u80fd","value":"off"},{"title":"mode","name":"\u6a21\u5f0f","value":"\u53c2\u8003mode auto"},{"title":"lefttime","name":"\u5269\u4f59\u65f6\u95f4","value":"1"},{"title":"remotestatus","name":"\u8bbe\u5907\u8fdc\u7a0b\u72b6\u6001","value":"off"}],
      propertiesData1:[{"title":"\u7535\u6e90\u72b6\u6001","value":"powerstate"},{"title":"\u989c\u8272","value":"color"},{"title":"\u6e29\u5ea6","value":"temperature"},{"title":"\u98ce\u901f","value":"windspeed"},{"title":"\u4eae\u5ea6","value":"brightness"},{"title":"\u96fe\u91cf","value":"fog"},{"title":"\u6e7f\u5ea6","value":"humidity"},{"title":"pm2.5","value":"pm2.5"},{"title":"\u7535\u89c6\u9891\u9053","value":"channel"},{"title":"\u7535\u89c6\u9891\u9053\u53f7","value":"number"},{"title":"\u65b9\u5411","value":"direction"},{"title":"\u89d2\u5ea6","value":"angle"},{"title":"\u8d1f\u79bb\u5b50\u529f\u80fd","value":"anion"},{"title":"\u51fa\u6c34\u529f\u80fd","value":"effluent"},{"title":"\u6a21\u5f0f","value":"mode"},{"title":"\u5269\u4f59\u65f6\u95f4","value":"lefttime"},{"title":"\u8bbe\u5907\u8fdc\u7a0b\u72b6\u6001","value":"remotestatus"}],
  
  },
    created:function(){
        const that=this;
        that.getNotice();
        

  },
    methods: {
        daoru:function(){
        	const that=this;
            $.prompt("将配置数据粘贴到下面输入框，注意最后不要带逗号", "导入json格式数据", function(text) {
          		
                $("#jsonData").html(text);
                
                window.setTimeout(that.saveData(),1000);
                
                
        	}, function() {
          //取消操作
        	});

            
            
            
            
        
        
        
        
        },
        saveData:function(){
        //$.toast("ret");
            const that=this;
            
            
            //var jsonData='{"deviceId":"'+that.deviceId+'","deviceName":"'+that.deviceName+'","deviceType":"'+that.deviceType+'","zone":"'+that.zone+'","brand":"'+that.brand+'","model":"'+that.model+'","icon":"'+that.icon+'","properties":"'+that.properties+'","actions":"'+that.actions+'","extensions":{   "extension1":"",   "extension2":""}}';
            var jsonData=$("#jsonData").html();
            
            //var jsonData='{\"deviceId\":\"'+that.deviceId+'\",\"deviceName\":\"'+that.deviceName+'\",\"deviceType\":\"'+that.deviceType+'\",\"zone\":\"'+that.zone+'\",\"brand\":\"'+that.brand+'\",\"model\":\"'+that.model+'\",\"icon\":\"'+that.icon+'\",\"properties\":'+that.properties+',\"actions\":'+that.actions+',\"extensions\":{   \"extension1\":\"\",   \"extension2\":\"\"}}';
            
            console.log(jsonData);
            
            
            try {
                var obj = eval('(' + jsonData + ')');
            } catch(error) {
                $.toast("输入的配置格式错误！请检查！", "forbidden");
                return;
            } finally {
            
            }
            
            
            
            
       console.log("obj.deviceId:"+obj.deviceId);
                
                 if(obj.deviceId==undefined || obj.deviceId==""){
         
         			$.toast("deviceId不能为空！", "forbidden");
             		return;
             
         			}else if(obj.deviceName==undefined || obj.deviceName==""){
         
         			$.toast("deviceName不能为空！", "forbidden");
              		return;
        }  
            
            
        
            
            
            
        var timestamp =Date.parse(new Date());
        var url ='service.php?v=add';
        console.log(url);
        this.$http.post(
            url,
            {
                deviceId:obj.deviceId,
                deviceName:obj.deviceName,
		jsonData:jsonData,
		virtual :"0",
		states  :""
                
            },
            {emulateJSON:true}

            ).then(
          function (res) {
            console.log(res.data);
            //alert("保存成功！");
              if(res.data.code=="ok"){
              	$.toast(res.data.Msg);
                 window.setTimeout("window.location.href='discovery.php';",2000); 
              }else{
              	$.toast(res.data.Msg, "forbidden");
              }
          },function (res) {
            console.log(res);
              $.toast("网络错误", "cancel");

          }
        );

    },
        getNotice:function(){
        //$.toast("ret");
        const that=this;   
        var timestamp =Date.parse(new Date());
        var url ='service.php?v=getNotice';
        console.log(url);
        this.$http.post(
            url,
            {},
            {emulateJSON:true}

            ).then(
          function (res) {
            console.log(res.data);
              if(res.data.code=="ok"){
              	//$.toast(res.data.Msg);
                 that.notice=res.data.data; 
              }else{
              	$.toast(res.data.Msg, "forbidden");
              }
          },function (res) {
            console.log(res);
              $.toast("网络错误", "cancel");

          }
        );

    }
    }
})

       
    </script>
    
    



