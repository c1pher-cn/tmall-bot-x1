<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>天猫精灵设备管理</title>
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
          .weui_grid_icon {
    width: 60px;
    height: 60px;
    margin: 0 auto;
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
    	
    
    
    
        <div class="weui_btn_area" v-if="notice.updata">
                 <a v-bind:href="notice.updataLink" target="_blank" class="weui_btn weui_btn bg-orange">有新版本啦！</a>
        </div>
        
        
    
    
    
        <div class="weui_grids" style="margin-top: 30px;">
            
            
            <template v-for="(item, index) in deviceList">
            
            <a href="javascript:;" class="weui_grid js_grid" @click="caozuo(item)" >
                <div class="weui_grid_icon">
                    <img v-bind:src="item.icon"  alt="">
                </div>
                <p class="weui_grid_label">
                    {{ item.zone+"的"+item.deviceName }}
                </p>
            </a>
            
            </template>
            
            
            
            <a href="add.php" class="weui_grid js_grid">
                <div class="weui_grid_icon">
                    <span class="icon icon-36" style="font-size: 60px;"></span>
                </div>
                <p class="weui_grid_label" style="margin-top:  20px;">
                    增 加
                </p>
            </a>

        </div>
        
        
        
        
        
        <div class="weui_btn_area">
                 <a href="https://open.bot.tmall.com/console/skill/list" target="_blank" class="weui_btn weui_btn bg-blue">打开AliGenie开发平台</a>
        </div>
        
        
        
        
        
        

</div>
<div class="page-bd">
    
   
    
    
    
    
    
    
    
    
    
    
    
</div>
<div class="weui-footer" style="margin-top: 70px;">
<p class="weui-footer-text">Copyright &copy; qebabe</p>

</div>
</div>   
    
   <script>
 
       
       
       
       
       
       
       
       
       
       
var vm = new Vue({
  el: '#app',
  data: {
      notice:{
      	title:'天猫精灵设备管理_1.0   By qebabe',
        nocice:"",
        
      },
      //HomeAssistant_deviceList:
      deviceList:[],
      HomeAssistant_deviceList:[],

      
     
  },
    created:function(){
        const that=this;
        that.getNotice();
        that.getData();
        

  },
    methods: {
        caozuo:function(e){
            const that=this;
            
            console.log(e);
        
          $.modal({
          	title: e.zone+"的"+e.deviceName+"_"+e.model,
          	text: JSON.stringify(e),
          	buttons: [
            { text: "删除", onClick: function(){ 
                
                
                $.confirm("您确定要删除"+e.deviceName+"吗?", "确认删除?", function() {
          			//$.toast("删除成功!");
                    
                    that.del(e.deviceId);
                    
        		}, function() {
          			//取消操作
        		});

                
                
                
            } 
            },
              
              
              
              
            { text: "取消", className: "default"},
          ]
        });

            
            
        },
        getData:function(){
        //$.toast("ret");
            const that=this;   
        var timestamp =Date.parse(new Date());
        var url ='service.php?v=getList';
        console.log(url);
        this.$http.post(
            url,
            {},
            {emulateJSON:true}

            ).then(
          function (res) {
            console.log(res.data);
            //alert("保存成功！");
              if(res.data.code=="ok"){
              	//$.toast(res.data.Msg);
                  that.deviceList=res.data.data;
              }else{
              	$.toast(res.data.Msg, "forbidden");
              }
          },function (res) {
            console.log(res);
              $.toast("网络错误", "cancel");

          }
        );

    
        },
        del:function(deviceId){
        //$.toast("ret");
        const that=this;   
        var timestamp =Date.parse(new Date());
        var url ='service.php?v=del';
        console.log(url);
        this.$http.post(
            url,
            {
            	deviceId:deviceId
            },
            {emulateJSON:true}

            ).then(
          function (res) {
            console.log(res.data);
              if(res.data.code=="ok"){
              	$.toast(res.data.Msg);
                  that.deviceList=res.data.data;
                  
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
    
    
    
    
    
    
    
    
    
    
</body>
</html>












