$(".minicover, .label").click(function(){
    var catName = $(this).parent().attr("id");
    var xhr = myGetXmlHttpRequest();
    var url = "./add_content.php";
    var method = "POST";
    var param = ["catName", catName];
    pageRequest(xhr, url, method, param);
    waitResponse();
//    history.pushState();
});

$(".add-feed-box > img.add-feed").click(function(){
    var feedId = $(this).parent().parent().attr("id");
    addFeed(feedId);
});

$("#back-to-cat").click(function(){
    var xhr = myGetXmlHttpRequest();
    var url = "./add_content.php";
    var method = "POST";
    var param = ["back-to-cat", true];
    pageRequest(xhr, url, method, param);
    waitResponse();
//    history.pushState();
});

$(":radio:not(:last)").click(function(){
    $(":radio:last").next().attr("readonly", "readonly");
    $(":radio:last").next().css("opacity", "0.3");
});

$(":radio:last").click(function(){
    $(this).next().removeAttr("readonly");
    $(":radio:last").next().css("opacity", "1");
});


$("button").click(function(){
    var catName = "";
    var checkedObj = $(":radio:checked");
    if(checkedObj[0].nextElementSibling.nodeName.toLowerCase() == "input")
        catName = checkedObj.next().val();
    else
        catName = checkedObj.attr("value");
    if(catName == "")
        alert("Devi assegnare un nome alla nuova categoria!");
    else{
        var feedId = $(this).attr("value");
        var xhr = myGetXmlHttpRequest();
        var url = "./add_content.php";
        var method = "POST";
        var param = ["feedId", feedId, "catName", catName];
        pageRequest(xhr, url, method, param);
        waitResponse();
//    location.href = "./home.php";
    }
});

function addFeed(feedId){
//    alert(feedName);
    var xhr = myGetXmlHttpRequest();
    var url = "./add_content.php";
    var method = "POST";
    var param = ["feedId", feedId];
    pageRequest(xhr, url, method, param);
    waitResponse();
//    history.pushState();
}

function addNewFeed(feedId, feedUrl){
//    alert(feedName);
    var xhr = myGetXmlHttpRequest();
    var url = "./add_content.php";
    var method = "POST";
    var param = ["feedId", feedId, "feedUrl", feedUrl];
    pageRequest(xhr, url, method, param);
    waitResponse();
//    history.pushState();
}