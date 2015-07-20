$(document).ready(function(){
    $(".profile-image img").click(function(){
       $(".profile-modify").toggle();
    }); 
    $(".profile-modify form").submit(function(event){
        
        event.preventDefault();
        
        $("#oldPassword").css("borderColor", "");
        $("#newPassword").css("borderColor", "");
        $("#rePassword").css("borderColor", "");
                
        var oldPasswd = $("#oldPassword").val();
        var newPasswd = $("#newPassword").val();
        var rePasswd = $("#rePassword").val();
        $(".errors").hide();
        $(".errors").html("<span></span>");
        if(oldPasswd != ""){
            if(newPasswd != ""){
                if(rePasswd != ""){
                    if(newPasswd != rePasswd){
                        $(".errors").show();
                        $(".errors span").append("Password non coincidenti");
                        $("#newPassword").css("borderColor", "red");
                        $("#rePassword").css("borderColor", "red");
                    }
                    else{
                        if(newPasswd.length < 6){
                            $(".errors").show();
                            $(".errors span").append("La password deve essere lunga almeno 6 caratteri");
                            $("#newPassword").css("borderColor", "red");
                            $("#rePassword").css("borderColor", "red");
                        }else{
                            var xhr = myGetXmlHttpRequest();
                            var url = "./user_profile.php";
                            var method = "POST";
                            var param = new Array(
                                    'newPasswd', newPasswd,
                                    'oldPasswd', oldPasswd
                                    );
                            pageRequest(xhr,url,method,param);
                        }
                    }
                }
                else{
                    $(".errors").show();
                    $(".errors span").append("Inserire di nuovo la password");
                    $("#rePassword").css("borderColor", "red");
                }
            }
            else{
                $(".errors").show();
                $(".errors span").append("Inserire la nuova password");
                $("#newPassword").css("borderColor", "red");
            }
        }
        else{
            $(".errors").show();
            $(".errors span").append("Inserire la vecchia password");
            $("#oldPassword").css("borderColor", "red");
        }
        return false;
    });
});