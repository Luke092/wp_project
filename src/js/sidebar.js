$(document).ready(function(){
   $(".navigation_arrow").click(function(){
      var cat_div = $(this).parent();
      var img = $(this).children("img");
      $(cat_div.children(".feed")).toggle("medium", "swing", function(){
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
        $("#page").scrollTop(0);
   });
   $(".cName").click(function(){
        var c_name = $(this).attr("id");
        var param = ["catName" , c_name];
        var xhr = myGetXmlHttpRequest();
        var url = "./user_home.php";
        pageRequest(xhr, url, "POST", param);
        $("#page").scrollTop(0);
   });
   
   $(".evidence").mouseover(function(){
      $(this).css("backgroundColor", "rgba(249, 197, 109, 0.66)");
      $(this).css("color", "red");
   });
   $(".evidence").mouseout(function(){
      $(this).css("backgroundColor", "");
      $(this).css("fontWeight", "");
      $(this).css("color", "");
   });
   $("#tv-commands").mouseover(function(){
        $(this).css("fontWeight", "bold");
        $(this).css("color", "darkred");
   });
   
   $("#tv-commands").click(function(){
      var icon = $("#tv-commands img").attr("src");
      if(icon == "./img/utils/expand.png"){
          $("#tv-commands img").attr("src", "./img/utils/collapse.png");
          $("#tv-commands span").text("Chiudi tutto");
          $(".feed").fadeIn(400, "swing", function(){
              $(".navigation").attr("src", "./img/utils/down_arrow.png");
          });
      }
      else{
          $("#tv-commands img").attr("src", "./img/utils/expand.png");
          $("#tv-commands span").text("Espandi tutto");
          $(".feed").fadeOut(400, "swing", function(){
              $(".navigation").attr("src", "./img/utils/right_arrow.png");
          });
      }
   });
});