$(document).ready(function(){
    $(".profile-image img").click(function(){
       $(".profile-modify").toggle();
    }); 
    $(".profile-modify button").click(function(event){
        $("#oldPassword").css("borderColor", "");
        $("#newPassword").css("borderColor", "");
        $("#rePassword").css("borderColor", "");
                
        var oldPasswd = $("#oldPassword").val();
        var newPasswd = $("#newPassword").val();
        var rePasswd = $("#rePassword").val();
        $(".errors").hide();
        $(".errors").html("<ul></ul>");
        if(oldPasswd != ""){
            if(newPasswd != ""){
                if(rePasswd != ""){
                    if(newPasswd != rePasswd){
                        $(".errors").show();
                        $(".errors ul").append("<li>Password non coincidenti</li>");
                        $("#newPassword").css("borderColor", "red");
                        $("#rePassword").css("borderColor", "red");
                    }
                    else{
                        var xhr = myGetXmlHttpRequest();
                        var url = "./user_profile.php";
                        var param = array("oldPasswd", oldPasswd, "newPasswd", newPasswd);
                        pageRequest(xhr, url, "POST", param);
                    }
                }
                else{
                    $(".errors").show();
                    $(".errors ul").append("<li>Inserire di nuovo la password!</li>");
                    $("#rePassword").css("borderColor", "red");
                }
            }
            else{
                $(".errors").show();
                $(".errors ul").append("<li>Inserire la nuova password!</li>");
                $("#newPassword").css("borderColor", "red");
            }
        }
        else{
            $(".errors").show();
            $(".errors ul").append("<li>Inserire la vecchia password!</li>");
            $("#oldPassword").css("borderColor", "red");
        }
        event.preventDefault();
        return false;
    });
});