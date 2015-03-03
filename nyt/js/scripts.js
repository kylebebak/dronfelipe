(function() {



	var NYTSearch = {

		init: function(config) {
			this.resource = config.resource;
			this.period = config.period;
			this.query = config.query;
			this.searchDelay = config.searchDelay;
			this.maxThumbWidth = config.maxThumbWidth;

			this.url = '';
			this.articles = [];
			this.page = 0;
			this.timer;


			this.cache();
			this.bindEvents();
			this.subscriptions();


			this.resourceOptions.find('a#' + this.resource).css("font-weight", "bold");
			this.periodOptions.find('a#' + this.period).css("font-weight", "bold");
			this.mostPopularKey = '';
			this.articleSearchKey = '';

			var self = this;
			// use server-side controller to retrieve api key from config file, which is outside of document root. because it is declared within a siaf, the NYTSearch var is not part of the global scope, and hence its properties are private
			$.post( "controllers/config.php", function( data ) {
			  var json = $.parseJSON(data);
			  self.mostPopularKey = json[0];
			  self.articleSearchKey = json[1];

				$(document).trigger( 'nyt/query' );
			});


			return this;
		},




		cache: function() {
			this.container = $('ul.articles');
			this.searchInput = $('input#q');
			this.template = $('#articles-template').html();
			this.resourceOptions = $('ul#resource');
			this.periodOptions = $('ul#period');
			this.previous = $('ul.pagination li:first-child a');
			this.next = $('ul.pagination li:last-child a');
		},


		bindEvents: function() {
			self = NYTSearch;

			this.searchInput.on( 'keyup', function() {
				self.search.call(this, self.searchDelay);
			});

			this.searchInput.on( 'keypress', function(e) {
				if (e.keyCode == 13) {
        	e.preventDefault();
        	self.search.call(this, 0);
    		}
			});

			this.resourceOptions.on('click', 'a', function() {
				self.resource = $(this).data('resource');

				$(this).css("font-weight", "bold")
					.parent().siblings().children('a').css("font-weight", "normal");

				self.page = 0;
				$(document).trigger( 'nyt/query' );
			});

			this.periodOptions.on('click', 'a', function() {
				self.period = $(this).data('period');

				$(this).css("font-weight", "bold")
					.parent().siblings().children('a').css("font-weight", "normal");

				self.page = 0;
				$(document).trigger( 'nyt/query' );
			});

			this.previous.on('click', function() {
				self.page = Math.max(0, self.page - 1);
				$(document).trigger( 'nyt/query' );
			});

			this.next.on('click', function() {
				self.page += 1;
				$(document).trigger( 'nyt/query' );
			});
		},


		subscriptions: function() {
			$(document).on( 'nyt/query', this.fetchJSON );
			$(document).on( 'nyt/results', this.renderResults );
		},


		search: function(delay) {
			var self = NYTSearch,
				input = this;

			clearTimeout( self.timer );

			self.timer = ( input.value.length >= 3 ) && setTimeout(function() {
				self.resource = 'articlesearch';
				self.query = input.value;
				self.page = 0;
				$(document).trigger( 'nyt/query' );
			}, delay);
		},


		buildURL: function() {
			var self = NYTSearch;
			if (self.resource === 'articlesearch') {
				self.url = 'http://api.nytimes.com/svc/search/v2/articlesearch.json?api-key=' + self.articleSearchKey + '&page=' + self.page + '&q=' + self.query;
			} else {
				self.url = 'http://api.nytimes.com/svc/mostpopular/v2/' + self.resource + '/all-sections/' + self.period + '.jsonp?api-key=' + self.mostPopularKey + '&offset=' + (self.page * 20) + '&callback=?';
			}
		},


		fetchJSON: function() {
			var self = NYTSearch;
			self.buildURL();

			return $.getJSON( self.url, function( data ) {
				if (self.resource === 'articlesearch') {
					self.articles = data.response.docs;
				} else {
					self.articles = data.results;
				}


				$(document).trigger( 'nyt/results' );

			});
		},







		renderResults: function() {

			var self = NYTSearch;


			if (self.resource === 'articlesearch') {

				self.arts = $.map(self.articles, function(article) {
	    		var thumbs = article.multimedia || '',
	    			by = article.byline || '',
	    				byline = by.original || '',
	    					pd = article.pub_date || '',
	    						published_date = pd.substring(0, 10),
	    							caption = '';


	    		return {
	    			url: article.web_url,
	    			byline: byline,
	    			title: article.headline.main,
	    			abstract: article.abstract,
	    			published_date: published_date,
	    			caption: caption,
	    			thumb: null,
	    			thumbs: thumbs
	    		};
	    	});
			}


			else {

	    	self.arts = $.map(self.articles, function(article) {
	    		var media = article.media[0] || '',
	    			caption = media.caption || '',
	    				thumbs = media['media-metadata'] || '';

	    		return {
	    			url: article.url,
	    			byline: article.byline,
	    			title: article.title,
	    			abstract: article.abstract,
	    			published_date: article.published_date,
	    			caption: caption,
	    			thumb: null,
	    			thumbs: thumbs
	    		};
	    	});
    	}



    	// find largest thumbnail available for article whose width is less than 400
    	$.each(self.arts, function(ia, article) {

    		var w = 0;
    		if (article.thumbs) {
    			$.each(article.thumbs, function(it, thumb) {
    				if (thumb.width > w && thumb.width < self.maxThumbWidth) {
    					w = thumb.width;
    					article.thumb = thumb.url;

    					if (self.resource === 'articlesearch') {
    						article.thumb = 'http://graphics8.nytimes.com/' + article.thumb;
    					}
    				}
    			});
    		}
    	});




			self.container.html('');
    	var template = Handlebars.compile(self.template);
			self.container.append(template(self.arts));

			// set min-height of each list item to height of its thumbnail if it has one
			$('ul.articles').find('li').children('img').load(function() {
				var img = $(this),
					h = img.height(),
						w = img.width(),
							wBody = $('body').width();

				if (w > wBody) {
					img.css("width", function() {
						return wBody - 32;
					});
				}

				img.parent().css("min-height", function() {
					return h + 32;
				});

			});


		}
	};


	var NYT = NYTSearch.init({
		resource: "mostviewed",
		period: '1',
		query: '',
		searchDelay: '1000',
		maxThumbWidth: '425'
	});


})();
