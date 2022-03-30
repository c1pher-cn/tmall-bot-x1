# tmall-bot-x1
天猫精灵智能家居技能对接homeassistant

本代码基于PHP 5.3.3 和mysql、还有php5.2-develop版的oauth2-server-php
https://github.com/bshaffer/oauth2-server-php/blob/php5.2-develop/README.md
更高版本的php可以使用正式版的oauth2-server-php

该方案有几个硬性要求：

   1.homeassistant公网可以访问   
   
   2.php+mysql或者相似的环境 
   
   
不足点：

    目前未完成鉴权，完成授权之后后续与天猫开放平台之间的通讯并未验证token的合法性。
    
    目前只支持homeassistant里light、switch、media_player、fan、cover、vacuum、script类型设备的控制，和这四个设备电源状态的查看。
    
    其他类型的设备因为我自己没有所以无法调试，有需求可以带着设备id跟我反馈。
    
  
   	
其他设备类型可能我没有的请提交设备id私信给我	

微博 https://weibo.com/u/1147593092

b站  https://space.bilibili.com/15856864
  
   
部署配置方法详见我的博客:           https://www.c1pher.cn/?p=170
