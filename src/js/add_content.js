$(".minicover, .label").click(function(){
    var catNname = $(this).parent().attr("id");
    var xhr = myGetXmlHttpRequest();
    var url = "./add_content.php";
    var method = "POST";
    var param = ["catName", catNname];
    pageRequest(xhr, url, method, param);
    waitResponse();
});

$("#back-to-cat").click(function(){
    var xhr = myGetXmlHttpRequest();
    var url = "./add_content.php";
    var method = "POST";
    var param = ["back-to-cat", true];
    pageRequest(xhr, url, method, param);
    waitResponse();
});