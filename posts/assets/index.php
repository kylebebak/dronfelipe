<html>
<head>
  <meta charset=utf-8>
  <title>dronfelipe</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../__partials/css/style.css">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php

parse_str($_SERVER['QUERY_STRING']);
if (file_exists("$a.php")) {
  echo "<article>";
  include "$a.php";
  echo "</article>";
} else {
  include "404.php";
}

?>

<script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=AM_HTMLorMML-full"></script>


</body>
</html>

