<?php

//////////////////////////////////
//////authentication params///////
//////////////////////////////////

const MIN_LENGTH_PASSWORD = 6;
const HASHING_ALGORITHM = "sha256";


//////////////////////////////////
//////layout & paging params//////
//////////////////////////////////

const DEF_CATS_PER_ROW = 4;
const DEF_FEEDS_PER_ROW = 3;
const ARTICLES_PER_FEED = 5;
const ARTICLES_PER_CATEGORY = 5;
const ARTICLES_PER_PAGE = 5;
const VISIBLE_PAGE_NUM_BEF_AFT = 3;


//////////////////////////////////
/////////size constraints/////////
//////////////////////////////////

const MAX_LENGTH_DESC = 100;
const MAX_LENGTH_TITLE = 75;
const MAX_LENGTH_SUMMARY = 200;
const MIN_IMAGE_WIDTH = 20;
const MIN_IMAGE_HEIGHT = 20;


//////////////////////////////////
//////////user messages///////////
//////////////////////////////////

$USER_MESSAGES = array(
    // authentication errors
    0 => "Errori riscontrati",
    1 => "Il campo email non pu&ograve; essere vuoto",
    2 => "L'indirizzo email inserito non è corretto",
    3 => "L'indirizzo email inserito &egrave; gi&agrave; stato registrato",
    4 => "Il campo password non pu&ograve; essere vuoto",
    5 => "La password deve essere lunga almeno " . MIN_LENGTH_PASSWORD . " caratteri",
    6 => "Le due password inserite non coincidono",
    7 => "Password errata",
    8 => "Errore nella comunicazione con il database",
    9 => "Utente non registrato",
    
    // feed search errors
    10=> "Errore, hai inserito un url che non contiene feed RSS",
    
    // logout messages
    11 => "Logout effettuato con successo.",
    12 => "Logout non effettuato: non sei autenticato.",
    13 => "Verrai reindirizzato a breve alla pagina iniziale...",
    14 => "Se non vieni reindirizzato, clicca ",
    15 => "qui"
    );


//////////////////////////////////
//////////urls and paths//////////
//////////////////////////////////

const DEF_ARTICLE_IMAGE_PATH = "./img/utils/default-article.jpg";
const DEF_FEED_ICON_PATH = "./img/utils/feed_icon.png";
const FAVICON_API_URL = 'http://www.google.com/s2/favicons?domain=';
const DEF_CAT_DIR = "./img/default_cat/";


//////////////////////////////////
//////////date formats////////////
//////////////////////////////////

const DATE_FORMAT = "D j F Y G i";

$MONTHS = array(
    "January" => "gennaio",
    "February" => "febbraio",
    "March" => "marzo",
    "April" => "aprile",
    "May" => "maggio",
    "June" => "giugno",
    "July" => "luglio",
    "August" => "agosto",
    "September" => "settembre",
    "October" => "ottobre",
    "November" => "novembre",
    "December" => "dicembre"
);

$DAYS = array(
    "Mon" => "lun",
    "Tue" => "mar",
    "Wed" => "mer",
    "Thu" => "gio",
    "Fri" => "ven",
    "Sat" => "sab",
    "Sun" => "dom"
);