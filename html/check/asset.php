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

<article>
<?php

parse_str($_SERVER['QUERY_STRING']);
include $_SERVER['DOCUMENT_ROOT'] . "/posts/assets/$a.php";

?>
</article>

<script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=AM_HTMLorMML-full"></script>

</body>
</html>
