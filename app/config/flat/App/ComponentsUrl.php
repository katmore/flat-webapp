<?php
/**
 * Configuration for web/view URLs 
 * 
 * config key prefix: 
 *    App/ComponentsUrl
 * 
 */
$config = [];

$addr_prefix = "";

/**
 * @var string base url for site URLs
 *    config key: App/ComponentsUrl/url_base
 */
$config['url_base'] = "$addr_prefix/webapp/components";

/**
 * @var string home URL - empty string assets are mapped to this 'home' URL
 *    config key: SpyDash/ComponentsUrl/system_path
 */
$config['system_path'] = __DIR__.'/../../../web/components';


































return $config;

