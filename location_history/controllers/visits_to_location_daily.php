<?php

include_once '__parse_query.php';



// this cannot return meaningful results with a location id
if (!$_POST['location_id']) {
	return;
}

$location_id = $_POST['location_id'];



// visits for location
$query = "SELECT
	DATE(start_date) AS date, SUM(duration) AS duration
	FROM grouped_point gp
	WHERE location_id = " . htmlspecialchars($location_id);


// CONSTRUCT WHERE CLAUSE
include '__build_query.php';

// group by comes after where clause
$query .= " GROUP BY DATE(start_date)";

$results = $db->rawQuery($query, null, false);

echo json_encode($results);




?>
