// Start load a page using AJAX
function page_request(url, method, param){
    // set default value if not defined
    if (typeof(param)==='undefined') param = null;
    
    var xhr = new XMLHttpRequest();
    
    switch (method){
        case 0:
            var uri = url;
            if(param != null){
                uri += "?";
                var i = 0;
                for (i = 0; i < param.length - 2; i += 2){
                    uri += param[i] + "=" + param[i+1] + "&";
                }
                uri += param[i] + "=" + param[i+1];
            }
            xhr.open("get", uri, true);
            xhr.send();
            break;
        case 1:
            var content = "";
            if(param != null){
                var i = 0;
                for (i = 0; i < param.length - 2; i += 2){
                    content += param[i] + "=" + param[i+1] + "&";
                }
                content += param[i] + "=" + param[i+1];
            }
            console.log(content);
            xhr.open("post", url, true);
            xhr.send((content == "")? null : content);
            break;
        default:
            return null;
    }
    xhr.onreadystatechange = function (){
        if(xhr.readyState == 4 && xhr.status == 200){
            // import target page css
            $(document.head).append($(xhr.responseText).filter("link[rel=\"stylesheet\"]"));
            // load remote page in the correct place
            document.getElementById("page").innerHTML = $(xhr.responseText).filter("div").html();
        }
    }
}