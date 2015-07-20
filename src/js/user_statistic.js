function initstat()
{
    readFeedGraphic();

    //
//    getFreqList("a");
    
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
    ///
//    data.categories = ['pippo', 'pluto', 'paperino', 'minnie', 'pippo', 'pluto', 'paperino', 'minnie', 'pippo', 'pluto', 'paperino', 'minnie'];
//    data.numReadFeed = [10, 11, 12, 14, 15, 16, 16, 10, 1, 15, 100, 20];
    ///
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
    //PROVA
//    text = "Il canto \n I dell’Inferno è di introduzione all’intero poema, presenta quindi la situazione iniziale e spiega le ragioni del viaggio allegorico: Dante vi compare nella duplice veste di personaggio reale, che in un determinato momento storico si smarrisce in una selva (a metà della sua vita, quindi nell'anno 1300 quando stava per compiere 35 anni), e in quella di ogni uomo che in questa vita è chiamato a compiere un percorso di redenzione e purificazione morale per liberarsi dal peccato e guadagnare la beatitudine. Sul piano allegorico, dunque, la selva rappresenta proprio il peccato (essa è infatti descritta come selvaggia e aspra e forte, spaventosa al solo ricordo e poco meno amara della morte stessa), mentre su quello letterale è un luogo in cui chi compie un viaggio rischia realisticamente di smarrirsi per essere uscito dalla diritta via, per cui i lettori del tempo di Dante potevano trovare familiare un paesaggio simile (all'epoca le zone boscose erano assai estese e selvatiche, come per esempio in Maremma: cfr. Inf., XIII, 7-9). Altrettanto realistici gli altri elementi del paesaggio simbolico, a cominciare dal colle che allegoricamente raffigura la via alla felicità terrena, cioè al possesso delle virtù cardinali (fortezza, temperanza, prudenza e giustizia) per le quali la ragione umana è sufficiente, e che Dante tenta inutilmente di scalare vedendo sorgere il sole dietro la sua vetta (esso rappresenta la via verso la salvezza, oltre all'ovvia considerazione che il nuovo giorno dissipa le paure della notte e ridona al poeta nuova speranza). Le tre fiere che sbarrano il passo al poeta e lo ricacciano verso la selva sono invece le tre principali disposizioni peccaminose: la lonza è la lussuria, il leone è la superbia, la lupa è l’avarizia-cupidigia, secondo una tradizione già attestata dai commentatori medievali, e anch'esse ovviamente rappresentano tre animali selvaggi che non erano certo impossibili da incontrare in un effettivo viaggio attraverso una foresta (tranne naturalmente il leone, ma nulla conferma che il viaggio dantesco avvenga in Italia e d'altronde vari interpreti hanno ipotizzato che questi luoghi si trovino in realtà nei pressi di Gerusalemme, sotto la quale si spalanca la voragine infernale). Più pericolosa è la lupa-avarizia, radice di tutti i mali e per Dante causa prima del disordine politico e morale che regnava in Italia all’inizio del Trecento, di cui è simbolo del resto anche la selva, mentre va ricordato che in molti passi del poema egli si scaglia con forza contro la corruzione del mondo politico ed ecclesiastico del suo tempo, causata principalmente proprio dall'avidità di denaro. La lupa si rivela un ostacolo insuperabile e Dante lentamente scivola nuovamente verso la selva, cioè il peccato.";
//    text += "La seconda parte del Canto vede come protagonista Virgilio, che sarà la prima guida di Dante nel viaggio ultraterreno e che è allegoria della ragione umana dei filosofi antichi, guida sufficiente a condurre l’uomo al pieno possesso delle virtù cardinali: egli giunge in soccorso del poeta in modo inaspettato, come un'apparizione spettrale, tanto che Dante gli chiede timoroso se sia ombra od omo certo. La risposta del poeta latino è una vera e propria prosopopea, un'elegante auto-presentazione in cui Virgilio non fa direttamente il proprio nome (sarà Dante a citarlo al termine delle sue parole) e si manifesta come l'autore dell'Eneide, il poema che era considerato il capolavoro della letteratura latina e il cui protagonista, Enea, è centrale nella tradizione classico-cristiana, in quanto fondatore della stirpe romana e, indirettamente, di quella Roma che sarà centro dell'Impero e della Chiesa. Virgilio rimprovera Dante del fatto che non sale il dilettoso monte  che è principio di ogni felicità e il poeta fiorentino risponde indicando Virgilio come il suo maestro, colui da cui ha tratto l'alto stile tragico che gli ha dato la fama, invocando poi il suo aiuto contro la lupa-avarizia che lo riempie di terrore e costituisce uno sbarramento insuperabile: la successiva risposta di Virgilio si divide in due parti, la prima delle quali dedicata alla profezia del «veltro» che ricaccerà la lupa nell'Inferno da dove è uscita (per le molte interpretazioni di questo personaggio si veda oltre), la seconda al viaggio nell'Oltretomba che Dante dovrà affrontare se vuole scampare da questo loco selvaggio, e in cui sotto la sua guida visiterà Inferno e Purgatorio, mentre se vorrà visitare anche il Paradiso dovrà attendere la guida di Beatrice, in quanto Virgilio è pagano e non è quindi ammesso nel regno di quel Dio che non ha conosciuto. Allegoricamente Beatrice raffigura la grazia santificante e la teologia rivelata, che sola può portare l'uomo alla salvezza, mentre è affermata fin dall'inizio l'insufficienza della ragione naturale, che è in grado di condurre l'uomo al possesso delle virtù cardinali e a una condotta onesta, ma non di arrivare alla beatitudine eterna: è questa l'ossatura allegorica dell'intero poema e la cosa diverrà chiara già dal Canto II, in cui Virgilio rievocherà l'incontro con Beatrice nel Limbo e spiegherà che il viaggio di Dante è voluto da Dio, dunque non è folle  in quanto non affrontato col solo ausilio della ragione dei filosofi che Virgilio rappresenta. La scelta di questo personaggio come guida nella prima parte del viaggio è stata molto discussa, in quanto Dante avrebbe potuto scegliere un filosofo come Aristotele o un personaggio storico come Catone Uticense, ma Virgilio nel Medioevo era ritenuto un pensatore al pari degli altri grandi filosofi antichi, inoltre si riteneva che avesse intravisto alcune verità del Cristianesimo e le avesse preannunciate nelle sue opere (specie nella famosa Egloga IV: cfr. Purg., XXII, in cui Stazio dichiara di essere diventato cristiano grazie alla lettura di quei versi); egli era anche il principale scrittore dell'età di Augusto, sotto il cui Impero il mondo aveva conosciuto pace e giustizia, indispensabili secondo il pensiero medievale affinché potesse diffondersi il Cristianesimo, per cui l'autore dell'Eneide  era in realtà una scelta quasi obbligata come maestro e guida di Dante nel viaggio attraverso i primi due regni ultraterreni. È interessante inoltre osservare che dopo questo primo incontro fra discepolo e maestro si creerà un rapporto di reciproco intenso affetto, per cui Virgilio accudirà Dante come un figlio e questi ricambierà le cure con profondo rispetto e deferenza, fino al momento della separazione in cui Dante si abbandonerà a un pianto disperato (Purg., XXX, 40 ss.). Il Canto si chiude con Dante che, pieno di speranza e di buoni propositi, si accinge a seguire la sua guida per giungere nei luoghi che gli ha preannunciato, salvo poi (all'inizio del Canto seguente) venire assalito da dubbi e timori, che Virgilio fugherà raccontando del suo incontro con Beatrice.";
//    text = "\n            Martina \u00e8 incinta ma resta in carcere  Bocciata istanza di domiciliari. Tribunale ,pericolo reiterazione         \n            Operazione Dia, 20 arresti e sequestri  Eseguita un'ordinanza di custodia cautelare in diverse regioni         \n            A Sara Rattaro il 63\/o Bancarella  Per"
//            + " un voto prevalso su Simona Sparaco         \n            Ubriaco investe con auto pedoni,un morto  A Torino, con Bmw finisce su marciapiede, poi tenta la fuga ";
    //
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
        weightFactor: weightFactor/2
    };
    
    WordCloud(document.getElementById("wordCloud"), options);
    flipChart();
}
