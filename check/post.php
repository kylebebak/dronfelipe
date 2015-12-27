<!DOCTYPE html>
<html>
<head>
  <meta charset=utf-8>
  <title>dronfelipe</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../__partials/css/style.css">
  <link rel="stylesheet" href="../posts/css/style.css">
</head>
<body>

<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include "${root}/__partials/navbar-left.html";
include "${root}/__partials/navbar-right.html"; ?>

<article>
<?php

parse_str($_SERVER['QUERY_STRING']);
include "${root}/posts/$p.php";

?>
</article>

<script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=AM_HTMLorMML-full"></script>

</body>
</html>
