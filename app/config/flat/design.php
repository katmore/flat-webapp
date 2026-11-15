<?php
/**
 * design configuration for flat deployments 
 */
/**
 * @var string full canonical path to the folder that contains the "tmpl" design folder;
 *    config key: design/root_dir
 *    For example, it is used by /flat/tmpl controller to 
 * @uses \flat\core\controller\tmpl  
 */
$config['root_dir'] = __DIR__."/../../Resources/design";
/**
 * @return array
 */
return $config;