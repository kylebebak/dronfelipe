(function() {
  var baseSite = "http://kylebebak.github.io";
  var numPosts = 7;
  var fade = 700;

  $.get(baseSite, function(data) {
    var container = $("ul#posts");
    var posts = $("ul.post-list li a.post-link", $(data)).slice(0, numPosts);

    $.each(posts, function(i, val) {
      var link = $(val).attr('href');
      $(val).attr('href', baseSite + link);
      $("span", val).remove();
      $(val).wrapInner("<li class='post-item'></li>");
      $(val).hide().appendTo(container).fadeIn(fade);
    });

    $("<li><a href='" + baseSite + "'>...more</a></li>").hide().appendTo(container).fadeIn(fade);
  });

  $.get(baseSite + '/code', function(data) {
    var container = $("div#code");
    var code = $(".post-content>ul", $(data));

    code.hide().appendTo(container).fadeIn(fade);
  });

})();
