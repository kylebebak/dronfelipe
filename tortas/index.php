<!DOCTYPE html>
<html>
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>dronfelipe/tortas</title>

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <!-- <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css"> -->

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="/lib/css/normalize.css">
  <link rel="stylesheet" href="/lib/css/skeleton.css">
  <link rel="stylesheet" href="css/style.css">



</head>








<body>

	<div class="container">

		<div class="row nav-container">

			<div class="one column" id="home">
				<a href="..">dronfelipe</a>
			</div>

			<div class="four columns offset-by-one">
				<h2 class="menu-name">

				</h2>
			</div>

			<form method="post">
				<div class="two columns offset-by-one">
					<label for="num_tortas">tortas</label>
					<input class="u-full-width" type="number" value="15" min="1" max="100" id="num_tortas" name="num_tortas">
				</div>
				<div class="two columns">
					<label for="num_ingredients">ingredientes</label>
					<input class="u-full-width" type="number" value="3" min="1" max="5" id="num_ingredients" name="num_ingredients">
				</div>

				<div class="one column">
					<input class="button-primary button-in-line" type="submit" id="menu" value="menú">
				</div>
			</form>

		</div>


		<div class="row content">

			<div class="four columns offset-by-two menu" id='menu-column-one'>
				<ul class='menu-items'>
					<!-- insert first half of menu here -->

				</ul>
			</div>

			<div class="four columns menu" id='menu-column-two'>
				<ul class='menu-items'>
					<!-- insert second half of menu here -->

				</ul>
			</div>

		</div>
	</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>



</body>
</html>
