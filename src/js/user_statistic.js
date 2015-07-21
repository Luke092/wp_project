function initstat()
{
    readFeedGraphic();
    $("#from").change(readFeedGraphic);
    
    $("#wordCloud").click(function(){
        flipChart();
        document.getElementById("wordCloud").innerHTML = "<canvas id=\"canvas_cloud\"></canvas>";
    });
    
    $(".card").flip({
        trigger: 'manual'
    });
}

function flipChart()
{
    $(".card").flip('toggle');
}

$(document).ready(initstat);
var xhr;
var barChart;
var barChartHeight;

function readFeedGraphic() {
    DBreadFeedRequest();
}

function DBreadFeedRequest()
{
    var JSONObject = new Object;
    JSONObject.type = "readFeed";
    JSONObject.from = $("#from option:selected").text();
    var JSONstring = JSON.stringify(JSONObject);
    $.getJSON("parser.php?json=" + JSONstring, null, drawBarChart);
}

function drawBarChart(data)
{
    var loadedData = $.merge(['articoli'], data.numReadFeed);
    var barChart = c3.generate({
        bindto: document.getElementById("graph"),
        data: {
            columns: [
                loadedData
            ],
            types: {
                articoli: 'bar'
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
    barChartHeight = $("#graph").height();
}

function createWordCloud(data, element)
{
    var JSONObject = new Object;
    JSONObject.type = "sendWords";
    JSONObject.classIndex = data.x;
    var JSONstring = JSON.stringify(JSONObject);
    $.getJSON("parser.php?json=" + JSONstring, null, getFreqList);
}

function getFreqList(text)
{
    var options = {
        workerUrl: './wordfreq.worker.js',
        minimumCount: 1
    };
    var wordfreq = WordFreq(options).process(text.text, drawWordCloud);
}

function getWeightFactor(list)
{
    var max = list[0][1];
    for(var i=1; i<list.length; i++)
    {
        if(list[i][1]>max)
            max = list[i][1];
    }
    var height = $(document.getElementById("graph")).height();
    return height/max;
}

function drawWordCloud(list)
{
    var weightFactor = getWeightFactor(list);
    
    var options = {
        list: list,
        fontFamily: 'Arial',
        shape: 'circle',
        rotateRatio: 1,
        shuffle: true,
        backgroundColor: 'fff4cf', //rgb(255, 244, 207),
        weightFactor: weightFactor/2
    };
    $("#wordCloud").height(barChartHeight);
    WordCloud(document.getElementById("wordCloud"), options);
    flipChart();
}
