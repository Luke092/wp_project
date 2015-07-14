function init()
{
    addEditRemove();
    initDraggable();
    initDroppable();
}

$(document).ready(init);

var xhr;
var collision; // 1 collision, 0 no collision

function runAjax(JSONstring, handler)
{
    var url = "parser.php?json=" + JSONstring;
    xhr = myGetXmlHttpRequest();
    xhr.onreadystatechange = (typeof handler !== 'undefined') ? handler : null;
    xhr.open("GET", url, true);
    xhr.setRequestHeader("connection", "close");
    xhr.send(null);
}

function addEditRemove()
{
    $("img[class='editCat']").click(editCategory);
    $("img[class='removeCat']").click(removeCategory);
    $("img[class='editFeed']").click(editFeed);
    $("img[class='removeFeed']").click(removeFeed);
}

function initDraggable()
{
    $("div[class='feedName']").draggable({
        containment: 'document',
        helper: 'clone',
    });
}

function initDroppable()
{
    $("div[class='subscriptionContentsHolder']").droppable({
        hoverClass: 'hovered',
        drop: feedDrop
    });

    $("div[class='newCategory']").droppable({
        hoverClass: 'hovered',
        drop: newCatDrop
    });
}

function feedDrop(event, ui)
{
    var destinationFeedIds = $(event.target).find("div[class='feedId']");
    var movedFeedId = $(ui.draggable).parent().find("div[class='feedId']").text();
    for (var i = 0; i < destinationFeedIds.length; i++)
    {
        if (movedFeedId == $(destinationFeedIds[i]).text())
        {
            return;
        }
    }
    var oldCatName = $(ui.draggable).parents("div[class='itemContentsHolder']").find("div[class='categoryName']").text();
    var movedDiv = $(ui.draggable).parent().detach();
    var newCatName = $(event.target).parent().find("div[class='categoryName']").text();
    var movedFeedId = $(movedDiv).children("div[class='feedId']").text();
    movedFeedId = movedFeedId.substring(1, movedFeedId.length - 1);
    DBmoveFeed(oldCatName, newCatName, movedFeedId);
    feedMover(oldCatName, newCatName, movedDiv);
}

function feedMover(oldCatName, newCatName, movedDiv)
{
    var destination = $("#category_" + newCatName + "_contents div[class^='subscriptionContentsHolder']");
    $(destination).append(movedDiv);

    if ($("#category_" + oldCatName + "_contents div[class^='subscriptionContentsHolder']").children(":visible").length === 0)
    {
        hideCategory(oldCatName);
    }
}

function newCatDrop(event, ui)
{
    var sourceCat = $(ui.draggable).parents("div[class='itemContentsHolder']").find("div[class='categoryName']").text();
    var newCatName = $.trim(prompt("Inserisci il nome della nuova categoria"));
    var feedName = $(ui.draggable).text();
    var feedId = $(ui.draggable).parent().children("div[class='feedId']").text();
    var movedFeed = $(ui.draggable).parent();
    if (newCatName !== "" && !catNameAlreadyPresent(newCatName))
    {

        var itemContentsHolder = $("<div id=\"category_" + newCatName + "_contents\" class=\"itemContentsHolder\"></div>");
        var categoryHeader = $("<h2 class=\"categoryHeader\"></h2>");
        categoryHeader.append($("<div class=\"categoryName\">" + newCatName + "</div>"));
        categoryHeader.append($("<div class=\"modCanc\"><img class=\"editCat\" src=\"./img/utils/icon-edit.png\"></img><img class=\"removeCat\" src=\"./img/utils/icon-bury.png\"></img></div>"));
        itemContentsHolder.append(categoryHeader);
        var subscriptionContentsHolder = $("<div class=\"subscriptionContentsHolder\"></div>");
        itemContentsHolder.append(subscriptionContentsHolder);
        $(event.target).parents("div[class='itemContentsHolder']").before(itemContentsHolder);

        DBaddCategory(newCatName);

        feedMover(sourceCat, newCatName, movedFeed);

        var feedId1 = feedId.substring(1, feedId.length - 1);
        console.log(sourceCat);
        DBmoveFeed(sourceCat, newCatName, feedId1);
    }
    else
    {
        alert("Nome categoria non valido");
    }
}



function editCategory(event)
{
    //prova
    var newName = prompt("Inserisci il nuovo nome da dare alla categoria");
    //
    if (newName != null) {
        var JSONObject = new Object;
        JSONObject.type = "editCategory";
        JSONObject.oldName = $(event.target).parent().prev().text();
        JSONObject.newName = newName;
        var JSONstring = JSON.stringify(JSONObject);

        runAjax(JSONstring);

        changeCatName(newName, $(event.target).parent().prev());
    }
}

function removeCategory(event)
{
    var remove = confirm("Vuoi veramente rimuovere questa categoria?");
    if (remove)
    {
        categoryRemover($(event.target).parent().prev().text());
    }

}

