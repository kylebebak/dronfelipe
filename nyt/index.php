<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8>
	<title>dronfelipe/nyt</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
	<!-- last stylesheet loaded takes precedence, which means custom styles are not overwritten by bootstrap -->
	<link rel="stylesheet" href="css/style.css">
</head>
<body>



<?php include '../__partials/navbar-left.html'; ?>

  <ul class="nav navbar-nav navbar-centered">

    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">most <span class="caret"></span></a>
      <ul class="dropdown-menu" role="menu" id="resource">
        <li><a href="#" id="mostviewed" data-resource="mostviewed">viewed</a></li>
        <li><a href="#" id="mostemailed" data-resource="mostemailed">emailed</a></li>
        <li><a href="#" id="mostshared" data-resource="mostshared">shared</a></li>
      </ul>
    </li>

    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">last <span class="caret"></span></a>
      <ul class="dropdown-menu" role="menu" id="period">
        <li><a href="#" id="1" data-period="1">day</a></li>
        <li><a href="#" id="7" data-period="7">week</a></li>
        <li><a href="#" id="30" data-period="30">month</a></li>
      </ul>
    </li>

    <li>
      <form class="navbar-form" role="search" action="#">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="search" name="q" id="q">
        </div>
      </form>
    </li>

    <li>
      <ul class="nav pagination">
        <li>
          <a href="#" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li>
          <a href="#" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </li>

  </ul>

</nav>



<ul class="articles">

</ul>





<!-- HANDLEBARS templating -->
<script id="articles-template" type="text/x-handlebars-template">
	{{#each this}}
		<li>
			{{#if thumb}}
				<img src="{{thumb}}" {{#if caption}}alt="{{{caption}}}" title="{{{caption}}}"{{/if}}>
			{{/if}}
			<h3>
				<a href="{{url}}">{{{title}}}</a>
			</h3>
			<h4>
			{{#if byline}}{{{byline}}}, {{/if}}{{published_date}}
			</h4>
			<p>{{{abstract}}}</p>
		</li>
	{{/each}}
</script>









<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js"></script>
<script src="js/scripts.js"></script>


</body>
</html>



