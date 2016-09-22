<?php
/**
 * Configuration for web/view URLs 
 * 
 * config key prefix: 
 *    App/ViewUrl
 * 
 */
$config = [];

$addr_prefix = "";

/**
 * @var string base url for site URLs
 *    config key: App/ViewUrl/url_base
 */
$config['url_base'] = "$addr_prefix/webapp/view.php";

/**
 * @var string home URL - empty string assets are mapped to this 'home' URL
 *    onfig key: App/ViewUrl/home
 */
$config['url_home'] = "$addr_prefix/";


































return $config;

