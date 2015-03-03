<?php

include_once '../php/Tortas.php';

// this controller only invoked from $.post method, which means there is no need to check if the $_POST array is populated
$num_tortas = $_POST["num_tortas"];
$num_ingredients = $_POST["num_ingredients"];

$tortas = new Tortas($num_tortas, $num_ingredients);
$menu = $tortas->get_menu();
$name = $tortas->get_name();
$half_index = round(count($menu) / 2);



$column_one = "";
$column_two = "";

for ($i = 0; $i < $half_index; $i++) {
	$column_one = $column_one . "<li><b>" . $menu[$i][0] . "</b><p>" . $menu[$i][1] . "</p></li>";
}
for ($i = $half_index; $i < count($menu); $i++) {
	$column_two = $column_two . "<li><b>" . $menu[$i][0] . "</b><p>" . $menu[$i][1] . "</p></li>";
}


echo json_encode(array($name, $column_one, $column_two));


?>
