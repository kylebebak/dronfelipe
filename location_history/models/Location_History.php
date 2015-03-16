<?php

include_once 'ChromePhp.php';
require_once 'MysqliDb.php';


if (!isset($db)) {
	$db = new MysqliDb('127.0.0.1', 'root', 'root', 'mysql', '8889');
	// the post must be specified for localhost, but it must not be specified in production
}

$db->select_db("location_history");



