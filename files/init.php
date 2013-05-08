<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	// Session security flags
	ini_set('session.cookie_httponly', 1);
	ini_set('session.use_only_cookies', 1);
	ini_set('session.cookie_secure', 1);

	//Set timezone
	date_default_timezone_set("Europe/London");

	function __autoload($class) {
		require_once 'class.'.$class.'.php';
	}

	// Setup app
	try {
		$app = new app();
	} catch (Exception $e) {
		die($e->getMessage());
	}

	// Connect to database
	$db = $app->config('db');
	try {
		$dsn = "{$db['driver']}:host={$db['host']}";
		$dsn .= (!empty($db['port'])) ? ';port=' . $db['port'] : '';
		$dsn .= ";dbname={$db['database']}";
		$db = new PDO($dsn, $db['username'], $db['password']);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	} catch(PDOException $e) {
		die($e->getMessage());
	}

	// Create user object
	$user = new user();

	// Import resource minifier
	$minifier = new loader($app->custom_css, $app->custom_js);
?>