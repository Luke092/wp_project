$(".minicover, .label").click(function(){
    var catName = $(this).parent().attr("id");
    var xhr = myGetXmlHttpRequest();
    var url = "./add_content.php";
    var method = "POST";
    var param = ["catName", catName];
    pageRequest(xhr, url, method, param);
    waitResponse();
//    history.pushState();
});

$(".add-feed > img").click(function(){
    var feedId = $(this).parent().parent().attr("id");
//    alert(feedName);
    var xhr = myGetXmlHttpRequest();
    var url = "./add_content.php";
    var method = "POST";
    var param = ["feedId", feedId];
    pageRequest(xhr, url, method, param);
    waitResponse();
//    history.pushState();
});

$("#back-to-cat").click(function(){
    var xhr = myGetXmlHttpRequest();
    var url = "./add_content.php";
    var method = "POST";
    var param = ["back-to-cat", true];
    pageRequest(xhr, url, method, param);
    waitResponse();
//    history.pushState();
});