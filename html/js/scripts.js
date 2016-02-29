(function() {
  var baseSite = "http://kylebebak.github.io";
  var numPosts = 7;
  var fade = 700;

  $.get(baseSite, function(data) {
    var container = $("ul#posts");
    var posts = $("ul.post-list li", $(data)).slice(0, numPosts);

    $.each(posts, function(i, val) {
      $(val).addClass('post-item');
      var link = $("a", val).attr('href');
      $("a", val).attr('href', baseSite + link);
      $("span", val).remove();
      $(val).hide().appendTo(container).fadeIn(fade);
    });

    $("<li><a href='http://kylebebak.github.io'>...more</a></li>").hide().appendTo(container).fadeIn(fade);
  });

  $.get(baseSite + '/code', function(data) {
    var container = $("div#code");
    var code = $(".post-content>ul", $(data));

    code.hide().appendTo(container).fadeIn(fade);
  });

})();
