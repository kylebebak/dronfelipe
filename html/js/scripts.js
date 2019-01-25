(function() {
  var baseSite = "https://kylebebak.github.io";
  var numPosts = 7;
  var fade = 700;

  // read post metadata from posts "API" and insert posts into DOM
  $.get(baseSite + "/feed.json", function(posts) {
    var container = $("ul#posts");

    $.each(posts.slice(0, numPosts), function(i, post) {
      $("<li class='post-item'><a href='" + post.url + "'>" + post.title + "</a></li>")
        .hide().appendTo(container).fadeIn(fade);
    });
    $("<li class='post-item'><a href='" + baseSite + "'>...more</a></li>")
      .hide().appendTo(container).fadeIn(fade);
  });

  $.get(baseSite + '/code', function(data) {
    var container = $("div#code");
    var code = $(".post-content>ul", $(data));

    code.hide().appendTo(container).fadeIn(fade);
  });

})();
