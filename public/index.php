<?php

/*
 * General LoveLive! SIF Server Project
 * The AGPL3.0 License
 */

// Check PHP version.
if (version_compare(PHP_VERSION, '5.5.9', '<')) {
	die('Error: The PHP version must be least 5.5.9.');
}

// Check PHP extensions.
$Exts = ['curl', 'gd', 'iconv', 'json', 'mbstring', 'mcrypt', 'openssl', 'pdo', 'pdo_mysql'];
foreach ($Exts as $Ext) {
	if (!extension_loaded($Ext)) {
		die('Error: The PHP extension ' . $Ext . 'not load!');
	}
}

// Check PHP sapi name.
if (php_sapi_name() == 'cli') {
	die('Sorry: PHP command line is not support.');
} else if (php_sapi_name() == 'cli-server') {
	die('Sorry: The PHP built-in wevserver has limited support for rewrite, please use other webservers to run.');
}

// Fixes nginx cannot custom http headers.
// Source: http://www.php.net/manual/en/function.getallheaders.php#84262
if(!function_exists('getallheaders')) {
	function getallheaders(): array
	{
		$headers = [];
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(
					strtolower(
						str_replace('_', ' ', substr($name, 5))
					)
				))] = $value;
			}
	   }
	   return $headers;
	}
}

// Composer autoload.
require __DIR__ . '/../vendor/autoload.php';

// Rewrite to route.
require __DIR__ . '/../routes/api.php';