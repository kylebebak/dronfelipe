(function() {

	var home = $('#home'),
		menu_name = $('.menu-name'),
			menu = $('.menu'),
				column_one = $('#menu-column-one'),
					column_two = $('#menu-column-two'),
						form = $('form'),
							nav = $('.nav-container'),
								content = $('.content'),
									body = $('body'),
										slide_speed = 600,
											defaults = {'num_tortas': 15, 'num_ingredients': 3},
												mins = {'num_tortas': 1, 'num_ingredients': 1},
													maxes = {'num_tortas': 100, 'num_ingredients': 5};


	// listen for form submission rather than button click event, send form params to controller and return json with restaurant name and updated menu, split into two columns
	form.on('submit', function(e) {
		e.preventDefault();

		// validate input. it might be nice to write a reusable function with this code, because it's sort of nice
		var values = {},
			postString = "";

		$.each($(this).serializeArray(), function(i, input) {
			if (!$.isNumeric(input.value)) {
				values[input.name] = defaults[input.name];
			} else {
				values[input.name] = Math.floor(input.value);
			}

			values[input.name] = Math.min(Math.max(values[input.name], mins[input.name]), maxes[input.name]);

			// set value for input element in DOM
			$('#' + input.name).val(values[input.name]);

			// add to post string
			postString += input.name + "=" + values[input.name] + "&";
		});
		postString = postString.substring(0, postString.length - 1);


		// post validated input variables in post string to tortas controller
		$.post('controllers/tortas.php', postString, function(data) {
			var json = $.parseJSON(data);

			menu_name.text(json[0]);
			column_one.children("ul").html(json[1]);
			column_two.children("ul").html(json[2]);

			body.css("padding-top", function() {
				return nav.outerHeight() + 20;
			});

			// the slideDown method is invoked within the callback that processes the data returned by the controller. this way, slideDown is invoked after the html is inserted into the DOM by this callback. if this method isn't invoked here, the $.post() method, which is asynchronous, does not return in time to calculate the height of the menu divs based on the new content
			menu.slideDown(slide_speed);

		});

	});


	// when button is clicked, slide up menu, update it, slide it down
	$('input#menu').on('click', function(e) {
		e.preventDefault();

		// use promise to make sure callback is only invoked once, even though there are two DOM elements matched by the menu selector and which are slid up (the two columns)
		menu.slideUp(slide_speed).promise().done(function() {
			form.submit();
		});

	});


	// submit form on page load. the event listener above will be triggered and send the form's default values to the controller. menu is hidden by default and must be slid down on page load
	form.submit();


})();
