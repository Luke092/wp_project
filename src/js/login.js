var xhr = null;
$(document).ready(function(){
    $("#login-box :button").click(sendLoginData);
});

function sendLoginData(){
    xhr = myGetXmlHttpRequest();
    var url = "./login.php";
    var method = "POST";
    var email = document.getElementById("login-email").value;
    var password = document.getElementById("login-password").value;
    var param = new Array(
            "email", email.trim(),
            "password", password.trim());
    sendData(xhr, url, method, param, loginValidate);
}

function loginValidate(){
    if(xhr.responseText){
        $("#login-error").html(xhr.responseText);
    } else{
        location.href = "./home.php";
    }
}