function init()
{
    addEditRemove();
}

$(document).ready(init);

var xhr;

function runAjax(JSONstring)
{
    var url = "parser.php?json=" + JSONstring;
    xhr = myGetXmlHttpRequest();
    xhr.open("GET", url, true);
    xhr.setRequestHeader("connection", "close");
    xhr.send(null);
}

//function myGetXmlHttpRequest()
//{
//    var XmlHttpReq = false;
//    var activeXopt = new Array("Microsoft.XmlHttp", "MSXML4.XmlHttp", "MSXML3.XmlHttp", "MSXML2.XmlHttp", "MSXML.XmlHttp");
//    // prima come oggetto nativo
//    try
//    {
//        XmlHttpReq = new XMLHttpRequest();
//    }
//    catch (e)
//    {
//        // poi come oggetto ActiveX dal più al meno recente
//        var created = false;
//        for (var i = 0; i < activeXopt.length && !created; i++)
//        {
//            try
//            {
//                XmlHttpReq = new ActiveXObject(activeXopt[i]);
//                created = true;
//            }
//            catch (eActXobj)
//            {
//                alert("Il tuo browser non supporta AJAX!");
//            }
//        }
//    }
//    return XmlHttpReq;
//}

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
        var JSONstring = JSON.stringify(JSONObject);

//       var xhr = myGetXmlHttpRequest();
//        sendData(xhr, "./parser.php", "GET", ["json", JSONstring], function () {
//        });
        runAjax(JSONstring);

//          location.reload();

//        var duplicated = $("div[id='category_" + newName + "_contents'");
//        if (duplicated == undefined) {
//            $(event.target).parent().prev().text(newName);
//        }
//        else
//        {   //se la categoria è già fra quelle dell'utente bisogna eliminare il vecchio contenitore e spostare i feed in quello dell'altra categoria
//            //verificando di non inserire doppioni
//            
//        }
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
