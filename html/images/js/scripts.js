// must be called on document ready

$(function() {
	$("li img").unveil(100, function() {

		var $this = $(this)

	  $this.load(function() {
    	$this.animate({
    		opacity: 1
    	}, 400, function() {

    		$this.on('click', function() {
    			// this is equivalent to clicking on an image wrapped in an anchor tag
    			window.location.href = "img/" + $this.data("name");
    		});


    	});
  	});
	});
});

