var readyState = {
	UNINITIALIZED:	0,
	OPEN:           1,
	SENT:           2,
	RECEIVING:	3,
	LOADED:         4
    };

var statusText = new Array();
statusText[100] = "Continue";
statusText[101] = "Switching Protocols";
statusText[200] = "OK";
statusText[201] = "Created";
statusText[202] = "Accepted";
statusText[203] = "Non-Authoritative Information";
statusText[204] = "No Content";
statusText[205] = "Reset Content";
statusText[206] = "Partial Content";
statusText[300] = "Multiple Choices";
statusText[301] = "Moved Permanently";
statusText[302] = "Found";
statusText[303] = "See Other";
statusText[304] = "Not Modified";
statusText[305] = "Use Proxy";
statusText[306] = "(unused, but reserved)";
statusText[307] = "Temporary Redirect";
statusText[400] = "Bad Request";
statusText[401] = "Unauthorized";
statusText[402] = "Payment Required";
statusText[403] = "Forbidden";
statusText[404] = "Not Found";
statusText[405] = "Method Not Allowed";
statusText[406] = "Not Acceptable";
statusText[407] = "Proxy Authentication Required";
statusText[408] = "Request Timeout";
statusText[409] = "Conflict";
statusText[410] = "Gone";
statusText[411] = "Length Required";
statusText[412] = "Precondition Failed";
statusText[413] = "Request Entity Too Large";
statusText[414] = "Request-URI Too Long";
statusText[415] = "Unsupported Media Type";
statusText[416] = "Requested Range Not Satisfiable";
statusText[417] = "Expectation Failed";
statusText[500] = "Internal Server Error";
statusText[501] = "Not Implemented";
statusText[502] = "Bad Gateway";
statusText[503] = "Service Unavailable";
statusText[504] = "Gateway Timeout";
statusText[505] = "HTTP Version Not Supported";
statusText[509] = "Bandwidth Limit Exceeded";

function myGetXmlHttpRequest(){
    var xhr = false;
    var activeXopt = new Array(
            "Microsoft.XmlHttp",
            "MSXML4.XmlHttp",
            "MSXML3.XmlHttp",
            "MSXML2.XmlHttp",
            "MSXML.XmlHttp");
    try{
        xhr = new XMLHttpRequest();
    }
    catch(e){
        var created = false;
        for(var i = 0; i < activeXopt.length && !created; i++){
            try{
                xhr = new ActiveXObject(activeXopt[i]);
                created = true;
            }
            catch(eActXobj){
                alert("Il tuo browser non supporta AJAX!");
            }
        }
    }
    return xhr;
}

// Start load a page using AJAX
function sendData(xhr, url, method, param, callback){
    // set default value if not defined
    if (typeof(param)==='undefined')
        param = null;
    switch (method.toUpperCase()){
        case "GET":
            var uri = url;
            if(param != null){
                uri += "?";
                var i = 0;
                for (i = 0; i < param.length - 2; i += 2){
                    uri += param[i] + "=" + param[i+1] + "&";
                }
                uri += param[i] + "=" + param[i+1];
            }
            xhr.open("GET", uri, true);
            xhr.onreadystatechange = function(){
                if(xhr.readyState === readyState.LOADED){
                    if(statusText[xhr.status] === "OK")
                        callback();
                    else
                        alert("Spiacente, si e' verificato il seguente errore: " + xhr);//statusText[xhr.status]);
                }
            };
            xhr.send(null);
            break;
        case "POST":
            var content = "";
            if(param != null){
                var i = 0;
                for (i = 0; i < param.length - 2; i += 2){
                    content += param[i] + "=" + param[i+1] + "&";
                }
                content += param[i] + "=" + param[i+1];
            }
//            console.log(content);
            xhr.open("POST", url, true);
            xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function(){
                if(xhr.readyState === readyState.LOADED){
                    if(statusText[xhr.status] === "OK")
                        callback();
                    else
                        alert("Spiacente, si e' verificato il seguente errore: " + statusText[xhr.status]);
                }
            };
            xhr.send((content == "")? null : content);
            break;
        default:
            return null;
    }
}

function pageRequest(xhr, url, method, param){
    var callback = function (){
        // import target page css
        $(document.head).append($(xhr.responseText).filter("link[rel=\"stylesheet\"]"));
        // load remote page in the correct place
//        document.getElementById("page").innerHTML = $(xhr.responseText).filter("div").html();
        $("#page").html(xhr.responseText);
    };
    sendData(xhr, url, method, param, callback);
}