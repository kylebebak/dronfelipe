(function() {



  // enable location search form
  $('select#search-options').select2({
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

    // listen for when day picker is closed and assign LH callback to this event. can this listener be registered outside of the "constructor" for the daypicker form?
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

      this.defaultLimit = '30';
      this.zoomFromSearch = 15;
      this.highlightedMarkerID = '';


      this.query = {
        dateFilter: ['', ''],
        dayFilter: [],
        limit: this.defaultLimit
      };

      // size markers depending on their duration
      this.markerSize = {
        minScalingFactor: .3,
        maxW: 44,
        maxH: 70,
        maxWHighlighted: 108
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
      this.tripLine = '';
      this.maxDuration = '';
      this.global = {};
      this.resourceWindowAnimating = false;
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
      }).on('focusout', function() {
        self.setLimit.call(this);
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

      if (marker) {
        marker.setIcon({
          url: url,
          scaledSize: new google.maps.Size(maxW * marker.scalingFactor,
            maxH * marker.scalingFactor)
        });
      }

    },

    secondsToTime: function(totalSeconds, format) {

      format = typeof format !== 'undefined' ? format : 'time';

      var hours = Math.floor(totalSeconds / 3600),
        totalSeconds = totalSeconds % 3600,
          minutes = Math.floor(totalSeconds / 60),
            seconds = totalSeconds % 60;

      if (format === 'time') {
        return ("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(-2);
      } else if (format === 'duration') {
        return hours > 0 ? hours + "h " + minutes + "m" : minutes + "m";
      }
    },



    subscriptions: function() {
      $(document).on( 'LH/query', this.fetchJSON );
      $(document).on( 'LH/results', this.renderResults );
    },










    setLimit: function() {
      var self = LH;

      // ignore NaN and non-positive values
      if (isNaN(this.value) || this.value <= 0) {
        $(this).val(self.query.limit);
        return;
      }

      // only trigger ajax call if query has changed
      if (self.query.limit === this.value) {
        return;
      }

      self.query.limit = this.value;
      $(document).trigger( 'LH/query' );

    },

    setDateFilter: function() {
      var self = LH;

      // only trigger ajax call if query has changed
      if (self.query.dateFilter[0] === self.startDateInput.val() && self.query.dateFilter[1] === self.endDateInput.val()) {
        return;
      }

      self.query.dateFilter[0] = self.startDateInput.val();
      self.query.dateFilter[1] = self.endDateInput.val();

      $(document).trigger( 'LH/query' );

    },

    setDayFilter: function() {
      var self = LH,
        dayFilter = [];

      self.daysContainer.find("option:not(:selected)").each(function() {
        dayFilter.push($(this).val());
      });

      // only trigger ajax call if query has changed
      if ($(dayFilter).not(self.query.dayFilter).length === 0 && $(self.query.dayFilter).not(dayFilter).length === 0) {
        return;
      }

      self.query.dayFilter = dayFilter;

      $(document).trigger( 'LH/query' );

    },








    fetchJSON: function() {
      // grab LH singleton, because within this callback "this" refers to the document
      var self = LH;

      self.deleteResults();

      // pass query object literal to location controller
      $.post('controllers/main.php', self.query, function(data) {

        self.results = $.parseJSON(data);

        $.post('controllers/global.php', self.query, function(data) {

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

      self.searchOptions.find("option#placeholder").nextAll().remove();
      // this will fail to programmatically select any options, which will cause the placeholder to appear by default again
      self.searchOptions.select2("val", "");


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

        // fancy scaling to allow for markers with dynamic sizes
        var scalingFactor = Math.sqrt(Math.sqrt(result.duration / self.maxDuration) + Math.pow(self.markerSize.minScalingFactor, 2));

        var contentString = '<div class="info-window">' +

        // geocode_name and number of hours spent at this location as header
        '<p id="header">' + result.geocode_name +
        '<br><strong id="location-metrics">' + (result.duration / 3600).toFixed(1) + ' hours, ' + result.visits + ' visits, ' + self.secondsToTime(Math.round(result.duration / result.visits), 'duration') + ' avg</strong></p>' +
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
          '<div value="trips" class="resource">trips</div>' +
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
        google.maps.event.addListener(marker.infowindow, 'closeclick', function() {
          // make sure resourceWindow is closed along with infowindow
          self.closeInfoWindow();
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

      // make outermost infowindow container semi-transparent
      $('div.gm-style-iw').parent().css("opacity", "0.9");

      // set values of input elements in form to name and description retrieved from DB. nothing needs to be escaped here, because the val method expects and assigns a string to the input's value attribute. it does not expect or insert html into the DOM
      $('.info-window input#name').val(marker.data.name);
      $('.info-window input#description').val(marker.data.description);

      // add click listener to button in order to post to controller and update fields
      $('.info-window form').on('submit', function(e) {
        e.preventDefault();

        // update marker object with values submitted in form
        $.each($(this).serializeArray(), function(i, field) {
          marker.data[field.name] = field.value;
        });


        // pass values submitted in form to update controller and persist them in db
        $.post('controllers/update.php', $(this).serialize() + '&id=' + marker.data.id, function() {
          self.closeInfoWindow();
          // update data-search attribute in search option with values submitted in form
          self.searchOptions.children("[value='_" + marker.data.id + "']").attr("data-search", marker.data.name + " " + marker.data.description);
        });

      });

      // add click listener for displaying resources
      $('.info-window .resource').on('click', function() {


        // ensure that click listener is only available if there are no animations in progress. if click listener is available, the sequence of events is as follows: if a resource window is currently open, it is closed. then, if the resource requested is different from the previous resource, the method for rendering the content of this resource window is called. finally, and finally, the resource window for this new resource is opened
        if (!self.resourceWindowAnimating) {
          self.resourceWindowAnimating = true;

          var resource = $(this).attr("value"),
            oldResource = self.resourceWindow.attr("value");

          self.resourceWindow.fadeOut(function() {
            if (resource == oldResource) {
              self.resourceWindow.attr("value", "");
              self.resourceWindowAnimating = false;
            } else {
              self.renderResourceWindow[resource]().done(function() {
                self.resourceWindow.attr("value", resource);
                self.resourceWindow.fadeIn(function() {
                  self.resourceWindowAnimating = false;
                });
              });
            }
          })

        }
      });

    },


    closeInfoWindow: function() {
      var self = LH;

      self.marker.infowindow.close();
      self.resourceWindow.attr("value", "");
      self.resourceWindow.fadeOut();
      if (self.tripLine) {
        self.tripLine.setMap(null);
      }
    },




    renderResourceWindow: {

      visits: function() {
        var self = LH;

        return $.post('controllers/visits.php', self.query, function(data) {

          var json = $.parseJSON(data);

          var html = '<h4 id="visits-header">average arrival to this location: ' + self.secondsToTime(json.mean_times.start) + "<br>" +
          "average departure from this location: " + self.secondsToTime(json.mean_times.end) + "</h4>";

          html += '<table id="visits-table"><tr>' +
            "<th>date</th>" +
            "<th>arrival</th>" +
            "<th>duration</th>" +
            "</tr>";

          $.each(json.results, function(index, val) {
            html += '<tr>' +
              "<td>" + val.start_date + "</td>" +
              "<td>" + val.start_time + "</td>" +
              "<td>" + self.secondsToTime(val.duration, 'duration') + "</td>" +
              "</tr>";
          });

          html += '</table>';

          self.resourceWindow.html(html);
        });
      },

      graph: function() {
        var self = LH;
        self.resourceWindow.html("");

        return $.post('controllers/graph.php', self.query, function(data) {

          var json = $.parseJSON(data);

          json = MG.convert.date(json, 'date');

          // no title in order to save space
          MG.data_graphic({
            data: json,
            missing_is_zero: true,
            width: 1000,
            height: 250,
            target: ".resource-window",
            max_y: 25,
            x_accessor: "date",
            y_accessor: "duration",
            interpolate: "monotone"
          });

        });
      },

      trips: function() {
        var self = LH;

        return $.post('controllers/trips.php', self.query, function(data) {

          var json = $.parseJSON(data);

          // add header html to resource window
          self.resourceWindow.html(
            '<div class="header"><div class="box" id="header"><h3>average trips starting from this location</h3></div>' +
            '<div class="box" id="select"><select name="" id="">' +
              '<option value="start_aggregate">average trips starting from this location</option>' +
              '<option value="start_all">all trips starting from this location</option>' +
              '<option value="end_aggregate">average trips ending at this location</option>' +
              '<option value="end_all">all trips ending at this location</option>' +
              '<option value="start_end_all">all trips in chronological order for this location</option>' +
            '</select></div></div>');


          // add event listener to select dropdown, and trigger it immediately
          self.resourceWindow.find("select").on("change", function() {
            var option = $(this).find(":selected");

            self.resourceWindow.find("h3").text(option.text());
            var html = self.renderResourceWindow.renderTripsTable[option.val()](json);

            // remove and reinsert table, and add click listener to any tr element
            self.resourceWindow.find("table").remove();
            self.resourceWindow.append(html).find("table").on("click", "tr.selectable", function() {

              self.renderResourceWindow.setTripLineParameters($(this).attr("data-sid"), $(this).attr("data-eid"));

              if (self.markers[self.tripLineParams.val]) {
                self.openMarker(self.markers[self.tripLineParams.val]);
                self.searchOptions.select2("val", self.tripLineParams.val);
              }
            })
            .on("mouseenter", "tr.selectable", function() {

              self.renderResourceWindow.setTripLineParameters($(this).attr("data-sid"), $(this).attr("data-eid"));

              if (self.tripLine) {
                self.tripLine.setMap(null);
              }

              if (self.tripLineParams.round) {
                self.tripLine = new google.maps.Circle({
                  center: self.marker.position,
                  radius: 800,
                  strokeColor: self.tripLineParams.color,
                  strokeOpacity: 1.0,
                  strokeWeight: 4,
                });
                self.tripLine.setMap(self.map);
              }

              else if (self.markers[self.tripLineParams.val]) {
                self.tripLine = new google.maps.Polyline({
                  path: [self.marker.position, self.markers[self.tripLineParams.val].position],
                  geodesic: true,
                  strokeColor: self.tripLineParams.color,
                  strokeOpacity: 1.0,
                  fillColor: '#FFFFFF',
                  fillOpacity: 0.0,
                  strokeWeight: 4
                });
                self.tripLine.setMap(self.map);
              }
            });

          // if this event is not triggered, trips resource window will not load content when it is first opened
          }).change();

        });
      },

      setTripLineParameters: function(sid, eid) {
        var self = LH,
          val,
            color,
              mid = String(self.marker.data.id);

        self.tripLineParams = {};

        if (sid === eid) {
          val = mid;
          color = '#FF00FF';
          self.tripLineParams.round = "true";
        } else if (sid === mid) {
          val = eid;
          color = '#00FF00';
        } else if (eid === mid) {
          val = sid;
          color = '#0000FF';
        }

        self.tripLineParams.val = "_" + val;
        self.tripLineParams.color = color;
      },


      renderTripsTable: {

        start_aggregate: function(json) {
          var self = LH;

          var html = '<table id="trips-table"><tr>' +
            "<th>trips</th>" +
            "<th>destination</th>" +
            "<th>departure</th>" +
            "<th>duration</th>" +
            "<th>distance (km)</th>" +
            "</tr>";

          $.each(json.start_aggregate, function(index, val) {
            html += '<tr class="selectable" data-sid="' + self.marker.data.id + '" data-eid="' + val.end_location_id + '">' +
              "<td>" + val.count_lid + "</td>" +
              "<td>" + val.name + "</td>" +
              "<td>" + self.secondsToTime(val.start_time) + "</td>" +
              "<td>" + self.secondsToTime(val.duration, 'duration') + "</td>" +
              "<td>" + (val.distance / 1000).toFixed(2) + "</td>" +
              "</tr>";
          });

          html += "</table>";
          return html;
        },


        start_all: function(json) {
          var self = LH;

          var html = '<table id="trips-table"><tr>' +
            "<th>destination</th>" +
            "<th>date</th>" +
            "<th>departure</th>" +
            "<th>duration</th>" +
            "<th>distance (km)</th>" +
            "</tr>";

          $.each(json.start_all, function(index, val) {
            html += '<tr class="selectable" data-sid="' + self.marker.data.id + '" data-eid="' + val.end_location_id + '">' +
              "<td>" + val.name + "</td>" +
              "<td>" + val.start_date + "</td>" +
              "<td>" + val.start_time + "</td>" +
              "<td>" + self.secondsToTime(val.duration, 'duration') + "</td>" +
              "<td>" + (val.distance / 1000).toFixed(2) + "</td>" +
              "</tr>";
          });

          html += "</table>";
          return html;
        },


        end_aggregate: function(json) {
          var self = LH;

          var html = '<table id="trips-table"><tr>' +
            "<th>trips</th>" +
            "<th>starting location</th>" +
            "<th>arrival</th>" +
            "<th>duration</th>" +
            "<th>distance (km)</th>" +
            "</tr>";

          $.each(json.end_aggregate, function(index, val) {
            html += '<tr class="selectable" data-sid="' + val.start_location_id + '" data-eid="' + self.marker.data.id + '">' +
              "<td>" + val.count_lid + "</td>" +
              "<td>" + val.name + "</td>" +
              "<td>" + self.secondsToTime(val.end_time) + "</td>" +
              "<td>" + self.secondsToTime(val.duration, 'duration') + "</td>" +
              "<td>" + (val.distance / 1000).toFixed(2) + "</td>" +
              "</tr>";
          });

          html += "</table>";
          return html;
        },


        end_all: function(json) {
          var self = LH;

          var html = '<table id="trips-table"><tr>' +
            "<th>starting location</th>" +
            "<th>date</th>" +
            "<th>arrival</th>" +
            "<th>duration</th>" +
            "<th>distance (km)</th>" +
            "</tr>";

          $.each(json.end_all, function(index, val) {
            html += '<tr class="selectable" data-sid="' + val.start_location_id + '" data-eid="' + self.marker.data.id + '">' +
              "<td>" + val.name + "</td>" +
              "<td>" + val.end_date + "</td>" +
              "<td>" + val.end_time + "</td>" +
              "<td>" + self.secondsToTime(val.duration, 'duration') + "</td>" +
              "<td>" + (val.distance / 1000).toFixed(2) + "</td>" +
              "</tr>";
          });

          html += "</table>";
          return html;
        },

        start_end_all: function(json) {
          var self = LH;

          var html = '<table id="trips-table"><tr>' +
            "<th>starting location</th>" +
            "<th>ending location</th>" +
            "<th>date</th>" +
            "<th>departure</th>" +
            "<th>duration</th>" +
            "<th>distance (km)</th>" +
            "</tr>";

          $.each(json.start_end_all, function(index, val) {
            html += '<tr class="selectable" data-sid="' + val.start_location_id + '" data-eid="' + val.end_location_id + '">' +
              "<td>" + val.start_name + "</td>" +
              "<td>" + val.end_name + "</td>" +
              "<td>" + val.start_date + "</td>" +
              "<td>" + val.start_time + "</td>" +
              "<td>" + self.secondsToTime(val.duration, 'duration') + "</td>" +
              "<td>" + (val.distance / 1000).toFixed(2) + "</td>" +
              "</tr>";
          });

          html += "</table>";
          return html;
        }

      },


      global: function() {
        var self = LH;

        self.resourceWindow.html('<ul>' +
            '<li class="header">visits' +
              '<ul>' +
                '<li>' + self.global.visits.num + '</li>' +
                '<li>' + (self.global.visits.duration / 3600).toFixed(0) + ' hours</li>' +
              '</ul>' +
            '</li>' +
            '<li class="header">trips' +
              '<ul>' +
                '<li>' + self.global.trips.num + '</li>' +
                '<li>' + (self.global.trips.duration / 3600).toFixed(0) + ' hours</li>' +
                '<li>' + (self.global.trips.distance / 1000).toFixed(0) + ' km</li>' +
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
