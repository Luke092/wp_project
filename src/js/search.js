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
            return $("<li>").append("<a><img src=\"" + item.image_url + "\" style=\"width:20px;height:20px;\">&nbsp;" 
                            + item.f_name + "<br><span style=\"font-size: xx-small;\">" + item.url 
                            + "</span></a>").appendTo(ul);
        };
    });
    $("#search-bar form").submit(function (event){
        var feedId = $("#search-input-id").val();
        var feedUrl = $("#search-input").val();
        var regex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]){2,6}([/\w\.-]*)*\/?$/;
        if(feedUrl.match(regex) != null){
            addNewFeed(feedId, feedUrl);
        }
        else{
            alert("Url Feed non valido!");
        }
        event.preventDefault();
        return false;
    });
});
    