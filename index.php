<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8>
	<title>dronfelipe</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
	<!-- last stylesheet loaded takes precedence, which means custom styles are not overwritten by bootstrap -->
	<link rel="stylesheet" href="css/style.css">
</head>
<body>



<nav class="navbar navbar-default navbar-fixed-top">


	<ul class="nav navbar-nav navbar-left">
	  <li class="dropdown">
	    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" id="home">dronfelipe <span class="caret"></span></a>
	    <ul class="dropdown-menu" role="menu">

	      <li><a href="tortas"><span class="menu-item">tortas</span></a></li>
	      <li><a href="images"><span class="menu-item">images</span></a></li>
	      <li><a href="https://www.facebook.com/pages/La-Tiendita-de-San-Pascual-Bailongo/289596207869743?fref=ts"><span class="menu-item">la tiendita</span></a></li>
	      <li><a href="https://www.facebook.com/elcomedordesanpascualbailongo?fref=ts"><span class="menu-item">el comedor</span></a></li>
	      <li><a href="http://ojodecorazon.tumblr.com/"><span class="menu-item">ojo de corazón</span></a></li>
	      <li><a href="location_history"><span class="menu-item">location history</span></a></li>


	    </ul>
	  </li>
	</ul>

	<ul class="nav navbar-nav navbar-centered">

	  <li><a href="https://github.com/kylebebak/notes">notes</a></li>
	  <li><a href="https://play.google.com/store/apps/details?id=bebak.kyle.tap_it">match it</a></li>
	  <li><a href="nyt">nyt</a></li>

	</ul>

	<ul class="nav navbar-nav navbar-right">
	  <li><a href="#notes">#notes</a></li>
	  <li><a href="#projects">#projects</a></li>
	  <li><a href="#Writing">#writing</a></li>
	</ul>

</nav>

<main>




<a class="anchor" name="notes" id="first-anchor"></a>
<h2>Notes</h2>
<ul>
	<li><span class="content-item">Notes</span> - Because accessing your Evernotes takes too long. Notes provides a minimal syntax for manipulating notes from any working directory. You choose the file extension for your notes (the default is <a href="http://daringfireball.net/projects/markdown/">.md</a>), but for convenience Notes' syntax is completely independent of this extension. Combined with an editor like <a href="http://macdown.uranusjr.com/">MacDown</a>, or Sublime Text with a plugin like <a href="https://github.com/SublimeText-Markdown/MarkdownEditing">MarkdownEditing</a>, Notes recovers the formatting goodness you would otherwise lose switching from a note-taking app to plain text. Place your notes directory in your Dropbox or something similar to get syncing, versioning, and access from everywhere. Check it out <a href="https://github.com/kylebebak/notes">here</a>.</li>

	<li><span class="content-item">LSE</span> - <a href="https://github.com/kylebebak/other-scripts">A homemade version</a> of the Unix built-in <strong>which</strong> with extra bells and whistles.</li>

	<li><span class="content-item">CLists, ComPer, XOR</span> - <a href="https://github.com/kylebebak/math-scripts">Python shell scripts</a> for comparing two or more lists (intersection, difference, symmetric difference, and cartesian product), computing combinations and permutations of a list, and computing bitwise XOR of two or more strings.</li>
</ul>



<a class="anchor" name="projects"></a>
<h2>Projects</h2>

<ul>
	<li><span class="content-item">Tortas</span> - Pon tu propio changarro de tortas. Genera un <a href="tortas">menú</a> digno de los mejores puestos, sumamente variado, internacional y sin sentido alguno.</li>

	<li><p><span class="content-item">Location History</span> - Unless you've opted out of location services on your phone, Google (or Apple or Microsoft) is probably <a href="http://www.howtogeek.com/195647/googles-location-history-is-still-recording-your-every-move/">tracking your location</a>, in Google's case once a minute and accurate to 5 or 10 meters. There used to be an API for accessing this data, but now the best you can do is download your raw location history for a range of dates as KML or JSON. I did this, ran a clustering algorithm similar to DBSCAN on the data to create visit, location and trip instances, and put them in a MySQL database.</p>

	<p>When run on my location data during the last year and a half, this process turned up 4400 visits (clusters of points where I'd been stationary somewhere for more than 6 minutes) that correspond to 750 unique locations. Trips are the sequences of moving points that occur between visits. The data has been a lot of fun to explore. Probed with simple queries it can answer interesting aggregate questions, like where are the top five places I spend time on Saturdays, or, during the last year, at what time on average have I left work on each of the different weekdays.</p>

	<p>I built a front end for this data using the Google Maps API and a bunch of plugins. The data doesn't reveal anything that makes me uncomfortable, so I've made it available <a href="location_history">here</a>.</p></li>
</ul>


<a class="anchor" name="Writing"></a>
<h2>Writing</h2>

<ul>
	<li>Coming soon</li>

	<li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum veniam ipsa optio dolores exercitationem, deserunt necessitatibus voluptates architecto dolore, pariatur incidunt, quos sequi harum. Itaque labore a, quisquam corporis eveniet. Lorem ipsum dolor sit amet, consectetur adipisicing elit. At quae, rem voluptate labore, ex reiciendis impedit culpa cum quam dolor, maxime corporis recusandae? Blanditiis accusantium placeat quidem illum rem obcaecati? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi illum corrupti mollitia, asperiores et laudantium vero delectus voluptatem, qui repudiandae temporibus explicabo! Nobis praesentium rerum, nam facere dignissimos ullam debitis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus maiores nam deleniti quaerat consectetur tempora veniam cum voluptatibus laborum praesentium, fuga sint ducimus vero quia quam nostrum ea voluptate nesciunt? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam natus officiis perspiciatis dolore, facilis esse sunt? Impedit eos dolores nesciunt ipsum, sit sunt adipisci cupiditate amet, mollitia molestias libero omnis.</li>
</ul>


</main>

<footer>dronfelipe <span id="footer-text">centro</span> histórico</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js"></script> -->
<script src="js/scripts.js"></script>




</body>
</html>



