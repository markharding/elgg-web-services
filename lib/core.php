<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Saket Saurabh
 *
 */

/**
 * Heartbeat web service
 *
 * @return string $response Hello
 */
function site_test() {
	$response['success'] = true;
	$response['message'] = "Hello";
	return $response;
} 

expose_function('site.test',
				"site_test",
				array(),
				"Get site information",
				'GET',
				false,
				false);

/**
 * Web service to get site information
 *
 * @return string $url URL of Elgg website
 * @return string $sitename Name of Elgg website
 * @return string $language Language of Elgg website
 * @return string $enabled_services List of enabled services
 */
function site_getinfo() {
	$site = elgg_get_config('site');

	$siteinfo['url'] = elgg_get_site_url();
	$siteinfo['sitename'] = $site->name;
	$siteinfo['language'] = elgg_get_config('language');
	$siteinfo['enabled_services'] = $enabled = unserialize(elgg_get_plugin_setting('enabled_webservices', 'web_services'));
	
	//return OAuth info
	if(elgg_is_active_plugin('oauth',0) == true){
		$siteinfo['OAuth'] = "running";
	} else {
		$siteinfo['OAuth'] = "no";
	}
	
	return $siteinfo;
} 

expose_function('site.getinfo',
				"site_getinfo",
				array(),
				"Get site information",
				'GET',
				false,
				false);
				
/**
 * Retrive river feed
 *
 * @return array $river_feed contains all information for river
 */			
function site_river_feed($limit){
	
	global $jsonexport;
	
	elgg_view_river_items();

	return $jsonexport['activity'];
	
}
expose_function('site.river_feed',
				"site_river_feed",
				array('limit' => array('type' => 'int', 'required' => 'no')),
				"Get river feed",
				'GET',
				false,
				false);
				
function site_get_entities($type, $subtype, $owner_guid, $limit = 10, $offset = 0){
	
	$options = array(	'type' => $type, 
						'subtype' => $subtype,
						'limit' => $limit,
						'offset' => $offset
						);

		global $jsonexport;
		elgg_list_entities($options);
		return $jsonexport;
}
expose_function('site.get_entities',
				"site_get_entities",
				array(	'type' => array('type' => 'string', 'required' => false),
						'subtype' => array('type' => 'string', 'required' => false),
						'owned_guid' => array('type' => 'int', 'required' => false),
						'limit' => array('type' => 'int','required' => false),
						'offset' => array('type' => 'int', 'required' => false)),
				"Get list of entities",
				'GET',
				false,
				false);