$(document).ready(function(){
   $(".navigation_arrow").click(function(){
      var cat_div = $(this).parent();
      var img = $(this).children("img");
      $(cat_div.children(".feed")).toggle("medium", function(){
          if($(this).css("display") == "none"){
              img.attr("src", "./img/utils/right_arrow.png");
          }
          else{
              img.attr("src", "./img/utils/down_arrow.png");
          }
      });
   });
   $(".feed").click(function(){
        var feed_id = $(this).attr("id");
        var cat_name = $(this).parent().children(".cName").attr("id");
        var param = ["feedId" , feed_id, "catName", cat_name];
        var xhr = myGetXmlHttpRequest();
        var url = "./user_home.php";
        pageRequest(xhr, url, "POST", param);
   });
   $(".cName").click(function(){
        var c_name = $(this).attr("id");
        var param = ["catName" , c_name];
        var xhr = myGetXmlHttpRequest();
        var url = "./user_home.php";
        pageRequest(xhr, url, "POST", param);
   });
   
});