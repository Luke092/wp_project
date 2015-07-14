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
       //TODO: add organize click action
   });
   $(".menu-entry").mouseover(function(){
       $(this).css("backgroundColor", "#bbb");
   });
   $(".menu-entry").mouseout(function(){
       $(this).css("backgroundColor", "#ddd");
   });
});