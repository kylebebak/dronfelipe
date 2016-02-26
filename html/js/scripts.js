(function() {
  var baseSite = "http://kylebebak.github.io";
  var numPosts = 5;
  var fade = 700;

  $.get( baseSite + '/posts/', function( data ) {
    var container = $("ul#posts");
    var posts = $("ul.post-list li", $(data)).slice(0, numPosts);

    $.each(posts, function(i, val) {
      $(val).addClass('post-item');
      var link = $("a", val).attr('href');
      $("a", val).attr('href', baseSite + link);
      $("span", val).remove();
      $(val).hide().appendTo(container).fadeIn(fade);
    });

    $("<li><a href='http://kylebebak.github.io/posts/'>...more</a></li>").hide().appendTo(container).fadeIn(fade);
  });

})();
