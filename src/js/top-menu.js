$(document).ready(function(){
   $("#add_content").click(function(){
       var xhr = myGetXmlHttpRequest();
       var url = "./add_content.php";
       pageRequest(xhr, url, "GET");
        waitResponse();
   });
   $("#home").click(function(){
       var xhr = myGetXmlHttpRequest();
       var url = "./user_home.php";
       pageRequest(xhr, url, "GET");
       waitResponse();
   });
   $("#logout").click(function(){
       window.location.href = "./logout.php";
   });
   $("#profile").click(function(){
       var xhr = myGetXmlHttpRequest();
       var url = "./user_profile.php";
       pageRequest(xhr, url, "GET");
       waitResponse();
   });
   $("#organize").click(function(){
       var xhr = myGetXmlHttpRequest();
       var url = "./organize.php";
       pageRequest(xhr, url, "GET");
       waitResponse();
   });
    $(".menu-entry").click(function(){
        $(this).addClass("selected-menu-entry");
        $(".menu-entry").not(this).removeClass("selected-menu-entry");
    })
});