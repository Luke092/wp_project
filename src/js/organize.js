function init()
{
    addEditRemove();
}

$(document).ready(init);

function addEditRemove()
{
    $("img[class='editCat']").click(editCategory);
    $("img[class='removeCat']").click(removeCategory);
    $("img[class='editFeed']").click(editFeed);
    $("img[class='removeFeed']").click(removeFeed);
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
        JSONstring = JSON.stringify(JSONObject);

        var xhr = myGetXmlHttpRequest();
        sendData(xhr, "./parser.php", "GET", ["json", JSONstring], function(){});
        $(event.target).parent().prev().text(newName);
    }
}

function removeCategory()
{

}

function editFeed()
{

}

function removeFeed()
{

}