function categoryRemover(catName)
{
    var JSONObject = new Object;
    JSONObject.type = "removeCategory";
    JSONObject.catName = catName;
    var JSONstring = JSON.stringify(JSONObject);

    runAjax(JSONstring);

    hideCategory(catName);
}

function editFeed(event)
{
    var feedId = $(event.target).parent().next().text();
    feedId = feedId.substring(1, feedId.length - 1);     
    var newName = prompt("Inserisci il nuovo nome da dare al feed");
    if (newName != null) {
        verifyFeedCollision(feedId, newName);
        if (collision == 0) {
            
            var feedIds = $("div[class='feedId']:contains(" + feedId + ")");
            $(feedIds).parent().children("div[class='feedName']").text(newName);
            
            var JSONObject = new Object;
            JSONObject.type = "editFeed";
            JSONObject.oldName = $(event.target).parent().prev().text();
            JSONObject.newName = newName;
            JSONObject.feedId = feedId;
            var JSONstring = JSON.stringify(JSONObject);

            runAjax(JSONstring, updateFeedIds);
        }
        else
        {
            alert("Attenzione, nome feed gia' assegnato");
        }
    }
}

function updateFeedIds()
{
    if (xhr.readyState === 4)
    {
        var JSONobject = xhr.responseText;
//        var JSONobject = JSON.parse(JSONtext);
        $("div[class='feedId']:contains(" + JSONobject.oldId + ")").text("a" + JSONobject.newId + "a");
    }
}

function verifyFeedCollision(feedId, newName)
{
    var JSONObject = new Object;
    JSONObject.type = "feedCollision";
    JSONObject.newName = newName;
    JSONObject.feedId = feedId;
    var JSONstring = JSON.stringify(JSONObject);

    runAjax(JSONstring, assignCollision);
}

function assignCollision()
{
    if (xhr.readyState === 4)
    {
        var JSONtext = xhr.responseText;
        collision = JSONtext.collision;
//        var JSONtext = xhr.responseText;
//        var JSONobject = JSON.parse(JSONtext);
//        collision = JSONobject.collision;
    }
}

function removeFeed(event)
{
    var feedId = $(event.target).parent().next().text();
    var feedId1 = feedId.substring(1, feedId.length - 1);
    var JSONObject = new Object;
    JSONObject.type = "removeFeed";
    JSONObject.feedId = feedId1;
    var JSONstring = JSON.stringify(JSONObject);
    runAjax(JSONstring);
    hideFeed(feedId);
}

function feedRemover(feedId)
{
    var feedId1 = feedId.substring(1, feedId.length - 1);
    var JSONObject = new Object;
    JSONObject.type = "removeFeed";
    JSONObject.feedId = feedId1;
    var JSONstring = JSON.stringify(JSONObject);
    runAjax(JSONstring);
    hideFeed(feedId);
}



function hideCategory(catName)
{
    $("#category_" + catName + "_contents").hide();
}

function changeCatName(newName, catDiv)
{
//    var categories = $("div[class='categoryName']");
//    for (var i = 0; i < categories.length; i++)
//    {
//        if ($.trim($(categories[i]).text()) === $.trim(newName))
//        {
    if (catNameAlreadyPresent(newName)) {
        alert("La categoria " + $.trim(newName) + " esiste gia'!");
        return;
    }
//        }
//    }
    $(catDiv).text(newName);
}

function catNameAlreadyPresent(catName)
{
    var categories = $("div[class='categoryName']");
    for (var i = 0; i < categories.length; i++)
    {
        if ($.trim($(categories[i]).text()) === $.trim(catName))
        {
            return true;
        }
    }
    return false;
}

function hideFeed(feedId)
{
//    var feedId = $(feed).next().next().text();
    var feedIds = $("div[class='feedId']:contains(" + feedId + ")");
//    console.log(feedIds.length);
    for (var i = 0; i < feedIds.length; i++) {
        if ($(feedIds[i]).parents("div[class^='subscriptionContentsHolder']").children(":visible").length === 1) {
            hideCategory($(feedIds[i]).parents("div[class='itemContentsHolder']").find("div[class='categoryName']").text());
        }
        else {
            $(feedIds[i]).parent().hide();
        }
    }
}

function DBaddCategory(catName)
{
    var JSONObject = new Object;
    JSONObject.type = "addCategory";
    JSONObject.catName = catName;
    var JSONstring = JSON.stringify(JSONObject);
    runAjax(JSONstring);
}

function DBfeedRemover(feedId)
{
    var feedId1 = feedId.substring(1, feedId.length - 1);
    var JSONObject = new Object;
    JSONObject.type = "removeFeed";
    JSONObject.feedId = feedId1;
    var JSONstring = JSON.stringify(JSONObject);
    runAjax(JSONstring);
}

function DBmoveFeed(oldCatName, newCatName, feedId)
{
    var JSONObject = new Object;
    JSONObject.type = "moveFeed";
    JSONObject.feedId = feedId;
    JSONObject.oldCatName = oldCatName;
    JSONObject.newCatName = newCatName;
    var JSONstring = JSON.stringify(JSONObject);
    runAjax(JSONstring);
}
