$(document).ready(function(){
    $(function() {
        $( "#search-input" ).autocomplete({
          minLength: 0,
          source: "./search.php",
          focus: function( event, ui ) {
            $( "#search-input" ).val( ui.item.f_name );
            return false;
          },
          select: function( event, ui ) {
            $("#search-input").val(ui.item.f_name);
            $("#search-input-id").val(ui.item.id);
            var feedId = $("#search-input-id").val();
            addFeed(feedId);
            return false;
          },
        })
        .autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $("<li>").append("<a><img src=\"" + item.image_url + "\" style=\"width:32px;height:32px;\">" 
                            + item.f_name + "<br><span style=\"font-size: xx-small;\">" + item.url 
                            + "</span></a>").appendTo(ul);
        };
    });
});
    