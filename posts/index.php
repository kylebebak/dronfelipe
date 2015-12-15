<?php include '../__partials/header.html'; ?>

<?php include '../__partials/navbar-left.html'; ?>
<?php include '../__partials/navbar-right.html'; ?>


<?php

include_once 'models/Post.php';

parse_str($_SERVER['QUERY_STRING']);

$query = 'SELECT
  id, written, slug, name, description, content, created, updated
  FROM post
  WHERE slug = ?';

$post = $db->rawQueryOne($query, Array($p));

if(isset($post)) {
  echo $post['content'];
} else {
  include "404.php";
}

?>

<script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=AM_HTMLorMML-full"></script>


</body>
</html>

