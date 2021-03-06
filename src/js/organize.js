function init()
{
    addEditRemove($("body"));
    initDraggable();
    initDroppable();
}

var xhr;

$(document).ready(init);

function runAjax(JSONstring, handler)
{
    xhr = myGetXmlHttpRequest();
    var callback;
    if (handler !== null) {
        callback = handler;
    } else {
        callback = function () {
        };
    }
    sendData(xhr, "parser.php", "GET", ["json", JSONstring], callback);
}

function addEditRemove(parentNode)
{
    $(parentNode).find("img[class='editCat']").click(editCategory);
    $(parentNode).find("img[class='removeCat']").click(removeCategory);
    $(parentNode).find("img[class='removeFeed']").click(removeFeed);
}

function initDraggable()
{
    $("div[class='feedName']").draggable({
        scroll: true,
        scrollSpeed: 20,
        containment: 'document',
        helper: 'clone'
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
    var movedFeedId = $.trim($(ui.draggable).parent().find("div[class='feedId']").text());
    if (!feedAlreadyPresent(movedFeedId, destinationFeedIds))
    {
        var oldCatName = $.trim($(ui.draggable).parents("div[class='itemContentsHolder']").find("div[class='categoryName']").text());
        var movedDiv = $(ui.draggable).parent().detach();
        var newCatName = $.trim($(event.target).parent().find("div[class='categoryName']").text());
        var movedFeedId = $.trim($(movedDiv).children("div[class='feedId']").text());
        movedFeedId = movedFeedId.substring(1, movedFeedId.length - 1);
        DBmoveFeed(oldCatName, newCatName, movedFeedId);
        feedMover(oldCatName, newCatName, movedDiv);
    }
    sidebar_reload();
}

function feedAlreadyPresent(movedFeedId, destinationFeedIds)
{
    for (var i = 0; i < destinationFeedIds.length; i++) {
        if (movedFeedId === $.trim($(destinationFeedIds[i]).text())) {
            return true;
        }
    }
    return false;
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
    var newCatName = prompt("Inserisci il nome della nuova categoria (max. 30 caratteri)");
    if (newCatName !== null) {
        newCatName = $.trim(newCatName);
        if (newCatName !== "" && !catNameAlreadyPresent(newCatName) && newCatName.length <= 30)
        {
            var sourceCat = $.trim($(ui.draggable).parents("div[class='itemContentsHolder']").find("div[class='categoryName']").text());
            var feedId = $.trim($(ui.draggable).parent().children("div[class='feedId']").text());
            var movedFeed = $(ui.draggable).parent();
            var itemContentsHolder = buildItemContentsHolder(newCatName);
            $(event.target).parents("div[class='itemContentsHolder']").before(itemContentsHolder);
            DBaddCategory(newCatName);
            DBmoveFeed(sourceCat, newCatName, feedId.substring(1, feedId.length - 1));
            feedMover(sourceCat, newCatName, movedFeed);
        }
        else if(newCatName.length > 30){
            alert("Nome categoria troppo lungo");
        }
        else
        {
            alert("Nome categoria non valido");
        }
    }
    else
    {
        alert("La categoria non e' stata aggiunta");
    }

    sidebar_reload();
}

function buildItemContentsHolder(newCatName)
{
    newCatName = newCatName.trim();
    var itemContentsHolder = $("<div id=\"category_" + newCatName + "_contents\" class=\"itemContentsHolder\"></div>");
    var categoryHeader = $("<h2 class=\"categoryHeader\"></h2>");
    categoryHeader.append($("<div class=\"categoryName\">" + newCatName + "</div>"));
    categoryHeader.append($("<div class=\"modCanc\"><img class=\"editCat\" src=\"./img/utils/icon-edit.png\"></img><img class=\"removeCat\" src=\"./img/utils/icon-bury.png\"></img></div>"));
    itemContentsHolder.append(categoryHeader);
    var subscriptionContentsHolder = $("<div class=\"subscriptionContentsHolder\"></div>").droppable({
        hoverClass: 'hovered',
        drop: feedDrop
    });
    itemContentsHolder.append(subscriptionContentsHolder);
    addEditRemove(itemContentsHolder);
    return itemContentsHolder;
}

function editCategory(event)
{
    var newName = $.trim(prompt("Inserisci il nuovo nome da dare alla categoria (max. 30 caratteri)"));
    if (newName !== null && newName !== "") {
        newName = $.trim(newName);
        if(newName.length > 30){
            alert("Nome categoria troppo lungo");
        }
        else{
            var oldName = $.trim($(event.target).parent().prev().text());
            if (catNameAlreadyPresent(newName)) {
                alert("La categoria " + newName + " esiste gia'!");
            }
            else {
                DBeditCategory(oldName, newName);
                $(event.target).parent().prev().text(newName);
            }
        }
    }
    sidebar_reload();
}

function removeCategory(event)
{
    var catName = $.trim($(event.target).parent().prev().text());
    var remove = confirm("Vuoi veramente rimuovere la categoria '" + catName + "'?");
    if (remove)
    {
        DBcategoryRemover(catName);
        hideCategory(catName);
    }
    sidebar_reload();
}

function removeFeed(event)
{
    var feedId = $.trim($(event.target).parent().next().text());
    var feedName = $.trim($(event.target).parent().prev().text());
    var catName = $.trim($(event.target).parents("div[class='itemContentsHolder']").find("div[class='categoryName']").text());
    var remove = confirm("Vuoi veramente rimuovere il feed '" + feedName + "'?");
    if (remove)
    {
        DBfeedRemover(feedId, catName);
        hideFeed(feedId, catName);
    }
    sidebar_reload();
}

function hideCategory(catName)
{
    $("#category_" + catName.trim() + "_contents").remove();
}

function catNameAlreadyPresent(catName)
{
    var categories = $("div[class='categoryName']:visible");
    for (var i = 0; i < categories.length; i++)
    {
        if ($.trim($(categories[i]).text()) === $.trim(catName))
        {
            return true;
        }
    }
    return false;
}

function hideFeed(feedId, catName)
{
    var idDiv = $("#category_" + catName + "_contents div[class='feedId']:contains(" + feedId + ")");
    if (idDiv.parents("div[class^='subscriptionContentsHolder']").children().length === 1) {
        hideCategory($.trim(idDiv.parents("div[class='itemContentsHolder']").find("div[class='categoryName']").text()));
    }
    else
    {
        idDiv.parent().remove();
    }
}

function DBsuccess()
{
    var JSONtext = xhr.responseText;
    var JSONobject = JSON.parse(JSONtext);
    if (JSONobject.success === false)
    {
        alert("Attenzione: l'operazione e' fallita per problemi con il database");
        xhr = myGetXmlHttpRequest();
        pageRequest(xhr, "organize.php", "GET", null);
    }
    return true;
}

function DBaddCategory(catName)
{
    var JSONObject = new Object;
    JSONObject.type = "addCategory";
    JSONObject.catName = catName;
    var JSONstring = JSON.stringify(JSONObject);
    runAjax(JSONstring, DBsuccess);
}

function DBcategoryRemover(catName)
{
    var JSONObject = new Object;
    JSONObject.type = "removeCategory";
    JSONObject.catName = catName;
    var JSONstring = JSON.stringify(JSONObject);

    runAjax(JSONstring, DBsuccess);
}

function DBeditCategory(oldName, newName)
{
    var JSONObject = new Object;
    JSONObject.type = "editCategory";
    JSONObject.oldName = oldName;
    JSONObject.newName = newName;
    var JSONstring = JSON.stringify(JSONObject);

    runAjax(JSONstring, DBsuccess);
}

function DBmoveFeed(oldCatName, newCatName, feedId)
{
    var JSONObject = new Object;
    JSONObject.type = "moveFeed";
    JSONObject.feedId = feedId;
    JSONObject.oldCatName = oldCatName;
    JSONObject.newCatName = newCatName;
    var JSONstring = JSON.stringify(JSONObject);
    runAjax(JSONstring, DBsuccess);
}

function DBfeedRemover(feedId, catName)
{
    var JSONObject = new Object;
    JSONObject.type = "removeFeed";
    JSONObject.feedId = feedId.substring(1, feedId.length - 1);
    JSONObject.catName = catName;
    var JSONstring = JSON.stringify(JSONObject);
    runAjax(JSONstring, DBsuccess);
}
