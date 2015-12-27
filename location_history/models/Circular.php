<?php


/*
implemented using "circular statistics":
http://rosettacode.org/wiki/Averages/Mean_angle#PHP

this is an approximation, and from what i've seen nothing will give exact results
for averages involving more than two values. e.g., the average of 360, 360 and 15
should be 5, but this method yields roughly 4.98
*/


function meanAngle($angles) {
	$y_part = $x_part = 0;
	$size = count($angles);

	for ($i = 0; $i < $size; $i++) {
		$x_part += cos(deg2rad($angles[$i]));
		$y_part += sin(deg2rad($angles[$i]));
	}

	$x_part /= $size;
	$y_part /= $size;
	return rad2deg(atan2($y_part, $x_part));
}


function circularMean($values, $circular_units_input) {
	$circular_units_base = 360;
	$scaling_factor = $circular_units_input / $circular_units_base;

	// convert values to angles so that meanAngle can be invoked on array
	foreach( $values as &$value ) {
		$value /= $scaling_factor;
	}
	unset($value);

	// mean is rescaled to input units and returned
	$toReturn = meanAngle($values) * $scaling_factor;
	if ($toReturn < 0) {
		return $toReturn + $circular_units_input;
	}
	return $toReturn;

}



?>
