<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8>
	<title>dronfelipe</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
	<link href="../lib/css/bootstrap-datepicker.min.css" rel="stylesheet">
	<link href="../lib/css/bootstrap-multiselect.css" rel="stylesheet">
	<link href="../lib/css/select2.css" rel="stylesheet">
	<link href="../lib/css/select2-bootstrap.css" rel="stylesheet">
	<link href="../lib/css/metricsgraphics.css" rel="stylesheet">

	<link rel="stylesheet" href="../__partials/css/style.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>



<?php include '../__partials/navbar-left.html'; ?>





	<ul class="nav navbar-nav navbar-centered">

		<li id="search-container">

			<select id="search-options" placeholder="search locations">

				<!-- insert empty option so that first option is not selected by default. this allows user to select this option first after options are rebuilt -->
				<option id="placeholder"></option>
				<!-- populated dynamically -->

			</select>
		</li>

		<li>
			<form class="navbar-form" action="#">
			  <div class="form-group">
			    <input id="limit" type="text" class="form-control" value="30" placeholder="limit" name="limit" id="limit">
			  </div>
			</form>
		</li>

		<li id='datepicker-container'>

			<div class='col-sm-16'>
				<div class="input-daterange input-group" id="datepicker">
			  	<input type="text" class="input-sm form-control" name="start" id="start" placeholder="start date"/>
			  	<span class="input-group-addon">to</span>
			  	<input type="text" class="input-sm form-control" name="end" id="end" placeholder="end date"/>
				</div>
			</div>

		</li>

		<li id="weekdays-container">
			<select multiple="multiple">
				<optgroup label="toggle">
			  	<option value="2" selected="selected">mon</option>
			  	<option value="3" selected="selected">tue</option>
			  	<option value="4" selected="selected">wed</option>
			  	<option value="5" selected="selected">thu</option>
			  	<option value="6" selected="selected">fri</option>
			  	<option value="7" selected="selected">sat</option>
			  	<option value="1" selected="selected">sun</option>
			  </optgroup>
			</select>
		</li>




	</ul>

<?php include '../__partials/navbar-right.html'; ?>




<div id="wrapper">

	<div id="map-canvas"></div>

	<div value="" class="resource-window"></div>

</div>




<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABMVmhges6RVGlKFJW4nLARUymZk-INYM"></script> -->
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script src="https://www.google.com/jsapi"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>


<!-- these depend on twitter bootstrap, so they are loaded after it -->
<script src="../lib/js/bootstrap-datepicker.min.js"></script>
<script src="../lib/js/bootstrap-multiselect.js"></script>
<script src="../lib/js/select2.min.js"></script>
<script src="../lib/js/metricsgraphics.min.js"></script>


<!-- this depends on all of the above -->
<script src="js/scripts.js"></script>

</body>
</html>
