var ENVELOPE_OPEN_SRC = "./img/utils/open_envelope.png";
var ENVELOPE_CLOSED_SRC = "./img/utils/closed_envelope.png";
var MSG_READ = "Contrassegna come letto";
var MSG_NO_READ = "Contrassegna come non letto";

$(document).ready(function(){
    $(".article-title-fs").click(function(){
        var id = $(this).parent().attr("href");
       $(".envelope-img[id=\""+id+"\"]").attr("src", ENVELOPE_OPEN_SRC);
        sendArticleObject($(this), true);
    });
    $(".envelope-img[src=\""+ENVELOPE_CLOSED_SRC+"\"]").attr("title", MSG_READ);
    $(".envelope-img[src=\""+ENVELOPE_OPEN_SRC+"\"]").attr("title", MSG_NO_READ);
    $(".envelope-img").click(function(){
        if($(this).attr("src") == ENVELOPE_OPEN_SRC){
            $(this).attr("src", ENVELOPE_CLOSED_SRC);
            $(this).attr("title", MSG_READ);
        }
        else{
            $(this).attr("src", ENVELOPE_OPEN_SRC);
            $(this).attr("title", MSG_NO_READ);
        }
        sendArticleObject($(this), false);
    });
    $(".article-box-fs").mouseover(function(){
        $(this).css("backgroundColor", "rgb(255, 238, 181)");
    });
    $(".article-box-fs").mouseout(function(){
        $(this).css("backgroundColor", "");
    });
    
    $(".link-evidence").mouseover(function(){
        $(this).css("fontWeight", "bold");
        $(this).css("color", "red");
    });
    $(".link-evidence").mouseout(function(){
        $(this).css("fontWeight", "");
        $(this).css("color", "");
    });
    
    $(".cat-name-fs").click(function(){
        var xhr = myGetXmlHttpRequest();
        var url = "./user_home.php";
        var method = "POST";
        var param = ["catName", $(this).text().trim()];
        pageRequest(xhr, url, method, param);
        waitResponse(); 
    });
    
    function sendArticleObject(elem, readOnly){
        var xhr = myGetXmlHttpRequest();
        var url = "./read_article.php";
        var method = "POST";
        var JSONObject = new Object;
        var hidden = elem.parents(".article-box-fs").children(".hidden");
//        JSONObject.id = hidden.attr("id");
//        JSONObject.cat_id = hidden.attr("name");
//        JSONObject.text = hidden.text();
        var json = hidden.text();
//        var JSONString = JSON.stringify(JSONObject);
        var param = ["article", json,
                     "readonly", readOnly];
        sendData(xhr, url, method, param, function(){});
//        waitResponse(); 
    }
});
