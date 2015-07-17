var xhr = null;
$(document).ready(function(){
    $("#login-box form").submit(sendLoginData);
});

function sendLoginData(event){
    xhr = myGetXmlHttpRequest();
    var url = "./login.php";
    var method = "POST";
    var email = document.getElementById("login-email").value;
    var password = document.getElementById("login-password").value;
    var param = new Array(
            "email", email.trim(),
            "password", password.trim());
    sendData(xhr, url, method, param, loginValidate);
    event.preventDefault();
    return false;
}

function loginValidate(){
    if(xhr.responseText){
        $("#login-error").show();
        $("#login-error").html(xhr.responseText);
    } else{
        location.href = "./home.php";
    }
}