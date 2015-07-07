var xhr = null;
$(document).ready(function(){
    $("#register-box :button").click(sendRegisterData);
});

function sendRegisterData(){
    xhr = myGetXmlHttpRequest();
    var url = "./register.php";
    var method = "POST";
    var email = document.getElementById("register-email").value;
    var password = document.getElementById("register-password").value;
    var repassword = document.getElementById("re-password").value;
    var param = new Array(
            "email", email.trim(),
            "password", password.trim(),
            "repassword", repassword.trim());
    sendData(xhr, url, method, param, registerValidate);
}

function registerValidate(){
    if(xhr.responseText){
        $("#register-error").show();
        $("#register-error").html(xhr.responseText);
    } else{
        location.href = "./home.php";
    }
}