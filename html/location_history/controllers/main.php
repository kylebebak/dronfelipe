<?php

include_once '__parse_query.php';




// this query also counts the visits that contribute to each location, and gets the duration dynamically by summing the duration values from the visits, which means this can respond to any filters specified by the user
$query = "SELECT
	l.id, SUM(v.duration) AS duration, l.lat, l.lon, l.geocode_name, l.name, l.description, COUNT(l.id) AS visits
	FROM location l, visit v
	WHERE l.id = v.location_id";



// CONSTRUCT WHERE CLAUSE
include_once '__build_query.php';



// group by and order by come after where clause
$query .= " GROUP BY l.id ORDER BY duration DESC";


// limit clause comes last
if (isset($limit)) {
	$query .= " LIMIT " . $limit;
}



echo json_encode($db->rawQuery($query, null));






?>
