<?php

/**
 * EBSCO - Detect Guest mode via IP address
 *
 * PHP version 5
 *
 */
	$configFile="Config.xml";

	function validAuthIP($configFile="Config.xml") {

		$dom = simplexml_load_file($configFile);
		// get the ip address of the request
		$ip_address = $_SERVER['REMOTE_ADDR'];
		foreach($dom->ipaddresses->ip as $ip) {
		  if ( strcmp(substr($ip_address,0,strlen($ip)),$ip)==0)   {
			// inside of ip address range of customer
			return true;
		  }
		}
		// if not found, return false, not authenticated by IP address
		return false;
	  
	}


?>