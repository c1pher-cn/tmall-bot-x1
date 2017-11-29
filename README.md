# tmall-bot-x1
天猫精灵智能家居技能对接homeassistant

本代码基于PHP 5.3.3 和mysql、还有php5.2-develop版的oauth2-server-php
https://github.com/bshaffer/oauth2-server-php/blob/php5.2-develop/README.md

该方案有几个硬性要求：   
   1.homeassistant公网可以访问
   2.搭建公网可访问的https的oauth2服务（对应本项目中的 authorize.php、token.php、server.php）
   3.搭建公网可访问的网关服务器，将天猫开放平台的语义转换成HA的api，代理调用HA（gate.php、homeassistant_conf.php、aligenies_request.php）。   
   4.该方式目前只能自己搭自己用（技能只能在测试状态，无法发布）
不足：
   目前未完成鉴权，完成授权之后后续与天猫开放平台之间的通讯并未验证token的合法性。
   目前只支持homeassistant里light、switch、media_player类型设备的控制，和这三个设备电源状态的查看。
部署配置方法详见 https://bbs.hassbian.com/thread-1862-1-1.html
