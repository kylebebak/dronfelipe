<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../config/nyt.php";


echo json_encode(array($nyt_most_popular_key, $nyt_article_search_key));


?>
