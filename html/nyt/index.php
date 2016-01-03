<!DOCTYPE html>
<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include "${root}/__partials/header.html";
include "${root}/__partials/navbar-left.html"; ?>

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



<?php include "${root}/__partials/footer.html"; ?>
