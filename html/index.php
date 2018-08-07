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

	      <li><a href="tortas"><span class="menu-item">tortas</span></a></li>
	      <li><a href="images"><span class="menu-item">images</span></a></li>
	      <li><a href="location_history"><span class="menu-item">location history</span></a></li>
	      <li role="separator" class="divider"></li>
	      <li><a href="http://kylebebak.github.io"><span class="menu-item">posts</span></a></li>
	      <li><a href="about"><span class="menu-item">about</span></a></li>

	    </ul>
	  </li>
	</ul>

	<ul class="nav navbar-nav navbar-right">
	</ul>

</nav>

<main>

<a class="anchor" name="posts-anchor" id="first-anchor"></a>
<h2>Posts</h2>

<ul id="posts">
</ul>

<a class="anchor" name="code-anchor"></a>
<h2>Code</h2>

<div id="code">
</div>

<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include "${root}/__partials/license.html";
?>

</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>

</body>
</html>
