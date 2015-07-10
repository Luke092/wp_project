$(".minicover, .label").click(function(){
    var cat_name = $(this).parent().attr("id");
    var xhr = myGetXmlHttpRequest();
    var url = "./add_content.php";
    var method = "POST";
    var param = ["catName", cat_name];
    pageRequest(xhr, url, method, param);
});