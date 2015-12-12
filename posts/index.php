<?php include '../__partials/header.html'; ?>

<?php include '../__partials/navbar-left.html'; ?>
<?php include '../__partials/navbar-right.html'; ?>


<?php

parse_str($_SERVER['QUERY_STRING']);
if (file_exists("$p.php")) {
  include "$p.php";
} else {
  include "404.php";
}

?>

<!-- <script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script> -->
<script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=AM_HTMLorMML-full"></script>


</body>
</html>

