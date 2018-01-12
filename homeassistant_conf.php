<?php
const URL="https://yourhomeassitant:8123";
const PASS="yourhomeassitantpassword";
const Discovery='
[
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
]';
?>
