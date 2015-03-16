<?php include '../__partials/header.html'; ?>

<?php include '../__partials/navbar-left.html'; ?>
<?php include '../__partials/navbar-right.html'; ?>


<main>

	<section id="videos">
		<h2>Videos</h2>

		<ul>
			<li><a href="https://www.youtube.com/watch?v=-_JWAh0lP8Q">foxes and rabbits</a></li>
			<li><a href="https://www.youtube.com/watch?v=hntcMg32I0s">percolation in 2d</a></li>
			<li><a href="https://www.youtube.com/watch?v=X5lpo9nY_jQ">percolation in 3d</a></li>
		</ul>
	</section>

	<section id="images">
		<h2>Images</h2>

		<ul>
			<li><img data-name="face.png" data-src="img/thumbs/face.jpg"></li>
			<li><img data-name="letters.pdf" data-src="img/thumbs/letters.jpg"></li>
			<li><img data-name="lettersRed.pdf" data-src="img/thumbs/lettersRed.jpg"></li>
			<li><img data-name="percolation.png" data-src="img/thumbs/percolation.jpg"></li>
		</ul>
	</section>


</main>





<?php include '../__partials/footer.html'; ?>


<!-- this script must be loaded after jquery, because it's a jquery plugin which modifies the jquery object -->
<script src="../lib/js/jquery.unveil.js"></script>
