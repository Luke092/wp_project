$(document).ready(function(){
   $("#add_content").click(function(){
       var xhr = myGetXmlHttpRequest();
       var url = "./add_content.php";
       pageRequest(xhr, url, "GET");
   });
   $("#home").click(function(){
       var xhr = myGetXmlHttpRequest();
       var url = "./user_home.php";
       pageRequest(xhr, url, "GET");
   });
   $("#logout").click(function(){
       window.location.href = "./logout.php";
   });
   $("#profile").click(function(){
       //TODO: add profile click action
   });
   $("#organize").click(function(){
       var xhr = myGetXmlHttpRequest();
       var url = "./organize.php";
       pageRequest(xhr, url, "GET");
   });
   $(".menu-entry").mouseover(function(){
//       $(this).css("backgroundColor", "#bbb");
        $(this).css("backgroundColor", "#fe8181");
   });
   $(".menu-entry").mouseout(function(){
       $(this).css("backgroundColor", "inherit");
   });
});