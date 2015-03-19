<?php

include_once '__parse_query.php';
include_once '../models/Circular.php';


// this cannot return meaningful results without a location id
if (!$_POST['location_id']) {
	return;
}

$location_id = $_POST['location_id'];






/* trips starting at location
–––––––––––––––––––––––––––––––––––––––––––––––––– */

    /* aggregate
    –––––––––––––––––––––––––––––– */
$query = "SELECT COUNT(t.end_location_id) as count_lid, t.end_location_id, l.name, AVG(t.duration) as duration, SEC_TO_TIME(AVG(TIME_TO_SEC(TIME(t.start_date)))) as start_time, AVG(t.distance) as distance
FROM trip t, location l
WHERE t.end_location_id = l.id
AND t.start_location_id = " . htmlspecialchars($location_id);


// CONSTRUCT WHERE CLAUSE
include '__build_query.php';
$query .= " GROUP BY t.end_location_id HAVING count_lid > 1 ORDER BY count_lid DESC";
$start_aggregate = $db->rawQuery($query, null, false);




    /* all
    –––––––––––––––––––––––––––––– */
$query = "SELECT t.end_location_id, l.name, t.start_date, t.duration, t.distance
FROM trip t, location l,
	(SELECT COUNT(t.end_location_id) AS count_lid, t.end_location_id
	FROM trip t
	WHERE t.start_location_id = " . htmlspecialchars($location_id) . "
	GROUP BY t.end_location_id) AS c
WHERE t.end_location_id = l.id
AND t.end_location_id = c.end_location_id
AND t.start_location_id = " . htmlspecialchars($location_id);


// CONSTRUCT WHERE CLAUSE
include '__build_query.php';
$query .= " ORDER BY c.count_lid DESC, t.end_location_id, t.start_date ASC";
$start_all = $db->rawQuery($query, null, false);





/* trips ending at location
–––––––––––––––––––––––––––––––––––––––––––––––––– */

    /* aggregate
    –––––––––––––––––––––––––––––– */
$query = "SELECT COUNT(t.start_location_id) as count_lid, t.start_location_id, l.name, AVG(t.duration) as duration, SEC_TO_TIME(AVG(TIME_TO_SEC(TIME(t.end_date)))) as end_time, AVG(t.distance) as distance
FROM trip t, location l
WHERE t.start_location_id = l.id
AND t.end_location_id = " . htmlspecialchars($location_id);


// CONSTRUCT WHERE CLAUSE
include '__build_query.php';
$query .= " GROUP BY t.start_location_id HAVING count_lid > 1 ORDER BY count_lid DESC";
$end_aggregate = $db->rawQuery($query, null, false);




    /* all
    –––––––––––––––––––––––––––––– */
$query = "SELECT t.start_location_id, l.name, t.end_date, t.duration, t.distance
FROM trip t, location l,
	(SELECT COUNT(t.start_location_id) AS count_lid, t.start_location_id
	FROM trip t
	WHERE t.end_location_id = " . htmlspecialchars($location_id) . "
	GROUP BY t.start_location_id) AS c
WHERE t.start_location_id = l.id
AND t.start_location_id = c.start_location_id
AND t.end_location_id = " . htmlspecialchars($location_id);


// CONSTRUCT WHERE CLAUSE
include '__build_query.php';
$query .= " ORDER BY c.count_lid DESC, t.start_location_id, t.end_date ASC";
$end_all = $db->rawQuery($query, null, false);







echo json_encode(array(
	"start_aggregate" => $start_aggregate,
	"start_all" => $start_all,
	"end_aggregate" => $end_aggregate,
	"end_all" => $end_all
	));





?>




