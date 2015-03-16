<?php

include_once '../models/Location_History.php';


// for updating a location record

$data = Array('name' => $_POST['name'], 'description' => $_POST['description']);
$db->where('id', $_POST['id']);
$db->update('location', $data);



?>
