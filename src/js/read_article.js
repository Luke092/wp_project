var ENVELOPE_OPEN_SRC = "./img/utils/open_envelope.png";
var ENVELOPE_CLOSED_SRC = "./img/utils/closed_envelope.png";
var MSG_READ = "Contrassegna come letto";
var MSG_NO_READ = "Contrassegna come non letto";

$(document).ready(function(){
    $(".article-title-fs").click(function(){
        var id = $(this).parent().attr("href");
       $(".envelope-img[id=\""+id+"\"]").attr("src", ENVELOPE_OPEN_SRC);
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
    });
});
