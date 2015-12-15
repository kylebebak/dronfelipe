<?php

require_once 'MysqliDb.php';

if (!isset($db)) {
	$db = new MysqliDb('127.0.0.1', 'root', 'root', 'mysql');
	// the port must be specified if it's not the default (3306)
}

$db->select_db("dronfelipe");



