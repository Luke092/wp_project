function initstat()
{
    readFeedGraphic();
}

$(document).ready(initstat);
var xhr;
var barChart;

function readFeedGraphic() {
    DBreadFeedRequest();
}

function DBreadFeedRequest()
{
//    xhr = myGetXmlHttpRequest();
    var JSONObject = new Object;
    JSONObject.type = "readFeed";
    var JSONstring = JSON.stringify(JSONObject);
////    sendData(xhr, "parser.php", "GET", ["json", JSONstring], drawBarChart);
//    runAjax(JSONstring, drawBarChart);
    $.getJSON("parser.php?json=" + JSONstring, null, drawBarChart);
}

function drawBarChart(data)
{
//    data.categories = ['pippo', 'pluto', 'paperino', 'minnie', 'pippo', 'pluto', 'paperino', 'minnie', 'pippo', 'pluto', 'paperino', 'minnie'];
//    data.numReadFeed = [10, 11, 12, 14, 15, 16, 16, 10, 1, 15, 100, 20];
    var loadedData = $.merge(['data1'], data.numReadFeed);
    var barChart = c3.generate({
        bindto: '#barchart',
        data: {
            columns: [
                loadedData
            ],
            types: {
                data1: 'bar'
            },
            onclick: createWordCloud
        },
        axis: {
            x: {
                type: 'category',
                categories: data.categories
            }
        },
        legend: {
            show: false
        }

    });
}

function createWordCloud(data, element)
{//accesso a category name tramite data.x all'array ottenuto da categories->get_array()
    var JSONObject = new Object;
    JSONObject.type = "sendWords";
    JSONObject.classIndex = data.x;
    var JSONstring = JSON.stringify(JSONObject);
    $.getJSON("parser.php?json=" + JSONstring, null, getFreqList);
}

function getFreqList(text)
{
    var options = {workerUrl: './wordfreq.worker.js'};
    var wordfreq = WordFreq(options).process(text, drawWordCloud);
}

function drawWordCloud(list)
{
    var options = {
        list: list,
        fontFamily: 'Arial',
        shape: 'circle',
        rotateRatio: 1,
        shuffle: true,
        weightFactor: 2.5
    }
    WordCloud(document.getElementById('wordCloud'), options);
}
