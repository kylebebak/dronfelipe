(function() {



  // enable location search form
  $('select#search-options').select2({
    placeholder: 'search locations',
    dropdownCssClass: 'wide-dropdown',
    width: "200",
    matcher: function(term, text, opt) {
      return text.toUpperCase().indexOf(term.toUpperCase()) >= 0 ||
      opt.attr("data-search").toUpperCase().indexOf(term.toUpperCase()) >= 0;
    }
  });


  // enable day picker form
  $('li#weekdays-container select').multiselect({
    enableClickableOptGroups: true,

    // listen for when day picker is closed and assign LH callback to this even. can this listener be registered outside of the "constructor" for the daypicker form?
    onDropdownHide: function(event) {
      LH.setDayFilter();
    }
  });


  // enable datepicker form
  $('div#datepicker').datepicker({
    clearBtn: true
  });






  var LH = {

    init: function() {

      this.defaultLimit = '25';
      this.zoomFromSearch = 15;
      this.highlightedMarkerID = '';


      this.query = {
        dateFilter: ['', ''],
        dayFilter: [],
        limit: this.defaultLimit
      };

      // size markers depending on their duration
      this.markerSize = {
        minScalingFactor: .35,
        maxW: 42,
        maxH: 68,
        maxWHighlighted: 103
      }

      if (!google.loader.ClientLocation) {
        this.coords = new google.maps.LatLng(19.378384, -99.170976);
      } else {
        this.coords = new google.maps.LatLng(google.loader.ClientLocation.latitude, google.loader.ClientLocation.longitude);
      }
      this.mapOptions = {
        center: this.coords,
        zoom: 13
      };
      this.map = new google.maps.Map(document.getElementById('map-canvas'), this.mapOptions);



      this.initializeResults();
      this.cache();
      this.bindEvents();
      this.subscriptions();

      $(document).trigger( 'LH/query' );

      return this;

    },






    initializeResults: function() {
      this.results = '';
      this.markers = {};
      this.marker = '';
      this.maxDuration = '';
      this.global = {};
      this.visits = '';
    },


    cache: function() {
      this.searchOptions = $('select#search-options');
      this.limitInput = $('input#limit');
      this.daysContainer = $('li#weekdays-container');
      this.datepicker = $('div#datepicker');
      this.startDateInput = this.datepicker.find("input#start");
      this.endDateInput = this.datepicker.find("input#end");
      this.endDateInput = this.datepicker.find("input#end");
      this.resourceWindow = $('.resource-window');
    },


    bindEvents: function() {
      var self = LH;

      // allow for closing info window with escape key
      $(document).on('keydown', function(e) {
        if (e.which == 27) {

          if (self.marker) {
            self.closeInfoWindow();
          }
        }
      });


      // limit input responds on enter key, DOES THIS WORK FOR MOBILE?
      self.limitInput.on('keydown', function(e) {
        if (e.keyCode == 13) {
          e.preventDefault();
          self.setLimit.call(this);
        }
      });


      // datepicker responds every time it is closed, just like the day picker
      self.datepicker.on('hide', function(e) {
        e.preventDefault();
        self.setDateFilter();
      });


      // open marker from search options
      self.searchOptions.on("change", function(e) {
        self.map.setZoom(self.zoomFromSearch);
        self.openMarker(self.markers[e.val]);


      // highlight marker on hover
      }).on("select2-highlight", function(e) {

        if (self.highlightedMarkerID) {
          self.changeMarkerImage(self.markers[self.highlightedMarkerID], 'img/marker-red.png', self.markerSize.maxW, self.markerSize.maxH);
        }

        self.highlightedMarkerID = e.val;
        self.changeMarkerImage(self.markers[self.highlightedMarkerID], 'img/marker-red-highlighted.png', self.markerSize.maxWHighlighted, self.markerSize.maxH);

      // unhighlight marker on close
      }).on("select2-close", function() {

        if (self.highlightedMarkerID) {
          self.changeMarkerImage(self.markers[self.highlightedMarkerID], 'img/marker-red.png', self.markerSize.maxW, self.markerSize.maxH);
        }

      });

    },


    changeMarkerImage: function(marker, url, maxW, maxH) {
      var self = LH;

      marker.setIcon({
        url: url,
        scaledSize: new google.maps.Size(maxW * marker.scalingFactor,
          maxH * marker.scalingFactor)
      });

    },

    secondsToTime: function(totalSeconds) {
      var hours = Math.floor(totalSeconds / 3600),
        totalSeconds = totalSeconds % 3600,
          minutes = Math.floor(totalSeconds / 60),
            seconds = totalSeconds % 60;

      // return hours + "h " + minutes + "m " + seconds + "s";
      return hours + "h " + minutes + "m ";
    },



    subscriptions: function() {
      $(document).on( 'LH/query', this.fetchJSON );
      $(document).on( 'LH/results', this.renderResults );
    },










    setLimit: function() {
      var self = LH;

      // coerce input to be positive number
      if (isNaN(this.value) || this.value <= 0) {
        $(this).val(self.defaultLimit);
      }

      self.query.limit = this.value;
      $(document).trigger( 'LH/query' );

    },

    setDateFilter: function() {
      var self = LH;

      self.query.dateFilter = [];

      self.query.dateFilter[0] = self.startDateInput.val();
      self.query.dateFilter[1] = self.endDateInput.val();

      $(document).trigger( 'LH/query' );

    },

    setDayFilter: function() {
      var self = LH;

      self.query.dayFilter = [];

      self.daysContainer.find("option:not(:selected)").each(function() {
        self.query.dayFilter.push($(this).val());
      });

      $(document).trigger( 'LH/query' );

    },








    fetchJSON: function() {
      // grab LH singleton, because within this callback "this" refers to the document
      var self = LH;

      self.deleteResults();

      // pass query object literal to location controller
      $.post('controllers/location_history.php', self.query, function(data) {

        self.results = $.parseJSON(data);

        $.post('controllers/global_metrics.php', self.query, function(data) {

          var json = $.parseJSON(data);
          self.global.visits = json.visits[0];
          self.global.trips = json.trips[0];

          $(document).trigger( 'LH/results' );

        });

      });


    },






    // remove old search options from the DOM, and add new options from reinstantiated markers
    rebuildSearchOptions: function() {
      var self = LH;

      self.searchOptions.find("option").remove();

      $.each(self.markers, function(id, marker) {
        self.searchOptions.append('<option value="' + id + '" data-search="' + marker.data.name + ' ' + marker.data.description + '">' + marker.data.geocode_name + '</option>');
      });
    },

    deleteResults: function() {
      var self = LH;

      // first clear markers from map, by setting their map property to null
      $.each(self.markers, function(id, marker) {
        marker.setMap(null);
      });

      // close info window
      if (self.marker) {
        self.closeInfoWindow();
      }

      // initialize all fields related to results
      self.initializeResults();
    },


    renderResults: function() {

      // grab LH singleton, because within this callback "this" refers to the document
      var self = LH;

      // rebuild (remove) search options and return immediately if no results were returned by query
      if (!self.results.length) {
        self.rebuildSearchOptions();
        return;
      }

      self.maxDuration = self.results[0].duration;


      // put markers on map for each location returned in result set
      self.results.forEach(function(result) {

        // fancy scaling algorithm to allow for markers with dynamic sizes
        var scalingFactor = Math.sqrt(Math.sqrt(result.duration / self.maxDuration) + Math.pow(self.markerSize.minScalingFactor, 2));

        var contentString = '<div class="marker-content">' +

        // geocode_name and number of hours spent at this location as header
        '<p id="header">' + result.geocode_name +
        '<br><strong id="location-metrics">' + (result.duration / 3600).toFixed(2) + ' hours, ' + result.visits + ' visits, ' + self.secondsToTime(Math.round(result.duration / result.visits)) + ' avg</strong></p>' +
        '<form action="#">' +
          '<div id="form-inputs">' +
            '<li>' +
              '<label for="name">name</label>' +
              '<input type="text" name="name" id="name">' +
            '</li>' +
            '<li>' +
              '<label for="description">description</label>' +
              '<input type="text" name="description" id="description">' +
            '</li>' +
          '</div>' +
          '<div id="button"><button>update</button></div>' +
          '<div value="visits" class="resource">visits</div>' +
          '<div value="graph" class="resource">graph</div>' +
          '<div value="global" class="resource">global</div>' +
        '</form>' +
        '</div>';

        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(result.lat, result.lon),
          map: self.map,
          title: result.name,
          data: result,
          infowindow: new google.maps.InfoWindow({
            content: contentString
          }),
          icon: {
            url: 'img/marker-red.png',
            scaledSize: new google.maps.Size(self.markerSize.maxW * scalingFactor,
              self.markerSize.maxH * scalingFactor)
          },
          scalingFactor: scalingFactor
        });

        // maintain an associate array with all markers. JS is like many languages in the sense that the marker added to this array is not reinstantiated. the array maintains a pointer to the marker, and if a marker which was passed to the mapping is updated, then this change is also reflected in the mapping
        self.markers["_" + result.id] = marker;





        // attach event listener to each marker as the marker is instantiated, and set value of options list according to location id of this marker
        google.maps.event.addListener(marker, 'click', function() {
          self.openMarker(marker);
          self.searchOptions.select2("val", "_" + marker.data.id);
        });
      });


      // now that markers have been reinstantiated, search options can be rebuilt
      self.rebuildSearchOptions();


    },




    openMarker: function(marker) {
      var self = LH;
      self.map.panTo(marker.getPosition());

      // close infowindow for current marker if it's open
      if (self.marker) {
        self.closeInfoWindow();
      }

      // reassign LH marker property to marker which has been clicked, and set location id for query object literal for this object
      self.marker = marker;
      self.query.location_id = self.marker.data.id;

      marker.infowindow.open(self.map, marker);

      // set values of input elements in form to name and description retrieved from DB. nothing needs to be escaped here, because the val method expects and assigns a string to the input's value attribute. it does not expect or insert html into the DOM
      $('.marker-content input#name').val(marker.data.name);
      $('.marker-content input#description').val(marker.data.description);





      // add click listener to button in order to post to location controller and update fields
      $('.marker-content form').on('submit', function(e) {
        e.preventDefault();

        // update marker object with values submitted in form
        $.each($(this).serializeArray(), function(i, field) {
          marker.data[field.name] = field.value;
        });


        // pass values submitted in form to update controller and persist them in db
        $.post('controllers/update_location.php', $(this).serialize() + '&id=' + marker.data.id, function() {
          self.closeInfoWindow();
        });

      });

      // add click listener for displaying resources
      $('.marker-content .resource').on('click', function() {
        var resource = $(this).attr("value");

        self.resourceWindow.fadeOut(function() {
          if (resource != self.resourceWindow.attr("value")) {

            var promise = self.renderResourceWindow[resource]();
            promise.done(function() {
              self.resourceWindow.fadeIn();
              self.resourceWindow.attr("value", resource);
            });
          } else {
            self.resourceWindow.attr("value", "");
          }
        });

      });

    },


    closeInfoWindow: function() {
      var self = LH;

      self.marker.infowindow.close();
      self.resourceWindow.attr("value", "");
      self.resourceWindow.fadeOut();
    },


    renderResourceWindow: {
      visits: function() {
        var self = LH,
          visits = "";

        return $.post('controllers/visits_to_location.php', self.query, function(data) {

          var json = $.parseJSON(data);

          visits += "<p><strong>arrive arrival and departure: " + self.secondsToTime(json.mean_times.start) + " -> " + self.secondsToTime(json.mean_times.end) + "</strong></p>";
          $.each(json.results, function(index, val) {
            visits += "<p>" + val.start_date + ", <strong>duration:</strong> " + self.secondsToTime(val.duration) + "</p>"
          });

          self.resourceWindow.html(visits);
        });
      },

      graph: function() {
        var self = LH;

        self.resourceWindow.html("graph");

        var dfd = $.Deferred();
        return dfd.resolve().promise();
      },

      global: function() {
        var self = LH;

        self.resourceWindow.html('<ul>' +
            '<li class="header">visits' +
              '<ul>' +
                '<li>number: ' + self.global.visits.num + '</li>' +
                '<li>duration: ' + (self.global.visits.duration / 3600).toFixed(2) + ' hours</li>' +
              '</ul>' +
            '</li>' +
            '<li class="header">trips' +
              '<ul>' +
                '<li>number: ' + self.global.trips.num + '</li>' +
                '<li>duration: ' + (self.global.trips.duration / 3600).toFixed(2) + ' hours</li>' +
              '</ul>' +
            '</li>' +
          '</ul>');

        var dfd = $.Deferred();
        return dfd.resolve().promise();
      }

    }

  }






  // initialize LH singleton
  google.maps.event.addDomListener(window, 'load', function() {
    var _LH = LH.init();
  });

})();
