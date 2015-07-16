$(document).ready(function(){
   $(".paging-header").click(function(){
        var xhr = myGetXmlHttpRequest();
        var url = "./user_home.php";
        var method = "POST";
        var ids = $(this).attr("id").split("#");
        var param = new Array(
                "page", ids[0],
                "feedId", ids[1],
                "catName", ids[2]);
        pageRequest(xhr, url, method, param);
   });
});