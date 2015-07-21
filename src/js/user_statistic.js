$(document).ready(initstat);
var xhr;
var barChart;
var barChartHeight;
var MAX_NUM_COLS = 8;
var loadedData;
var firstVisible;
var stopWords;

function initstat()
{
    loadStopWords();
    readFeedGraphic();
    $("#from").change(readFeedGraphic);
    $("#wordCloud").click(function () {
        flipChart();
        document.getElementById("wordCloud").innerHTML = "<canvas id=\"canvas_cloud\"></canvas>";
    });
    $(".card").flip({
        trigger: 'manual'
    });
    hideArrows();
    $("#left_arrow").click(slideVisibleCols);
    $("#right_arrow").click(slideVisibleCols);
}

function hideArrows()
{
    $("#left_arrow").css("visibility", "hidden");
    $("#right_arrow").css("visibility", "hidden");
}

function slideVisibleCols(event)
{
    $(event.target).attr("id") === "left_arrow" ? firstVisible-- : firstVisible++;
    barChart.load({columns: [
            getVisibleData()
        ],
        categories: getVisibleCategories()
    });
    handleArrows();
}

function getVisibleData()
{
    return $.merge(['articoli'], loadedData.numReadFeed.slice(firstVisible, firstVisible + MAX_NUM_COLS));
}

function getVisibleCategories()
{
    return loadedData.categories.slice(firstVisible, firstVisible + MAX_NUM_COLS);
}

function flipChart()
{
    $(".card").flip('toggle');
}

function readFeedGraphic() {
    DBreadFeedRequest();
}

function drawBarChart(data)
{
    loadedData = data;
    var visibleData = $.merge(['articoli'], data.numReadFeed.slice(0, MAX_NUM_COLS));
    firstVisible = 0;
    barChart = c3.generate({
        bindto: document.getElementById("graph"),
        data: {
            columns: [
                visibleData
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
            },
            y: {
                tick: {
                    format: d3.format("d")
                }
            }
        },
        legend: {
            show: false
        }
    });
    handleArrows();
    barChartHeight = $("#graph").height();
}

function handleArrows()
{
    firstVisible > 0 ? $("#left_arrow").css("visibility", "visible") : $("#left_arrow").css("visibility", "hidden");
    (firstVisible + MAX_NUM_COLS) < loadedData.numReadFeed.length ? $("#right_arrow").css("visibility", "visible") : $("#right_arrow").css("visibility", "hidden");
}

function getFreqList(text)
{
    var options = {
        workerUrl: './wordfreq.worker.js',
        stopWords: stopWords,
        minimumCount: 1
    };
    var wordfreq = WordFreq(options).process(text.text, drawWordCloud);
}

function getWeightFactor(list)
{
    var max = list[0][1];
    for (var i = 1; i < list.length; i++)
    {
        if (list[i][1] > max)
            max = list[i][1];
    }
    var height = $(document.getElementById("graph")).height();
    return height / max;
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
        backgroundColor: 'fff4cf',
        weightFactor: weightFactor / 2
    };
    $("#wordCloud").height(barChartHeight);
    WordCloud(document.getElementById("wordCloud"), options);
    flipChart();
}


function loadStopWords()
{
    var JSONObject = new Object;
    JSONObject.type = "loadStopWords";
    var JSONstring = JSON.stringify(JSONObject);
    $.getJSON("parser.php?json=" + JSONstring, null, function (data) {
        stopWords = data.stopWords;
    });
}

function DBreadFeedRequest()
{
    var JSONObject = new Object;
    JSONObject.type = "readFeed";
    JSONObject.from = $("#from option:selected").text();
    var JSONstring = JSON.stringify(JSONObject);
    $.getJSON("parser.php?json=" + JSONstring, null, drawBarChart);
}

function createWordCloud(data, element)
{
    var JSONObject = new Object;
    JSONObject.type = "sendWords";
    JSONObject.classIndex = data.x;
    var JSONstring = JSON.stringify(JSONObject);
    $.getJSON("parser.php?json=" + JSONstring, null, getFreqList);
}