<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */
	
session_start();

error_reporting(E_ALL);

// Load configs (database credentials etc.)
require 'config.php';

// Please Do Not Edit Below

foreach ($B as $name => $val) {

    define($name, $val);
}

$C = array();

$C['APP_DEMO'] = false;                          
$C['APP_PATH'] = "app";
$C['CLIENT_ID'] = 1;  //Android App Client ID (only for android application)

$C['ERROR_SUCCESS'] = 0;
$C['ERROR_UNKNOWN'] = 100;
$C['ERROR_ACCESS_TOKEN'] = 101;

$C['ERROR_LOGIN_TAKEN'] = 300;
$C['ERROR_EMAIL_TAKEN'] = 301;
$C['ERROR_IP_TAKEN'] = 302;

$C['ERROR_ACCOUNT_ID'] = 400;

$C['GCM_NOTIFY_CONFIG'] = 0;
$C['GCM_NOTIFY_SYSTEM'] = 1;
$C['GCM_NOTIFY_CUSTOM'] = 2;
$C['GCM_NOTIFY_LIKE'] = 3;
$C['GCM_NOTIFY_ANSWER'] = 4;
$C['GCM_NOTIFY_QUESTION'] = 5;
$C['GCM_NOTIFY_COMMENT'] = 6;
$C['GCM_NOTIFY_FOLLOWER'] = 7;

$C['ACCOUNT_STATE_ENABLED'] = 0;
$C['ACCOUNT_STATE_DISABLED'] = 1;
$C['ACCOUNT_STATE_BLOCKED'] = 2;
$C['ACCOUNT_STATE_DEACTIVATED'] = 3;

$time = time();

foreach ($C as $name => $val) {

    define($name, $val);
}

// The auto-loader which loads classes automatically
require 'autoload.inc.php';

$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
$dbo = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

$helper = new helper($dbo);
$auth = new auth($dbo);
