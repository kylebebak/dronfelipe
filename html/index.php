<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8>
	<title>dronfelipe</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="__partials/css/style.css">
	<!-- last stylesheet loaded takes precedence, which means custom styles are not overwritten by bootstrap -->
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">


	<ul class="nav navbar-nav navbar-left">
	  <li class="dropdown">
	    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" id="home">dronfelipe <span class="caret"></span></a>
	    <ul class="dropdown-menu" role="menu">

	      <li><a href="nyt"><span class="menu-item">nyt</span></a></li>
	      <li><a href="https://gist.github.com/kylebebak"><span class="menu-item">gists</span></a></li>
	      <li><a href="tortas"><span class="menu-item">tortas</span></a></li>
	      <li><a href="images"><span class="menu-item">images</span></a></li>
	      <li><a href="https://www.facebook.com/elcomedordesanpascualbailongo?fref=ts"><span class="menu-item">el comedor</span></a></li>
	      <li><a href="http://ojodecorazon.tumblr.com/"><span class="menu-item">ojo de corazón</span></a></li>
	      <li><a href="location_history"><span class="menu-item">location history</span></a></li>
	      <li role="separator" class="divider"></li>
	      <li><a href="about"><span class="menu-item">about</span></a></li>

	    </ul>
	  </li>
	</ul>

	<ul class="nav navbar-nav navbar-right">
	  <li><a href="#posts">#posts</a></li>
	  <li><a href="#code">#code</a></li>
	</ul>

</nav>


<main>



<a class="anchor" name="posts" id="first-anchor"></a>
<h2>Posts</h2>

<ul>
	<?php
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once "${root}/posts/models/Post.php";
	$query = "SELECT
		id, written, slug, name, description, content, created, updated
		FROM post
		ORDER BY written DESC";

	$posts = $db->rawQuery($query, null, false);
	foreach ($posts as $post): ?>
		<li class="post-item">
		 	<a title="<?= $post['description'] ?>" href="posts/<?= $post['slug'] ?>"><?= $post['name'] ?></a>
		</li>
	<?php endforeach; ?>
</ul>



<a class="anchor" name="code"></a>
<h2>Code</h2>

<ul>
	<li><span class="content-item">Tortas</span> - Pon tu propio changarro de tortas. Genera un <a href="tortas">menú</a> digno de los mejores puestos: sumamente variado, internacional y sin sentido alguno.</li>

	<li><span class="content-item">Match It</span> - An Android clone of the <a href="http://www.blueorangegames.com/index.php/games/spotit">Spot it!</a> card game, on Google Play Store <a href="https://play.google.com/store/apps/details?id=bebak.kyle.tap_it">here</a>.</li>

	<li><span class="content-item">py-geohash-any</span> - A minimal Python geohash library designed to use any url-safe encoding. <a href="https://github.com/kylebebak/py-geohash-any">Here on Github</a>.</li>

	<li><span class="content-item">Notes</span> - Because accessing your Evernotes takes too long. Notes provides a minimal syntax for manipulating notes from any working directory. You choose the file extension for your notes (the default is <a href="http://daringfireball.net/projects/markdown/">.md</a>), but for convenience Notes' syntax is completely independent of this extension. Combined with an editor like <a href="http://macdown.uranusjr.com/">MacDown</a>, or Sublime Text with a plugin like <a href="https://github.com/SublimeText-Markdown/MarkdownEditing">MarkdownEditing</a>, Notes recovers the formatting goodness you would otherwise lose switching from a note-taking app to plain text. Place your notes directory in your Dropbox or something similar to get syncing, versioning, and access from everywhere. <a href="https://github.com/kylebebak/notes">Check it out here</a>.</li>

	<li><p><span class="content-item">Location History</span> - Unless you've disabled location services on your phone, Google (or Apple or Microsoft) is probably <a href="http://www.howtogeek.com/195647/googles-location-history-is-still-recording-your-every-move/">tracking your location</a>, in Google's case once a minute and accurate to 5 or 10 meters. There used to be an API for accessing this data, but now the best you can do is download your raw location history for a range of dates as KML or JSON via Google Takeout. I did this, ran a clustering algorithm on the data to create visit, location and trip instances, and put them in a database.</p>

	<p>When run on about 18 months of my location data, this process turned up 4400 visits (clusters of points where I'd been stationary somewhere for more than 6 minutes) that correspond to 750 unique locations. Trips are the sequences of moving points that occur between visits. The data was a lot of fun to explore. Probed with simple queries it can answer interesting aggregate questions, like where are the top five places I spend time on Saturdays, or, over a period of 6 months, at what time on average did I leave work on each of the different weekdays.</p>

	<p>I built a front end for this data using the Google Maps API and some JS plugins. The data doesn't reveal anything that makes me uncomfortable, <a href="location_history">so I made it available here</a>.</p></li>
</ul>


<?php include "${root}/__partials/license.html"; ?>

</main>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>


</body>
</html>



